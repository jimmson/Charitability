<?php

class ssOrganisation extends ssDataAccess
{
	protected $id;
    protected $name;
	protected $website;
	protected $logo;

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

    public function set_name( $_name )
	{
	 	$this->set_property( "name", $_name );   
	}

	public function get_name()
	{
	    return $this->name;    
	}

	public function set_website( $_website )
	{
	    $this->set_property( "website", $_website ); 
	}

	public function get_website()
	{
	    return $this->website;    
	}

	public function set_logo( $_logo )
	{
	   $this->set_property( "logo", $_logo );
	}

	public function get_logo()
	{
	    return $this->logo;    
	}

    /*
    	Public Methods
	*/
	public function focus( $_organisation_id )
	{
        QUERY::QTABLE("ssm_organisation");

        QUERY::QWHERE(
        	QUERY::condition("organisation_id", "=", $_organisation_id)
        );

        $this->set_properties(DB::single(QUERY::QSELECT()));
	}

	public function save()
	{
        QUERY::QTABLE("ssm_organisation");

        QUERY::QCOLUMNS(
        	"organisation_name", 
        	"organisation_website",
        	"organisation_logo_path"
        );

        QUERY::QVALUES (
        	QUERY::quote($this->get_name()), 
        	QUERY::quote($this->get_website()),
        	QUERY::quote($this->get_logo())
        );

        /*TODO: error handling */

		switch ( $this->datastate ) 
		{
		    case self::DATASTATE_NEW:

		    	$new_id = DB::query( QUERY::QINSERT());

		        $this->address->set_owning_id( $new_id);
		        $this->address->set_owning_table( "ssm_organisation");		        
		        $this->contact->set_owning_id( $new_id);
		        $this->contact->set_owning_table( "ssm_organisation");

		        //$this->focus($new_id);

	        break;
		    case self::DATASTATE_MODIFIED:
	            QUERY::QWHERE(QUERY::condition("organisation_id", "=", $this->get_id()));
		           DB::query( QUERY::QUPDATE() );
	        break;
		}

		$this->address->save();
        $this->contact->save();
	}

    /*
    	Private Methods
	*/
	private function set_properties( $_organisation_data )
	{

		/*TODO: error handling */

		$this->id 		 = $_organisation_data["organisation_id"];
		$this->name 	 = $_organisation_data["organisation_name"];
		$this->website 	 = $_organisation_data["organisation_website"];
		$this->logo 	 = $_organisation_data["organisation_logo_path"];
		$this->datastate = self::DATASTATE_CURRENT;

		$this->address->focus( "ssm_organisation", $this->get_id() );	
		$this->contact->focus( "ssm_organisation", $this->get_id() );	
	}

	private function reset_properties()
	{
		$this->id 		 = 0;
		$this->name 	 = "";
		$this->website 	 = "";
		$this->logo 	 = "";
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

	public static function authenticate_organisation_user( $_organisation_id, $_user_id )
	{
		QUERY::QTABLE("sst_organisation_user");
		QUERY::QWHERE(QUERY::condition("organisation_id", "=", $_organisation_id));
		QUERY::QAND  (QUERY::condition("user_id", 		  "=", $_user_id));

		return !empty(DB::single(QUERY::QSELECT()));
	}

	public static function get_organisation_data()
	{
		QUERY::QTABLE("ssm_organisation");
	
		return DB::select(QUERY::QSELECT());
	}

}