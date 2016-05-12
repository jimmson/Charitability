<?php

class ssUser extends ssDataAccess
{
	protected $id;
    protected $name;
    protected $surname;
	protected $email;
	protected $focused;
	
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
    private function set_id( $_id )
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

    public function set_name( $_name )
	{
	 	$this->set_property( "name", $_name );   
	}

	public function get_name()
	{
	    return $this->name;    
	}

    public function set_surname( $_surname )
	{
	 	$this->set_property( "surname", $_surname );   
	}

	public function get_surname()
	{
	    return $this->surname;    
	}

	public function set_picture( $_picture )
	{
	   $this->set_property( "picture", $_picture );
	}

	public function get_picture()
	{
	    return $this->picture;    
	}

    /*
    	Public Methods
	*/
	public function focus( $_user_id )
	{

        QUERY::QTABLE("ssm_user");

        QUERY::QWHERE(
        	QUERY::condition("user_id", "=", $_user_id)
        );

        $this->set_properties(DB::single(QUERY::QSELECT()));

	}

	public function save()
	{
        QUERY::QTABLE("ssm_user");

        QUERY::QCOLUMNS(
        	"user_name", 
        	"user_surname", 
        	"user_picture_path"
        );

        QUERY::QVALUES (
        	QUERY::quote($this->get_name()), 
        	QUERY::quote($this->get_surname()), 
        	QUERY::quote($this->get_picture())
        );

        /*TODO: error handling */

		switch ( $this->datastate ) 
		{
		    case self::DATASTATE_NEW:

		    	$new_id = DB::query( QUERY::QINSERT());

		        $this->address->set_owning_id( $new_id);
		        $this->address->set_owning_table("ssm_user");		        
		        $this->contact->set_owning_id( $new_id);
		        $this->contact->set_owning_table("ssm_user");

		        //$this->focus($new_id);

	        break;
		    case self::DATASTATE_MODIFIED:
	            QUERY::QWHERE(QUERY::condition("user_id", "=", $this->get_id()));
		           DB::query( QUERY::QUPDATE() );
	        break;
		}

		$this->address->save();
        $this->contact->save();
	}

    /*
    	Private Methods
	*/
	private function set_properties( $_user_data )
	{

		/*TODO: error handling */

		$this->id 		 = $_user_data["user_id"];
		$this->name 	 = $_user_data["user_name"];
		$this->surname 	 = $_user_data["user_surname"];
		$this->picture 	 = $_user_data["user_picture_path"];
		$this->focused 	 = true;
		$this->datastate = self::DATASTATE_CURRENT;

		$this->address->focus( "ssm_user", $this->get_id() );	
		$this->contact->focus( "ssm_user", $this->get_id() );	
	}

	private function reset_properties()
	{
		$this->id 		 = 0;
		$this->name 	 = "";
		$this->surname 	 = "";
		$this->picture 	 = "";
		$this->focused 	 = false;
		$this->datastate = self::DATASTATE_NEW;

		$this->address 	 = new ssAddress();
		$this->contact 	 = new ssContact();

	}

	public function get_data_array()
	{
		$data = parent::get_data_array();

	 	unset($data["address"]);
	  	unset($data["contact"]);

		return $data;
	}


	public static function authenticate( $_email, $_password )
	{
		QUERY::QTABLE("ssm_contact");
	    QUERY::QCOLUMNS("user_id");
 		QUERY::QJOIN("ssm_user", QUERY::condition("ssm_contact.owning_id", "=", "ssm_user.user_id"));
		QUERY::QWHERE(QUERY::condition("contact_email", "=", QUERY::quote($_email)));
		QUERY::QAND  (QUERY::condition("user_password", "=", QUERY::quote(md5($_password))));
	
		$user_data = DB::single(QUERY::QSELECT());

		if (empty($user_data))
			return false;
		else
			return $user_data["user_id"];	
	}

}