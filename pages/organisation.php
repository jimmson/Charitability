<?php

 use bones\base\page;
 use bones\containers\div;
 use bones\containers\form;
 use bones\controls\input;
 use bones\controls\button;
 use bones\controls\h;
 use bones\controls\img;

 use bootstrap\controls\binput;

class organisation extends dashboard
{
    private $organisation;

    //containers
    private $form;
    private $info;
    private $logo;
    private $address;
    private $contact;

    //controls
    private $name;
    private $image;
    private $website;
    private $file;
    private $submit;
    private $headding;

    public function __construct()
    {
        parent::__construct( "Organisation" );
    }

    public function define()
    {
        parent::define();

        $this->organisation = new ssOrganisation();

        $this->info     = new div();
        $this->logo     = new div();
        $this->title    = new div();
        $this->form     = new form("", form::POST);

        $this->headding = new h("");
        $this->image    = new img();
        $this->submit   = new button("submit_button");
        $this->name     = new binput("name");
        $this->website  = new binput("website");
        $this->file     = new binput("file", input::FILE);

        $this->address  = ssUtility::get_address_container();
        $this->contact  = ssUtility::get_contact_container();

        $this->name     ->set_label("Name:");
        $this->website  ->set_label("Website:");
        $this->file     ->set_label("Upload:");
        $this->image    ->set_src("http://car-insurance-quotes.co.za/wp-content/uploads/sites/2/2014/06/Woolworths-Insurance.jpg");

        $this->info     ->set_class("col-md-8", "form-horizontal");
        $this->logo     ->set_class("col-md-4");
        $this->title    ->set_class("page-header");
        $this->website  ->set_class("form-control"); 
        $this->name     ->set_class("form-control");
        $this->submit   ->set_class("btn", "btn-lg", "btn-primary");
        $this->image    ->set_class("img-rounded");

        $this->submit   ->set_text("Submit");

        $this->title    ->add( $this->headding, $this->submit );
        $this->info     ->add( $this->name, $this->website, $this->file );
        $this->logo     ->add( $this->image );
        $this->form     ->add( $this->title, $this->info, $this->logo, $this->address,  $this->contact);
        $this           ->add( $this->form );
    }

    public function add_organisation()
    {
        $this->headding ->set_text("Add Organisation");
        $this->form     ->set_action("/organisation/save_organisation"); 
    }

    public function change_organisation( $_args = null )
    {
        $data = $this->get_organisation_data( $_args[0] );

        $this->form     ->set_action("/organisation/update_organisation/" . $this->organisation->get_id());
        $this->headding ->set_text("Update Organisation");
        $this->image    ->set_src($this->organisation->get_logo());
    }

    public function view_organisation( $_args = null )
    {
        $data = $this->get_organisation_data( $_args[0] );

        $this->contact  ->set_control_properties("set_readonly", true);
        $this->address  ->set_control_properties("set_readonly", true);
        $this->info     ->set_control_properties("set_readonly", true);

        $this->form     ->set_action("/organisation/change_organisation/" . $this->organisation->get_id());
        $this->headding ->set_text("View Organisation");
        $this->submit   ->set_text("Change");

        $this->image    ->set_src($this->organisation->get_logo());
    }

    private function get_organisation_data( $_organisation_id )
    {
        $this->organisation->focus( $_organisation_id );

        $this->contact->set_control_values(  $this->organisation->contact->get_data_array() );
        $this->address->set_control_values(  $this->organisation->address->get_data_array() );
        $this->info   ->set_control_values(  $this->organisation->get_data_array()          );
    }

    public function update_organisation($_args = null)
    {
        $this->organisation->focus( $_args[0] );
        $this->save_organisation();
    }

    public function save_organisation()
    {
        $logo_path = ssUtility::upload_file();

        if ($logo_path ) $this->organisation->set_logo( $logo_path );

        $this->organisation->set_name               ( $_POST["name"]    );
        $this->organisation->set_website            ( $_POST["website"] );

        $this->organisation->address->set_line1     ( $_POST["line1"]   );
        $this->organisation->address->set_line2     ( $_POST["line2"]   );
        $this->organisation->address->set_country   ( $_POST["country"] );
        $this->organisation->address->set_state     ( $_POST["state"]   );
        $this->organisation->address->set_city      ( $_POST["city"]    );
        $this->organisation->address->set_zip       ( $_POST["zip"]     );

        $this->organisation->contact->set_phone     ( $_POST["phone"]   );
        $this->organisation->contact->set_fax       ( $_POST["fax"]     );
        $this->organisation->contact->set_email     ( $_POST["email"]   );

        $this->organisation->save();

    }

}