<?php

class ssRequest extends ssDataAccess
{
	protected $id;
	protected $reference;
	protected $description;
    protected $organisation_id;
    public $type;
    public $status;
	protected $quantity;
	public $quantity_type;
	public $organisation;

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

	public function set_organisation_id( $_organisation_id )
	{
	   $this->set_property( "organisation_id", $_organisation_id );
	}

	public function get_organisation_id()
	{
	    return $this->organisation_id;    
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
	public function focus( $_request_id )
	{

        QUERY::QTABLE("sst_request");

        QUERY::QWHERE(
        	QUERY::condition("request_id", "=", $_request_id)
        );

        $this->set_properties(DB::single(QUERY::QSELECT()));

	}

	public function save()
	{

		$reference  = ( $this->datastate == self::DATASTATE_NEW ? ssUtility::get_reference("REQ") : $this->get_reference() );
		$status 	= ( $this->datastate == self::DATASTATE_NEW ? "req_New" : $this->status->get_code() );

        QUERY::QTABLE("sst_request");

        QUERY::QCOLUMNS(
			"request_reference", 
			"request_description", 
			"status_code", 
			"organisation_id", 
			"request_type", 
			"request_quantity", 
			"quantity_type" 
        );

        QUERY::QVALUES (
        	QUERY::quote($reference), 
        	QUERY::quote($this->get_description()), 
        	QUERY::quote($status), 
        		 		 $this->get_organisation_id(), 
        	QUERY::quote($this->type->get_code()), 
        	QUERY::quote($this->get_quantity()),
        	QUERY::quote($this->quantity_type->get_code())
        );

        /*TODO: error handling */

		switch ( $this->datastate ) 
		{
		    case self::DATASTATE_NEW:
		    	$new_id = DB::query( QUERY::QINSERT());
	        break;
		    case self::DATASTATE_MODIFIED:
	            QUERY::QWHERE(QUERY::condition("request_id", "=", $this->get_id()));
		           DB::query( QUERY::QUPDATE() );
	        break;
		}
	}

    /*
    	Private Methods
	*/
	private function set_properties( $_request_data )
	{

		/*TODO: error handling */

		$this->id 		 			= $_request_data["request_id"];
		$this->reference 			= $_request_data["request_reference"];
		$this->description 			= $_request_data["request_description"];
		$this->status->focus   		( $_request_data["status_code"]);
		$this->organisation_id 		= $_request_data["organisation_id"];
		$this->type->focus 			( $_request_data["request_type"]);
		$this->quantity 	 		= $_request_data["request_quantity"];
		$this->quantity_type->focus ( $_request_data["quantity_type"]);
		$this->organisation->focus  ( $_request_data["organisation_id"]);
		$this->focused 	 			= true;
		$this->datastate 			= self::DATASTATE_CURRENT;

	}

	private function reset_properties()
	{
		$this->id 		 		= 0;
		$this->reference		= "";
		$this->description		= "";
		$this->status   		= new ssStatus();
		$this->organisation_id 	= 0;
		$this->type 	 		= new ssType();
		$this->quantity 	 	= 0;
		$this->quantity_type 	= new ssType();
		$this->organisation 	= new ssOrganisation();
		$this->focused 	 		= false;
		$this->datastate 		= self::DATASTATE_NEW;
	}

}