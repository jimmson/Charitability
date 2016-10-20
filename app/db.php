<?php 


class DB {

	private static $transaction = false;

	// The database connection
	protected static $connection;
	
	public static function connect() {
		
		if( !self::connected() ) 
		{
			$config = parse_ini_file('config.ini'); 
			
			self::$connection = new mysqli('localhost',$config['username'],$config['password'],$config['dbname']);
		}

		if(self::$connection === false) 
		{
			throw new Exception( self::$connection->error, 1);
		}

	}

	public static function connected() 
	{
 		return isset( self::$connection );
	}


	public static function begin_transaction() 
	{
		self::connect();
		self::$connection->autocommit(false);
		self::$connection->begin_transaction();
	}

	public static function commit() 
	{
		self::$connection->commit();
		self::$connection->autocommit(true);
	}

	public static function rollback() 
	{
		self::$connection->rollback();
		self::$connection->autocommit(true);
	}

	public static function inserted_id() 
	{
		return self::$connection->insert_id;
	}
	
	public static function query( $_query ) 
	{
		self::connect();

		if (!self::$connection->query( $_query ))
		{
			throw new Exception(self::error_text());
		}
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

	public function error_text()
	{
		return mysqli_error(self::$connection);
	}
	
}