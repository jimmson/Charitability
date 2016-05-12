<?php

 use bones\base\page;
 use bones\containers\div;
 use bones\containers\form;
 use bones\controls\input;
 use bones\controls\button;
 use bones\controls\h;

class register extends page
{
	//containers
	private $form;

	//controls
    private $name;
    private $surname;
	private $email;
    private $password;
	private $confirm;
	private $submit;
    private $headding;

	public function __construct()
	{
		parent::__construct( "Register" );
    }

    public function define()
    {     
        $this->requires_session = false;

        $this->add_stylesheet( "http://getbootstrap.com/dist/css/bootstrap.min.css" );
        $this->add_stylesheet( "https://getbootstrap.com/examples/signin/signin.css" );
        $this->add_meta( "viewport", "width=device-width, initial-scale=1" );

        $this->headding    = new h();
    	$this->submit 	   = new button("submit_button");
    	$this->form 	   = new form("", form::POST, "/register/register");
        $this->name        = new input("name");
        $this->surname     = new input("surname");
    	$this->email 	   = new input("email");
        $this->password    = new input("password", input::PASSWORD);
    	$this->confirm     = new input("confirm", input::PASSWORD);


    	$this->form->add( $this->headding, $this->name, $this->surname, $this->email, $this->password, $this->confirm, $this->submit );
    }

    public function view()
    {
    	$this->submit 	    ->set_class("btn", "btn-lg", "btn-primary", "btn-block");
    	$this->form 	    ->set_class("form-signin");
    	$this->name 	    ->set_class("form-control");
        $this->surname      ->set_class("form-control");
        $this->email        ->set_class("form-control");
        $this->password     ->set_class("form-control");
        $this->confirm      ->set_class("form-control");

        $this->name         ->set_placeholder("Name");
        $this->surname      ->set_placeholder("Surname");
		$this->email 	    ->set_placeholder("Email Address");
        $this->password     ->set_placeholder("Password");
    	$this->confirm      ->set_placeholder("Confirm Password");

        $this->headding     ->set_text("New User:");
    	$this->submit 	    ->set_text("Register");
    	
    	$this->add ($this->form );
    }

    public function register()
    {

 
    }


}