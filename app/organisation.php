<?php

class ssOrganisation extends ssDataAccess
{
	protected $id;
    protected $name;
	protected $website;
	protected $address_id;
	protected $contact_id;
	protected $logo_id;
	public $logo;

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
		try 
        {
			DB::begin_transaction();

			$this->address 	->save();
			$this->contact 	->save();
			$this->logo->save();

	        QUERY::QTABLE("ssm_organisation");

	        QUERY::QCOLUMNS(
	        	"organisation_name", 
	        	"organisation_website"
	        );

	        QUERY::QVALUES (
	        	QUERY::quote($this->get_name()), 
	        	QUERY::quote($this->get_website())
	        );

	        /*TODO: error handling */

			switch ( $this->datastate ) 
			{
			    case self::DATASTATE_NEW:

			     	QUERY::QCOLUMNS(
			     		"address_id",
			     		"contact_id",
			     		"logi_file_id"
 			     	);

			        QUERY::QVALUES (
			        	$this->address->get_id(),
			        	$this->contact->get_id(),
			        	$this->logo->get_id()
			        );

    				DB::query(
						QUERY::QINSERT()
					);
								
					$this->set_id(DB::inserted_id());

		        break;
			    case self::DATASTATE_MODIFIED:
		            QUERY::QWHERE(QUERY::condition("organisation_id", "=", $this->get_id()));
			           DB::query( QUERY::QUPDATE() );
		        break;
			}
	    }
		catch (Exception $e)
		{
			echo $e->getMessage();
       		DB::rollback();
		}

        DB::commit();
	}

    /*
    	Private Methods
	*/
	private function set_properties( $_organisation_data )
	{

		/*TODO: error handling */

		$this->id 		  = $_organisation_data["organisation_id"];
		$this->name 	  = $_organisation_data["organisation_name"];
		$this->website 	  = $_organisation_data["organisation_website"];
		$this->address_id = $_organisation_data["address_id"];
		$this->contact_id = $_organisation_data["contact_id"];
		$this->logo_id 	  = $_organisation_data["logo_file_id"];
		$this->datastate  = self::DATASTATE_CURRENT;

		$this->address 	->focus($this->address_id);	
		$this->contact 	->focus($this->contact_id);	
		$this->logo->focus($this->logo_id);
	}

	private function reset_properties()
	{
		$this->id 		  = 0;
		$this->name 	  = "";
		$this->website 	  = "";
		$this->address_id = 0;
		$this->contact_id = 0;
		$this->logo_id = 0;
		$this->datastate  = self::DATASTATE_NEW;

		$this->address 	  = new ssAddress();
		$this->contact 	  = new ssContact();
		$this->logo  = new ssFile();

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