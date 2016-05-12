<?php 

class ssSession {

	const USER_IDENTIFIER 			= "user_id"; 
	const ORGANISATION_IDENTIFIER 	= "organisation_id";

	public  static $user;
	public  static $organisation;

	public static function available()
	{
		return !empty($_SESSION);
	}

	public static function get_variable( $_variable )
	{
		if ( isset( $_SESSION[$_variable] ) )
		{
			return $_SESSION[$_variable];
		}
		else 
			return null;
	}

	public static function set_variable( $_variable, $_value )
	{
		$_SESSION[$_variable] = trim($_value);
	}	

	public static function create( $_user, $_organisation = null )
	{ 
		/* TODO error handling - msut be user class and in focus */
		self::$user = $_user;
		self::set_variable( self::USER_IDENTIFIER, self::$user->get_id() );

		if ( $_organisation )
		{
			self::$organisation = $_organisation;
			self::set_variable( self::ORGANISATION_IDENTIFIER, self::$organisation->get_id() );
		}
	}

	public static function focus()
	{ 
		/* TODO error handling  */
		$user_identifier 		 = self::get_variable( self::USER_IDENTIFIER );
		$organisation_identifier = self::get_variable( self::ORGANISATION_IDENTIFIER );

		self::$user = new ssUser;
		self::$user->focus( $user_identifier );

		if ($organisation_identifier)
		{
			self::$organisation = new ssOrganisation;
			self::$organisation->focus( $organisation_identifier );
		}
	}

	public static function start()
	{
		session_start();
	}

	public static function destroy()
	{
		unset($_SESSION);
		session_destroy();
		session_write_close();
	}
}