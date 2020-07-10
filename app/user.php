<?php

class ssUser extends ssDataAccess
{
    const STATUS_NEW 	= "usr_New";
    const STATUS_ACTIVE = "usr_Act";

    protected $id;
    protected $name;
    protected $surname;
    protected $email;
    protected $focused;
    protected $password;
    protected $auth_token;

    public $status;
    public $address;
    public $contact;


    /*
        Constructor
    */
    public function __construct()
    {
        $this->reset_properties();
    }

    /*
        Setter's and Getter's
    */
    private function set_id($_id)
    {
        $this->id = $_id;
    }

    public function get_id()
    {
        return $this->id;
    }

    public function focused()
    {
        return $this->focused;
    }

    public function set_name($_name)
    {
        $this->set_property("name", $_name);
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_surname($_surname)
    {
        $this->set_property("surname", $_surname);
    }

    public function get_surname()
    {
        return $this->surname;
    }

    public function set_password($_password)
    {
        $this->set_property("password", md5($_password));
    }

    public function set_picture($_picture)
    {
        $this->set_property("picture", $_picture);
    }

    public function get_picture()
    {
        return $this->picture;
    }

    /*
        Public Methods
    */
    public function focus($_user_id)
    {
        QUERY::QTABLE("ssm_user");

        QUERY::QWHERE(
            QUERY::condition("user_id", "=", $_user_id)
        );

        $user_data = DB::single(QUERY::QSELECT());

        if ($user_data) {
            $this->set_properties($user_data);
            return true;
        } else {
            return false;
        }
    }

    public function save()
    {
        $this->auth_token = ($this->datastate == self::DATASTATE_NEW ? md5(ssUtility::get_reference()) : $this->auth_token);
        $status 		  = ($this->datastate == self::DATASTATE_NEW ? self::STATUS_NEW 				  : $this->status->get_code());

        QUERY::QTABLE("ssm_user");

        QUERY::QCOLUMNS(
            "user_name",
            "user_surname",
            "user_picture_path",
            "user_auth_token",
            "status_code"
        );

        QUERY::QVALUES(
            QUERY::quote($this->get_name()),
            QUERY::quote($this->get_surname()),
            QUERY::quote($this->get_picture()),
            QUERY::quote($this->auth_token),
            QUERY::quote($status)
        );

        if ($this->password) {
            QUERY::QCOLUMNS("user_password");
            QUERY::QVALUES(QUERY::quote($this->password));
        }

        try {
            DB::begin_transaction();

            switch ($this->datastate) {
                case self::DATASTATE_NEW:
                    DB::query(
                        QUERY::QINSERT()
                    );

                    $new_id = DB::inserted_id();

                    $this->address->set_owning_id($new_id);
                    $this->address->set_owning_table("ssm_user");
                    $this->contact->set_owning_id($new_id);
                    $this->contact->set_owning_table("ssm_user");

                    $this->send_welcome_mail();
                    //$this->focus($new_id);

                break;
                case self::DATASTATE_MODIFIED:
                    QUERY::QWHERE(
                        QUERY::condition("user_id", "=", $this->get_id())
                    );

                    DB::query(
                        QUERY::QUPDATE()
                    );
                break;
            }

            $this->address->save();
            $this->contact->save();
        } catch (Exception $e) {
            echo $e->getMessage();
            DB::rollback();
        }
        DB::commit();
    }

    /*
        Private Methods
    */
    private function set_properties($_user_data)
    {
        $this->id 		  = $_user_data["user_id"];
        $this->name 	  = $_user_data["user_name"];
        $this->surname 	  = $_user_data["user_surname"];
        $this->picture 	  = $_user_data["user_picture_path"];
        $this->auth_token = $_user_data["user_auth_token"];
        $this->focused 	  = true;
        $this->datastate  = self::DATASTATE_CURRENT;

        $this->status ->focus($_user_data["status_code"]);
        $this->address->focus("ssm_user", $this->get_id());
        $this->contact->focus("ssm_user", $this->get_id());
    }

    private function reset_properties()
    {
        $this->id 		  = 0;
        $this->name 	  = "";
        $this->surname 	  = "";
        $this->picture 	  = "";
        $this->auth_token = "";
        $this->focused 	  = false;
        $this->datastate  = self::DATASTATE_NEW;

        $this->status    = new ssStatus();
        $this->address 	 = new ssAddress();
        $this->contact 	 = new ssContact();
    }

    public function get_data_array()
    {
        $data = parent::get_data_array();

        unset($data["address"]);
        unset($data["contact"]);
        unset($data["password"]);

        return $data;
    }


    public static function authenticate($_email, $_password)
    {
        QUERY::QTABLE("ssm_contact");
        QUERY::QCOLUMNS("user_id");
        QUERY::QJOIN("ssm_user", QUERY::condition("ssm_user.contact_id", "=", "ssm_contact.contact_id"));
        QUERY::QWHERE(QUERY::condition("contact_email", "=", QUERY::quote($_email)));
        QUERY::QAND(QUERY::condition("user_password", "=", QUERY::quote(md5($_password))));
    
        $user_data = DB::single(QUERY::QSELECT());

        if ($user_data) {
            return $user_data["user_id"];
        } else {
            return false;
        }
    }

    public static function verify($_auth_token)
    {
        QUERY::QTABLE("ssm_user");
        QUERY::QCOLUMNS("user_id");
        QUERY::QWHERE(QUERY::condition("user_auth_token", "=", QUERY::quote($_auth_token)));
    
        $user_data = DB::single(QUERY::QSELECT());

        if ($user_data) {
            return $user_data["user_id"];
        } else {
            return false;
        }
    }

    private function send_welcome_mail()
    {
        $subject  = "SS - Registration";
        $message  = "Hi " . $this->get_name() ."\n\n" ;
        $message .= "Thank you for registering with SS. \n";
        $message .= "Please click the folliwing link to verify your email address: \n";
        $message .= "http://charitability.com/register/confirm/" . $this->auth_token . "\n\n";
        $message .= "Kind Regards\n";
        $message .= "Team SS";

        $mail = new ssMail();
        $mail->set_to($this->contact->get_email());
        $mail->set_subject($subject);
        $mail->set_message($message);

        $mail->send();
    }
}
