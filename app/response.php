<?php

class ssResponse extends ssDataAccess
{
	protected $id;
	protected $reference;
	protected $description;
    protected $user_id;
    protected $organisation_id;
	protected $quantity;
	public $organisation;
	public $request;

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

	public function set_reference( $_reference )
	{
	   $this->set_property( "reference", $_reference );
	}

	public function get_reference()
	{
	    return $this->reference;    
	}

	public function set_description( $_description )
	{
	   $this->set_property( "description", $_description );
	}

	public function get_description()
	{
	    return $this->description;    
	}

	public function set_response_id( $_response_id )
	{
	   $this->set_property( "response_id", $_response_id );
	}

	public function get_response_id()
	{
	    return $this->response_id;    
	}

	public function set_request_id( $_request_id )
	{
	   $this->set_property( "request_id", $_request_id );
	}

	public function get_request_id()
	{
	    return $this->request_id;    
	}

	public function set_organisation_id( $_organisation_id )
	{
	   $this->set_property( "organisation_id", $_organisation_id );
	}

	public function get_organisation_id()
	{
	    return $this->organisation_id;    
	}

	public function set_user_id( $_user_id )
	{
	   $this->set_property( "user_id", $_user_id );
	}

	public function get_user_id()
	{
	    return $this->user_id;    
	}

	public function set_quantity( $_quantity )
	{
	   $this->set_property( "quantity", $_quantity );
	}

	public function get_quantity()
	{
	    return $this->quantity;    
	}

    /*
    	Public Methods
	*/
	public function focus( $_response_id )
	{

        QUERY::QTABLE("sst_response");

        QUERY::QWHERE(
        	QUERY::condition("response_id", "=", $_response_id)
        );

        $this->set_properties(DB::single(QUERY::QSELECT()));

	}

	public function save()
	{

		$reference = ( $this->datastate == self::DATASTATE_NEW ? ssUtility::get_reference("RES") : $this->get_reference() );

        QUERY::QTABLE("sst_response");

        QUERY::QCOLUMNS(
			"response_reference", 
			"response_description", 
			"request_id", 
			"organisation_id", 
			"user_id", 
			"response_quantity"
        );

        QUERY::QVALUES (
        	QUERY::quote($reference), 
        	QUERY::quote($this->get_description()), 
        		 		 $this->get_request_id(), 
        		 		 $this->get_organisation_id(), 
        		 		 $this->get_user_id(), 
        	QUERY::quote($this->get_quantity())
        );

        /*TODO: error handling */

		switch ( $this->datastate ) 
		{
		    case self::DATASTATE_NEW:
		    	$new_id = DB::query( QUERY::QINSERT());
	        break;
		    case self::DATASTATE_MODIFIED:
	            QUERY::QWHERE(QUERY::condition("response_id", "=", $this->get_id()));
		           DB::query( QUERY::QUPDATE() );
	        break;
		}
	}

    /*
    	Private Methods
	*/
	private function set_properties( $_response_data )
	{

		/*TODO: error handling */

		$this->id 		 			= $_response_data["response_id"];
		$this->reference 			= $_response_data["response_reference"];
		$this->description 			= $_response_data["response_description"];
		$this->request_id 			= $_response_data["request_id"];
		$this->organisation_id 		= $_response_data["organisation_id"];
		$this->user_id 				= $_response_data["user_id"];
		$this->quantity 	 		= $_response_data["response_quantity"];
		$this->organisation->focus 	( $_response_data["organisation_id"]);
		$this->request->focus 		( $_response_data["request_id"]);
		$this->focused 	 			= true;
		$this->datastate 			= self::DATASTATE_CURRENT;

	}

	private function reset_properties()
	{
		$this->id 		 		= 0;
		$this->reference		= "";
		$this->description		= "";
		$this->request_id 		= 0;
		$this->organisation_id 	= 0;
		$this->user_id 			= 0;
		$this->quantity 	 	= 0;
		$this->organisation 	= new ssOrganisation();
		$this->request 			= new ssRequest();
		$this->focused 	 		= false;
		$this->datastate 		= self::DATASTATE_NEW;
	}

}