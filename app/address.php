<?php

class ssAddress extends ssDataAccess
{
	protected $id;
	protected $number;
	protected $street;
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
    private function set_id( $_id )
	{
	    $this->id = $_id;
	}

	public function get_id()
	{
	    return $this->id;    
	}

    public function set_number( $_number )
	{
	 	$this->set_property( "number", $_number );   
	}

	public function get_number()
	{
	    return $this->number;    
	}

    public function set_street( $_street )
	{
	 	$this->set_property( "street", $_street );   
	}

	public function get_street()
	{
	    return $this->street;    
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
	public function focus( $_address_id )
	{
        QUERY::QTABLE("ssm_address");

        QUERY::QWHERE(
        	QUERY::condition("address_id", "=", QUERY::quote($_address_id))
        );

        $this->set_properties(DB::single(QUERY::QSELECT()));
	}

	public function save()
	{
        QUERY::QTABLE("ssm_address");

        QUERY::QCOLUMNS(
        	"address_number", 
        	"address_street", 
        	"address_country", 
        	"address_state", 
        	"address_city", 
        	"address_zip"
        );

        QUERY::QVALUES (
			QUERY::quote($this->get_number()),
			QUERY::quote($this->get_street()),
			QUERY::quote($this->get_country()),
			QUERY::quote($this->get_state()),
			QUERY::quote($this->get_city()),
			QUERY::quote($this->get_zip())
         );

        /*TODO: error handling */

		switch ( $this->datastate ) 
		{
		    case self::DATASTATE_NEW:
				DB::query( 
					QUERY::QINSERT()
				);

				$this->set_id(DB::inserted_id());

	        break;
		    case self::DATASTATE_MODIFIED:
        		QUERY::QWHERE(QUERY::condition("address_id", "=", $this->get_id()));
		        DB::query(
		        	QUERY::QUPDATE()
		        );
	        break;
		}
	}

    /*
    	Private Methods
	*/
	private function set_properties( $_address_data )
	{
		/*TODO: error handling */
		$this->id 			= $_address_data["address_id"];
		$this->number   	= $_address_data["address_number"];    
		$this->street   	= $_address_data["address_street"];    
		$this->country 	 	= $_address_data["address_country"];  
		$this->state   	 	= $_address_data["address_state"];    
		$this->city    	 	= $_address_data["address_city"];     
		$this->zip       	= $_address_data["address_zip"];      

		$this->datastate = self::DATASTATE_CURRENT;
	}

	private function reset_properties()
	{
		$this->id 			= 0;
		$this->number  		= "";
		$this->street  		= "";
		$this->country 		= "";
		$this->state  		= "";
		$this->city   		= "";
		$this->zip    		= "";

		$this->datastate = self::DATASTATE_NEW;
	}

}