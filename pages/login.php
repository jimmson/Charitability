<?php

 use bones\base\page;
 use bones\containers\div;
 use bones\containers\form;
 use bones\containers\select;
 use bones\controls\input;
 use bones\controls\button;
 use bones\controls\h;
 use bones\controls\option;

class login extends page
{
	//containers
	private $form;

	//controls
    private $email;
	private $organisation;
	private $password;
	private $submit;
	private $headding;

	public function __construct()
	{
		parent::__construct( "Welcome" );
    }

    public function define()
    {     
        $this->requires_session = false;

        $this->add_stylesheet( "http://getbootstrap.com/dist/css/bootstrap.min.css" );
        $this->add_stylesheet( "https://getbootstrap.com/examples/signin/signin.css" );
        $this->add_meta( "viewport", "width=device-width, initial-scale=1" );

    	$this->headding     = new h("");
    	$this->submit 	    = new button("submit_button");
    	$this->form 	    = new form("", form::POST, "/login/login");
        $this->organisation = $this->get_select();
    	$this->email 	    = new input("email");
    	$this->password     = new input("password", input::PASSWORD);

    	$this->form->add( $this->headding, $this->organisation, $this->email, $this->password, $this->submit );
    }

    public function view()
    {
        $this->submit       ->set_class("btn", "btn-lg", "btn-primary", "btn-block");
        $this->form         ->set_class("form-signin");
        $this->organisation ->set_class("form-control");
        $this->email        ->set_class("form-control");
        $this->password     ->set_class("form-control");

    //    $this->organisation ->set_placeholder("Organisation");
        $this->email        ->set_placeholder("Email Address");
        $this->password     ->set_placeholder("Password");

        $this->headding ->set_text("Please sign in");
        $this->submit   ->set_text("Sign in");
        
        $this->add ($this->form );
    }

    public function get_select()
    {
        $select = new select("organisation");
        $self   = new option();
        $option = new option();
        $data   = ssOrganisation::get_organisation_data();

        $self   ->set_value("self");
        $self   ->set_text("Self");
        $option ->set_data_items("organisation_id","organisation_name");
        $option ->set_data_properties("set_value","set_text");
        $select ->set_data( $data );

        $select->add($self, $option);

        return $select;
    }

    public function login()
    {
        $organisation_id = ($_POST["organisation"] == "self" ? null : $_POST["organisation"]);
        $organisation = null;

        $user_id = ssUser::authenticate($_POST["email"], $_POST["password"]);

        if (!$user_id) 
        {
            /* TODO: return with error */
           ssApp::handle_redirect( ssApp::LOGIN_PAGE);
        }

        $user = new ssUser();
        $user->focus( $user_id );

        if ( $organisation_id )
        {
            if (ssOrganisation::authenticate_organisation_user($organisation_id, $user_id))
            {
                $organisation = new ssOrganisation();
                $organisation->focus( $organisation_id );
            }
            else
            {
                /* TODO: return with error */
                ssApp::handle_redirect( ssApp::LOGIN_PAGE);
            }
        }

        ssSession::create( $user,  $organisation);

        ssApp::handle_redirect( ssApp::HOME_PAGE);
 
    }

    public function logout()
    {
        ssSession::destroy();
        ssApp::handle_redirect(ssApp::LOGIN_PAGE);
    }



}