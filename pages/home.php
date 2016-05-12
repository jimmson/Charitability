<?php

 use bones\base\page;
 use bones\containers\div;
 use bones\containers\form;
 use bones\controls\input;
 use bones\controls\button;
 use bones\controls\h;

class home extends dashboard
{
	//containers
	private $form;

	//controls
	private $email;
	private $password;
	private $submit;
	private $headding;

	public function __construct()
	{
		parent::__construct( "Home" );
    }

    public function define()
    {
        parent::define();
        
    	$this->headding = new h("");
    	$this->submit 	= new button("submit_button");
    	$this->form 	= new form("login_form", "/login/login", form::POST );
    	$this->email 	= new input("email");
    	$this->password = new input("password", input::PASSWORD);

    	$this->form->add( $this->headding, $this->email, $this->password, $this->submit );
    }

    public function view()
    {

    }
}