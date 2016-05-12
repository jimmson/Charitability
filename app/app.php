<?php 

class ssApp {

	const LOGIN_PAGE 	= "/login/view";
	const HOME_PAGE 	= "/home/view";

	public static function handle_request( $_request_url )
	{
		$request 	= split("/", $_request_url);
		$req_page 	= $request[1];
		$req_action = $request[2];
		$req_args	= array_splice( $request, 3);

		$page 		= new $req_page();

		if ( (!ssSession::available() && $page->requires_session()) && $req_page != "login" )
		{
			self::handle_redirect( self::LOGIN_PAGE );
		}

		$page->define();	

		call_user_func( array( $page, $req_action ), $req_args );

		$page->render();
	}

	public static function handle_redirect( $_redirect_url )
	{
		header("Location: " . $_redirect_url );
		die();
	}

}