<?php

class ssDataAccess 
{
	const DATASTATE_EMPTY 	 = 0;
	const DATASTATE_CURRENT  = 1;
	const DATASTATE_NEW 	 = 2;
	const DATASTATE_MODIFIED = 3;

	protected $datastate;

    /*
    	Protected Methods
	*/
    protected function set_property( $_property, $_value )
    {
    	$_value = trim($_value);

    	/*TODO: error handling */

    	if ( $this->$_property != $_value )
    	{
    		$this->$_property = $_value;
    		$this->property_changed();
    	}
    }

	protected function property_changed()
	{
		if ( $this->datastate != self::DATASTATE_NEW )
		{
			$this->datastate = self::DATASTATE_MODIFIED;
		}
	}

	public function get_data_array()
	{
		return get_object_vars($this);
	}
}
