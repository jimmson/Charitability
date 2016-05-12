<?php

class ssStatus extends ssDataAccess
{
	protected $code;
	protected $label;
	protected $description;
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
	public function set_code( $_code )
    {
        $this->code = $_code;
    }

    public function get_code()
    {
        return $this->code;    
    }

    private function set_label( $_label )
    {
        $this->label = $_label;
    }

    public function get_label()
    {
        return $this->label;    
    }

	public function set_description( $_description )
	{
	   $this->set_property( "description", $_description );
	}

	public function get_description()
	{
	    return $this->description;    
	}

    /*
    	Public Methods
	*/
	public function focus( $_status_code )
	{

        QUERY::QTABLE("ssc_status");

        QUERY::QWHERE(
        	QUERY::condition("status_code", "=", QUERY::quote($_status_code))
        );

        $this->set_properties(DB::single(QUERY::QSELECT()));

	}

	public function save()
	{

        QUERY::QTABLE("sst_status");

        QUERY::QCOLUMNS(
			"status_code", 
			"status_label", 
			"status_description"
        );

        QUERY::QVALUES (
        	QUERY::quote($this->get_code()), 
 		 	QUERY::quote($this->get_label()), 
 			QUERY::quote($this->get_description())
        );

        /*TODO: error handling */

		switch ( $this->datastate ) 
		{
		    case self::DATASTATE_NEW:
		    	$new_id = DB::query( QUERY::QINSERT());
	        break;
		    case self::DATASTATE_MODIFIED:
	            QUERY::QWHERE(QUERY::condition("status_code", "=", QUERY::quote($this->get_code())));
		           DB::query( QUERY::QUPDATE() );
	        break;
		}
	}

    /*
    	Private Methods
	*/
	private function set_properties( $_status_data )
	{

		/*TODO: error handling */

		$this->code 		 	= $_status_data["status_code"];
		$this->label 			= $_status_data["status_label"];
		$this->description 		= $_status_data["status_description"];
		$this->focused 	 		= true;
		$this->datastate 		= self::DATASTATE_CURRENT;

	}

	private function reset_properties()
	{
		$this->code 		 	= "";
		$this->label 			= "";
		$this->description		= "";
		$this->focused 	 		= false;
		$this->datastate 		= self::DATASTATE_NEW;
	}

}