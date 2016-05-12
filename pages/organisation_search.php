<?php

 use bones\base\page;
 use bones\containers\div;
 use bones\containers\form;
 use bones\containers\table;
 use bones\controls\input;
 use bones\controls\button;
 use bones\controls\h;
 use bones\controls\img;
 //use controls\sa as a;

 use bootstrap\controls\binput;

class organisation_search extends dashboard
{
    //containers
    private $form;
    private $filters;
    private $title;
    private $results;

    //controls
    private $name;
    private $table_name;
    private $search;
    private $headding;

    public function __construct()
    {
        parent::__construct( "Organisation Search" );
    }

    public function define()
    {
        parent::define();

        $this->filters      = new div();
        $this->title        = new div();
        $this->form         = new form("", form::POST);
        $this->results      = new table();

        $this->headding     = new h("");
        $this->search       = new button("search_button");
        $this->name         = new binput("organisation_name");
        $this->table_img    = new img();
        $this->table_link   = new sa();

        $this->table_img    ->set_data_items("organisation_logo_path");
        $this->table_img    ->set_data_properties("set_src");
        $this->table_link   ->set_data_items("organisation_id","organisation_name");
        $this->table_link   ->set_data_properties("set_arg","set_text");

        $this->filters      ->set_class("col-md-12", "form-horizontal");
        $this->results      ->set_class("col-md-12", "table", "table-striped"); 
        $this->table_img    ->set_class("table-image"); 

        $this->name         ->set_label("Name:");
        $this->table_img    ->set_label("Logo:");
        $this->table_link   ->set_label("Organisation:");

        $this->table_link   ->set_page("organisation");
        $this->table_link   ->set_mode("view_organisation");

        $this->title        ->set_class("page-header");
        $this->name         ->set_class("form-control");
        $this->search       ->set_class("btn", "btn-lg", "btn-primary");

        $this->search       ->set_text("Search");
        $this->headding     ->set_text("Search for an Organisation");

        $this->title        ->add( $this->headding, $this->search );
        $this->filters      ->add( $this->name );
        $this->results      ->add( $this->table_img, $this->table_link);
        $this->form         ->add( $this->title, $this->filters, $this->results );
        $this               ->add( $this->form );
    }

    private function get_organisations ( )
    {
        QUERY::QTABLE("ssm_organisation");
        QUERY::QCOLUMNS("organisation_id", "organisation_logo_path", "organisation_name");

        if ( isset($_POST["organisation_name"]) && $_POST["organisation_name"] != "")
        {
          QUERY::QWHERE(QUERY::condition("organisation_name", "=", QUERY::quote($_POST["organisation_name"])));            
        }


        return DB::select(QUERY::QSELECT());
    }

    public function search_organisation()
    {
        $this->results->set_data( $this->get_organisations() );
    }

}