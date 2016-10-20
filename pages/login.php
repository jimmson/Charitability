<?php

use bones\base\page;
use bones\containers\div;
use bones\containers\form;
use bones\containers\select;
use bones\controls\input;
use bones\containers\button;
use bones\controls\h;
use bones\controls\option;

use bootstrap\controls\binput;
use bootstrap\containers\bselect;
use bootstrap\containers\bpanel;
use bootstrap\layouts\grid;

class login extends page
{
	public function __construct()
	{
		parent::__construct( "Welcome" );
    }

    public function define()
    {     
        $this->requires_session = false;

        $this->add_stylesheet( "/public/bootstrap/dist/css/bootstrap.min.css" );
        $this->add_stylesheet( "/public/metisMenu/dist/metisMenu.min.css" );
        $this->add_stylesheet( "/public/css/sb-admin-2.css" );
        $this->add_stylesheet( "/public/font-awesome/css/font-awesome.min.css" );

        $this->add_meta( "viewport", "width=device-width, initial-scale=1" );

        $this->add_script( "/public/jquery/dist/jquery.min.js" );
        $this->add_script( "/public/bootstrap/dist/js/bootstrap.min.js" );
        $this->add_script( "/public/metisMenu/dist/metisMenu.min.js" );
        $this->add_script( "/public/js/sb-admin-2.js" );
    }

    public function view()
    {
        $layout             = new grid();
        $login_panel        = new bpanel("Please Sign In");
        $wrapper            = new div();    
        $submit             = new button("submit_button");
        $form               = new form("", form::POST, "/login/login");
        $email              = new binput("email");
        $password           = new binput("password", input::PASSWORD);
        $organisation       = $this->get_select();

        $this               ->set_layout($layout);   
        $layout             ->add_row(grid::FULL_WIDTH);

        $submit             ->set_class("btn", "btn-lg", "btn-primary", "btn-block");
        $form               ->set_class("form-signin");
        $organisation       ->set_class("form-control");
        $email              ->set_class("form-control");
        $password           ->set_class("form-control");
        $email              ->set_placeholder("Email Address");
        $password           ->set_placeholder("Password");
        $submit             ->set_text("Sign in");
        $wrapper            ->set_class("col-md-4", "col-md-offset-4");
        $login_panel        ->set_class("login-panel");

        $form               ->add($organisation, $email, $password, $submit);
        $wrapper            ->add($login_panel);   
        $login_panel        ->add($form);
        $this               ->add($wrapper);
    }

    public function get_select()
    {
        $select = new bselect("organisation");
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