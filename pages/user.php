<?php

 use bones\base\page;
 use bones\containers\div;
 use bones\containers\form;
 use bones\controls\input;
 use bones\controls\button;
 use bones\controls\h;
 use bones\controls\img;

 use bootstrap\controls\binput;

class user extends dashboard
{
    private $user;

    //containers
    private $form;
    private $info;
    private $logo;
    private $address;
    private $contact;

    //controls
    private $name;
    private $image;
    private $email;
    private $file;
    private $submit;
    private $headding;

    public function __construct()
    {
        parent::__construct( "Welcome" );
    }

    public function define()
    {
        parent::define();

        $this->user = new ssUser();

        $this->info     = new div();
        $this->logo     = new div();
        $this->title    = new div();
        $this->form     = new form("", form::POST);

        $this->headding = new h("");
        $this->image    = new img();
        $this->submit   = new button("submit_button");
        $this->name     = new binput("name");
        $this->surname  = new binput("surname");
        $this->file     = new binput("file", input::FILE);

        $this->address  = ssUtility::get_address_container();
        $this->contact  = ssUtility::get_contact_container();

        $this->name     ->set_label("Name:");
        $this->surname  ->set_label("surname:");
        $this->file     ->set_label("Upload:");
        $this->image    ->set_src("http://car-insurance-quotes.co.za/wp-content/uploads/sites/2/2014/06/Woolworths-Insurance.jpg");

        $this->info     ->set_class("col-md-8", "form-horizontal");
        $this->logo     ->set_class("col-md-4");
        $this->title    ->set_class("page-header");
        $this->surname  ->set_class("form-control"); 
        $this->name     ->set_class("form-control");
        $this->submit   ->set_class("btn", "btn-lg", "btn-primary");
        $this->image    ->set_class("img-rounded");

        $this->submit   ->set_text("Submit");

        $this->title    ->add( $this->headding, $this->submit );
        $this->info     ->add( $this->name, $this->surname, $this->file );
        $this->logo     ->add( $this->image );
        $this->form     ->add( $this->title, $this->info, $this->logo, $this->address,  $this->contact);
        $this           ->add( $this->form );
    }

    public function add_user()
    {
        $this->headding ->set_text("Add user");
        $this->form     ->set_action("/user/save_user"); 
    }

    public function change_user( $_args = null )
    {
        $data = $this->get_user_data( $_args[0] );

        $this->form     ->set_action("/user/update_user/" . $this->user->get_id());
        $this->headding ->set_text("Update user");
        $this->image    ->set_src($this->user->get_picture());
    }

    public function view_user( $_args = null )
    {
        $data = $this->get_user_data( $_args[0] );

        $this->contact  ->set_control_properties("set_readonly", true);
        $this->address  ->set_control_properties("set_readonly", true);
        $this->info     ->set_control_properties("set_readonly", true);

        $this->form     ->set_action("/user/change_user/" . $this->user->get_id());
        $this->headding ->set_text("View user");
        $this->submit   ->set_text("Change");

        $this->image    ->set_src($this->user->get_picture());
    }

    private function get_user_data( $_user_id )
    {
        $this->user->focus( $_user_id );

        $this->contact->set_control_values(  $this->user->contact->get_data_array() );
        $this->address->set_control_values(  $this->user->address->get_data_array() );
        $this->info   ->set_control_values(  $this->user->get_data_array()          );
    }

    public function update_user($_args = null)
    {
        $this->user->focus( $_args[0] );
        $this->save_user();
    }

    public function save_user()
    {
        $picture_path = ssUtility::upload_file();

        if ( $picture_path ) $this->user->set_picture( $picture_path );

        $this->user->set_name               ( $_POST["name"]    );
        $this->user->set_surname            ( $_POST["surname"] );

        $this->user->address->set_line1     ( $_POST["line1"]   );
        $this->user->address->set_line2     ( $_POST["line2"]   );
        $this->user->address->set_country   ( $_POST["country"] );
        $this->user->address->set_state     ( $_POST["state"]   );
        $this->user->address->set_city      ( $_POST["city"]    );
        $this->user->address->set_zip       ( $_POST["zip"]     );

        $this->user->contact->set_phone     ( $_POST["phone"]   );
        $this->user->contact->set_fax       ( $_POST["fax"]     );
        $this->user->contact->set_email     ( $_POST["email"]   );

        $this->user->save();

    }

}