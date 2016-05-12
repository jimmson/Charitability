<?php

class ssAddress extends ssDataAccess
{
	protected $owning_id;
	protected $owning_table;
	protected $line1;
	protected $line2;
	protected $country;
	protected $state;
	protected $city;
	protected $zip;

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

    public function set_line1( $_line1 )
	{
	 	$this->set_property( "line1", $_line1 );   
	}

	public function get_line1()
	{
	    return $this->line1;    
	}

    public function set_line2( $_line2 )
	{
	 	$this->set_property( "line2", $_line2 );   
	}

	public function get_line2()
	{
	    return $this->line2;    
	}

    public function set_country( $_country )
	{
	 	$this->set_property( "country", $_country );   
	}

	public function get_country()
	{
	    return $this->country;    
	}

    public function set_state( $_state )
	{
	 	$this->set_property( "state", $_state );   
	}

	public function get_state()
	{
	    return $this->state;    
	}

    public function set_city( $_city )
	{
	 	$this->set_property( "city", $_city );   
	}

	public function get_city()
	{
	    return $this->city;    
	}

    public function set_zip( $_zip )
	{
	 	$this->set_property( "zip", $_zip );   
	}

	public function get_zip()
	{
	    return $this->zip;    
	}

    /*
    	Public Methods
	*/
	public function focus( $_owning_table, $_owning_id )
	{
        QUERY::QTABLE("ssm_address");

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
        QUERY::QTABLE("ssm_address");

        QUERY::QCOLUMNS(
        	"owning_id", 
        	"owning_table", 
        	"address_line1", 
        	"address_line2", 
        	"address_country", 
        	"address_state", 
        	"address_city", 
        	"address_zip"
        );

        QUERY::QVALUES (
			QUERY::quote($this->get_owning_id()),
			QUERY::quote($this->get_owning_table()),
			QUERY::quote($this->get_line1()),
			QUERY::quote($this->get_line2()),
			QUERY::quote($this->get_country()),
			QUERY::quote($this->get_state()),
			QUERY::quote($this->get_city()),
			QUERY::quote($this->get_zip())
         );

        /*TODO: error handling */

		switch ( $this->datastate ) 
		{
		    case self::DATASTATE_NEW:
		   		DB::query( QUERY::QINSERT());
	        break;
		    case self::DATASTATE_MODIFIED:
        		QUERY::QWHERE(QUERY::condition("owning_id",    "=", 			 $this->get_owning_id()));
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
		$this->line1   	 	= $_organisation_data["address_line1"];    
		$this->line2   	 	= $_organisation_data["address_line2"];    
		$this->country 	 	= $_organisation_data["address_country"];  
		$this->state   	 	= $_organisation_data["address_state"];    
		$this->city    	 	= $_organisation_data["address_city"];     
		$this->zip       	= $_organisation_data["address_zip"];      

		$this->datastate = self::DATASTATE_CURRENT;
	}

	private function reset_properties()
	{
		$this->owning_id 	= 0;
		$this->owning_table	= 0;
		$this->line1  		= "";
		$this->line2  		= "";
		$this->country 		= "";
		$this->state  		= "";
		$this->city   		= "";
		$this->zip    		= "";

		$this->datastate = self::DATASTATE_NEW;
	}

}