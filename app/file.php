<?php

class ssFile extends ssDataAccess
{
	protected $id;
    protected $location;

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

    public function set_location( $_location )
	{
	 	$this->set_property( "location", $_location );   
	}

	public function get_location()
	{
	    return $this->location;    
	}

    
    /*
    	Public Methods
	*/
	public function focus( $_file_id )
	{
        QUERY::QTABLE("ssm_file");

        QUERY::QWHERE(
        	QUERY::condition("file_id", "=", $_file_id)
        );

        $this->set_properties(DB::single(QUERY::QSELECT()));
	}

	public function save()
	{
        QUERY::QTABLE("ssm_file");

        QUERY::QCOLUMNS(
        	"file_location"
        );

        QUERY::QVALUES (
        	QUERY::quote($this->get_location())
        );

		switch ( $this->datastate ) 
		{
		    case self::DATASTATE_NEW:
		    	DB::query( 
	    			QUERY::QINSERT()
		    	);

				$this->set_id(DB::inserted_id());

	        break;
		    case self::DATASTATE_MODIFIED:
	            QUERY::QWHERE(QUERY::condition("file_id", "=", $this->get_id()));
		           DB::query( QUERY::QUPDATE() );
	        break;
		}
	}

	public function delete()
	{
		if ($this->datastate == self::DATASTATE_NEW) 
			return;

        QUERY::QTABLE("ssm_file");
        QUERY::QWHERE(QUERY::condition("file_id", "=", $this->get_id()));   

        DB::query( QUERY::QDELETE() );

        $this->reset_properties();
	}

	function store_uploaded_file( $_file_name, $_file_path )
	{

        $directory = "public/uploads/";
        $extension = "." . pathinfo( $_file_name, PATHINFO_EXTENSION );
        $file_name = ssUtility::get_reference("IMG");
        $file_path = $directory . $file_name . $extension;

        move_uploaded_file( $_file_path, $file_path ); 

        $this->set_location("/" . $file_path);
	}

    /*
    	Private Methods
	*/
	private function set_properties( $_file_data )
	{
		/*TODO: error handling */
		$this->id            = $_file_data["file_id"];
		$this->location      = $_file_data["file_location"];
		$this->datastate 	 = self::DATASTATE_CURRENT;
	}

	private function reset_properties()
	{
		$this->id            = 0;
		$this->location      = "";
		$this->datastate 	 = self::DATASTATE_NEW;
	}

	public static function get_file_data()
	{
		QUERY::QTABLE("ssm_file");
	
		return DB::select(QUERY::QSELECT());
	}

}