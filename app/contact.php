<?php

class ssContact extends ssDataAccess
{
    protected $id;
    protected $phone;
    protected $fax;
    protected $email;

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

    public function set_phone($_phone)
    {
        $this->set_property("phone", $_phone);
    }

    public function get_phone()
    {
        return $this->phone;
    }

    public function set_fax($_fax)
    {
        $this->set_property("fax", $_fax);
    }

    public function get_fax()
    {
        return $this->fax;
    }

    public function set_email($_email)
    {
        $this->set_property("email", $_email);
    }

    public function get_email()
    {
        return $this->email;
    }

    /*
        Public Methods
    */
    public function focus($_contact_id)
    {
        QUERY::QTABLE("ssm_contact");

        QUERY::QWHERE(
            QUERY::condition("contact_id", "=", QUERY::quote($_contact_id))
        );

        $this->set_properties(DB::single(QUERY::QSELECT()));
    }

    public function save()
    {
        QUERY::QTABLE("ssm_contact");

        QUERY::QCOLUMNS(
            "contact_phone",
            "contact_fax",
            "contact_email"
        );

        QUERY::QVALUES(
            QUERY::quote($this->get_phone()),
            QUERY::quote($this->get_fax()),
            QUERY::quote($this->get_email())
        );

        /*TODO: error handling */

        switch ($this->datastate) {
            case self::DATASTATE_NEW:
                DB::query(
                    QUERY::QINSERT()
                );
                                
                $this->set_id(DB::inserted_id());
            break;
            case self::DATASTATE_MODIFIED:
                QUERY::QWHERE(QUERY::condition("contact_id", "=", $this->get_id()));
                DB::query(
                    QUERY::QUPDATE()
                );
            break;
        }
    }

    /*
        Private Methods
    */
    private function set_properties($_contact_data)
    {
        /*TODO: error handling */
        $this->id 			= $_contact_data["contact_id"];
        $this->phone   	 	= $_contact_data["contact_phone"];
        $this->fax   	 	= $_contact_data["contact_fax"];
        $this->email 	 	= $_contact_data["contact_email"];

        $this->datastate = self::DATASTATE_CURRENT;
    }

    private function reset_properties()
    {
        $this->phone  		= "";
        $this->fax  		= "";
        $this->email 		= "";

        $this->datastate = self::DATASTATE_NEW;
    }
}
