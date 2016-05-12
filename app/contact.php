<?php

class ssContact extends ssDataAccess
{
	protected $owning_id;
	protected $owning_table;
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
    public function set_owning_id( $_owning_id )
	{
	    $this->owning_id = $_owning_id;
	}

	public function get_owning_id()
	{
	    return $this->owning_id;    
	}

    public function set_owning_table( $_owning_table )
	{
	    $this->owning_table = $_owning_table;
	}

	public function get_owning_table()
	{
	    return $this->owning_table;    
	}

    public function set_phone( $_phone )
	{
	 	$this->set_property( "phone", $_phone );   
	}

	public function get_phone()
	{
	    return $this->phone;    
	}

    public function set_fax( $_fax )
	{
	 	$this->set_property( "fax", $_fax );   
	}

	public function get_fax()
	{
	    return $this->fax;    
	}

    public function set_email( $_email )
	{
	 	$this->set_property( "email", $_email );   
	}

	public function get_email()
	{
	    return $this->email;    
	}

    /*
    	Public Methods
	*/
	public function focus( $_owning_table, $_owning_id )
	{
        QUERY::QTABLE("ssm_contact");

        QUERY::QWHERE(
        	QUERY::condition("owning_table", "=", QUERY::quote($_owning_table))
        );

        QUERY::QAND(
        	QUERY::condition("owning_id", "=", $_owning_id)
        );

        $this->set_properties(DB::single(QUERY::QSELECT()));
	}

	public function save()
	{
        QUERY::QTABLE("ssm_contact");

        QUERY::QCOLUMNS(
        	"owning_id", 
        	"owning_table", 
        	"contact_phone", 
        	"contact_fax", 
        	"contact_email" 
        );

        QUERY::QVALUES (
			QUERY::quote($this->get_owning_id()),
			QUERY::quote($this->get_owning_table()),
			QUERY::quote($this->get_phone()),
			QUERY::quote($this->get_fax()),
			QUERY::quote($this->get_email())
         );

        /*TODO: error handling */

		switch ( $this->datastate ) 
		{
		    case self::DATASTATE_NEW:
		        DB::query( QUERY::QINSERT());
	        break;
		    case self::DATASTATE_MODIFIED:
        		QUERY::QWHERE(QUERY::condition("owning_id",    "=", 		     $this->get_owning_id()));
		        QUERY::QAND  (QUERY::condition("owning_table", "=", QUERY::quote($this->get_owning_table())));
		           DB::query (QUERY::QUPDATE());
	        break;
		}
	}

    /*
    	Private Methods
	*/
	private function set_properties( $_organisation_data )
	{
		/*TODO: error handling */
		$this->owning_id 	= $_organisation_data["owning_id"];
		$this->owning_table = $_organisation_data["owning_table"];
		$this->phone   	 	= $_organisation_data["contact_phone"];    
		$this->fax   	 	= $_organisation_data["contact_fax"];    
		$this->email 	 	= $_organisation_data["contact_email"];  

		$this->datastate = self::DATASTATE_CURRENT;
	}

	private function reset_properties()
	{
		$this->owning_id 	= 0;
		$this->owning_table	= 0;
		$this->phone  		= "";
		$this->fax  		= "";
		$this->email 		= "";

		$this->datastate = self::DATASTATE_NEW;
	}

}