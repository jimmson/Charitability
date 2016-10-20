<?php

use bones\base\page;
use bones\containers\div;
use bones\containers\button;
use bones\containers\form;
use bones\containers\a;
use bones\controls\input;
use bones\controls\h;
use bones\controls\img;
use bones\controls\script;

use bootstrap\containers\btabs;
use bootstrap\containers\bpanel;
use bootstrap\containers\btab;
use bootstrap\controls\binput;
use bootstrap\controls\bp;
use bootstrap\layouts\grid;

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
    private $heading;

    public function __construct()
    {
        parent::__construct( "Organisation" );
    }

    public function define()
    {
        parent::define();

        $this->organisation = new ssOrganisation();
        $this->layout       = new grid();
        $this->heading      = new h("");
        $this->actions      = new div();
        $heading_wrapper    = new div();
        $column_heading     = new div();
        $column_action      = new div();

        $this->layout   ->add_row( grid::FULL_WIDTH );

        $heading_wrapper    ->set_class("row", "page-header");
        $column_heading     ->set_class("col-md-6");
        $column_action      ->set_class("col-md-6", "align-right", "lh-6");

        $this               ->set_layout( $this->layout ); 

        $column_action      ->add( $this->actions ); 
        $column_heading     ->add( $this->heading ); 
        $heading_wrapper    ->add( $column_heading, $column_action ); 
        $this               ->add( $heading_wrapper ); 
    }

    public function define_maintenance()
    {
        $this->layout->add_row(grid::FULL_WIDTH);
        
        $this->info             = new div();
        $this->address          = new div(); 
        $this->contact          = new div(); 
        $this->form             = new form("", form::POST);
        $panel                  = new bpanel("Details");
        $tabs                   = new btabs();
        $tab_organisation       = new btab("Organisation");
        $tab_address            = new btab("Address");
        $tab_contact            = new btab("Contact");
        $this->submit           = new button("submit_button");
        $this->name             = new binput("name");
        $this->website          = new binput("website");
        $this->file             = new binput("file[]", input::FILE);
        $this->logo             = new script();
        $this->address          = ssUtility::get_address_container($this->address);
        $this->contact          = ssUtility::get_contact_container($this->contact);

        $this->file     ->set_id("organisation-logo");

        $this->name     ->set_label("Name:");
        $this->website  ->set_label("Website:");
        $this->file     ->set_label("Upload:");

        $this->website  ->set_class("form-control"); 
        $this->name     ->set_class("form-control");
        $this->submit   ->set_class("btn", "btn-primary");
        $this->file     ->set_class("file-loading");

        $this->submit   ->set_text("Submit");

        $this->info       ->add( $this->name, $this->website, $this->logo, $this->file );
        $tabs             ->add( $tab_organisation, $tab_address, $tab_contact );
        $tab_organisation ->add( $this->info );
        $tab_contact      ->add( $this->contact );
        $tab_address      ->add( $this->address );
        $panel            ->add( $tabs );
        $panel->footer    ->add( $this->submit );
        $this->form       ->add( $panel );
        $this             ->add( $this->form );
    }

    public function define_view()
    {
        $this->layout->add_row(grid::HALF_WIDTH, grid::HALF_WIDTH);
        $this->layout->add_row(grid::HALF_WIDTH, grid::HALF_WIDTH);

        $this->info             = new div();
        $this->contact          = new div();
        $this->address          = new div();
        $this->address          = ssUtility::get_address_container($this->address, true);
        $this->contact          = ssUtility::get_contact_container($this->contact, true);
        $this->website          = new bp("website");
        $this->logo             = new img();
        $this->button_change    = new sa();
        $panel_about            = new bpanel("About");
        $panel_contact          = new bpanel("Contact Details");
        $panel_address          = new bpanel("Address");

        $this->button_change    ->set_page("organisation");
        $this->button_change    ->set_mode("change_organisation");
        $this->button_change    ->set_text("Change");

        $this->website          ->set_label("Website:");

        $this->website          ->set_class("form-control-static");
        $this->logo             ->set_class("bg-organisation-logo");
        $this->button_change    ->set_class("btn", "btn-primary");
        
        $this->actions          ->add( $this->button_change );
        $this->info             ->add( $this->website );
        $panel_about            ->add( $this->info );
        $panel_address          ->add( $this->address );
        $panel_contact          ->add( $this->contact );

        $this                   ->add( $this->logo , $panel_about, $panel_contact, $panel_address );
    }


    public function add_organisation()
    {
        $this          ->define_maintenance();
        $this->heading ->set_text("Add Organisation");
        $this->form    ->set_action("/organisation/save_organisation"); 
        $this->logo    ->set_text("var organisationLogo = '/public/uploads/placeholder_logo.jpg';");
    }

    public function change_organisation( $_args = null )
    {
        $this->define_maintenance();

        $this->organisation->focus( $_args[0] );

        $this->form     ->set_action("/organisation/update_organisation/" . $this->organisation->get_id());
        $this->heading  ->set_text("Update Organisation");
        $this->website  ->set_value($this->organisation->get_website());
        $this->name     ->set_value($this->organisation->get_name());
        $this->logo     ->set_text("var organisationLogo = '" . $this->organisation->logo->get_location() . "';");

        $this->address->populate_controls($this->organisation->address->get_data_array());
        $this->contact->populate_controls($this->organisation->contact->get_data_array());

    }

    public function view_organisation( $_args = null )
    {
        $this          ->define_view();

        $this->organisation->focus( $_args[0] );

//        echo $this->organisation->logo_file->get_location();

        $this->website          ->set_text($this->organisation->get_website());
        $this->heading          ->set_text($this->organisation->get_name());
        $this->logo             ->set_src($this->organisation->logo->get_location());
        $this->button_change    ->set_arg($this->organisation->get_id());

        $this->address->populate_controls($this->organisation->address->get_data_array());
        $this->contact->populate_controls($this->organisation->contact->get_data_array());

    }

    public function update_organisation($_args = null)
    {
        $this->organisation->focus( $_args[0] );
        $this->save_organisation();
    }

    public function save_organisation()
    {
        //$logo_path = ssUtility::upload_file();

        //if ($logo_path ) $this->organisation->set_logo( $logo_path );

        if ( $_FILES["file"]["name"][0] )
            $this->organisation->logo->store_uploaded_file($_FILES["file"]["name"][0], $_FILES['file']['tmp_name'][0]);

        $this->organisation->set_name               ( $_POST["name"]    );
        $this->organisation->set_website            ( $_POST["website"] );

        $this->organisation->address->set_number    ( $_POST["number"]  );
        $this->organisation->address->set_street    ( $_POST["street"]  );
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