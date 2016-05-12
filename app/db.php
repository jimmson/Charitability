<?php 


class DB {
	// The database connection
	protected static $connection;
	
	public static function connect() {
		
		if( !isset( self::$connection ) ) 
		{
			$config = parse_ini_file('config.ini'); 
			
			self::$connection = new mysqli('localhost',$config['username'],$config['password'],$config['dbname']);
		}

		if(self::$connection === false) 
		{
			throw new Exception( self::$connection->error, 1);
		}

	}
	
	public static function query( $_query ) 
	{
		self::connect();

		self::$connection->query( $_query );

		return (self::$connection->insert_id > 0 ? self::$connection->insert_id : false);

	}
	
	public static function select( $_query ) 
	{
		self::connect();

		$rows 	= array();
		$result = self::$connection->query($_query);

		if ( $result === false ) 
		{
			return false;
		}

		while ($row = $result->fetch_assoc() ) 
		{
			$rows[] = $row;
		}

		return $rows;
	}

	public static function single( $_query ) 
	{
		$result = self::select( $_query ) ;

		if (empty($result))
			return false;
		else
			return $result[0];	
	}
	
}