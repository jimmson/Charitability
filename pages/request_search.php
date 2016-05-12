<?php

 use bones\base\page;
 use bones\containers\div;
 use bones\containers\form;
 use bones\containers\table;
 use bones\controls\option;
 use bones\controls\span;
 use bones\controls\button;
 use bones\controls\h;
 use bones\controls\img;
 //use controls\sa as a;

 use bootstrap\controls\binput;
 use bootstrap\containers\bselect;

class request_search extends dashboard
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
        parent::__construct( "Request Search" );
    }

    public function define()
    {
        parent::define();

        $this->filters          = new div();
        $this->title            = new div();
        $this->form             = new form("", form::POST);
        $this->results          = new table();

        $this->headding         = new h("");
        $this->search           = new button("search_button");
        $this->table_img        = new img();
        $this->table_link       = new sa();
        $this->table_req_type   = new span();
        $this->table_req_qty    = new span();
        $this->table_qty_type   = new span();


        $this->types            = new bselect("type");
        $types                  = new option();
        $types_default          = new option();

        $this->organisations    = new bselect("organisation");
        $organisation_default   = new option();
        $organisations          = new option();

        $types_default          ->set_value("0");
        $this->types            ->set_data(ssUtility::get_types("req_Type"));
        $types                  ->set_data_items("type_code","type_label");
        $types                  ->set_data_properties("set_value","set_text");

        $organisation_default   ->set_value("self");
        $organisations          ->set_data_items("organisation_id","organisation_name");
        $organisations          ->set_data_properties("set_value","set_text");
        $this->organisations     ->set_data( ssOrganisation::get_organisation_data() );

        $this->table_img        ->set_data_items("organisation_logo_path");
        $this->table_img        ->set_data_properties("set_src");

        $this->table_req_type   ->set_data_items("req_type_label");
        $this->table_req_type   ->set_data_properties("set_text");

        $this->table_qty_type   ->set_data_items("qty_type_label");
        $this->table_qty_type   ->set_data_properties("set_text");

        $this->table_req_qty    ->set_data_items("request_quantity");
        $this->table_req_qty    ->set_data_properties("set_text");

        $this->table_link       ->set_data_items("request_id", "request_reference");
        $this->table_link       ->set_data_properties("set_arg", "set_text");

        $this->filters          ->set_class("col-md-12", "form-horizontal");
        $this->results          ->set_class("col-md-12", "table", "table-striped"); 
        $this->table_img        ->set_class("table-image"); 
        $this->title            ->set_class("page-header");
        $this->search           ->set_class("btn", "btn-lg", "btn-primary");
        $this->types            ->set_class("form-control"); 
        $this->organisations    ->set_class("form-control"); 

        $this->table_img        ->set_label("Organisation");
        $this->table_req_type   ->set_label("Request type");
        $this->table_qty_type   ->set_label("Quantity type");
        $this->table_req_qty    ->set_label("Quantity");
        $this->table_link       ->set_label("Request");
        $this->organisations    ->set_label("Organisations:");
        $this->types            ->set_label("Type:");

        $this->table_link       ->set_page("request");
        $this->table_link       ->set_mode("view_request");

        $this->search           ->set_text("Search");
        $this->headding         ->set_text("Search for an request");
        $organisation_default   ->set_text("Self");
        $types_default          ->set_text("Select a Request type");

        $this->types            ->add( $types_default, $types );
        $this->organisations    ->add( $organisation_default, $organisations);
        $this->title            ->add( $this->headding, $this->search );
        $this->filters          ->add( $this->organisations, $this->types );
        $this->results          ->add( $this->table_img, $this->table_link, $this->table_req_type, $this->table_qty_type, $this->table_req_qty);
        $this->form             ->add( $this->title, $this->filters, $this->results );
        $this                   ->add( $this->form );
    }

    private function get_requests ( )
    {
        QUERY::QTABLE("sst_request");

        QUERY::QCOLUMNS(
            QUERY::QALIAS("qty_type.type_label", "qty_type_label"), 
            QUERY::QALIAS("req_type.type_label", "req_type_label"), 
            "request_id", 
            "request_reference", 
            "request_quantity", 
            "organisation_logo_path", 
            "organisation_name"
        );

        QUERY::QJOIN("ssm_organisation", QUERY::condition("sst_request.organisation_id", "=", "ssm_organisation.organisation_id"));
        QUERY::QJOIN("ssc_type",         QUERY::condition("sst_request.request_type",    "=", "req_type.type_code"), "req_type");
        QUERY::QJOIN("ssc_type",         QUERY::condition("sst_request.quantity_type",   "=", "qty_type.type_code"), "qty_type");
/*
        if ( isset($_POST["request_name"]) && $_POST["request_name"] != "")
        {
          QUERY::QWHERE(QUERY::condition("request_name", "=", QUERY::quote($_POST["request_name"])));            
        }
*/
        return DB::select(QUERY::QSELECT());

    }
    public function search_request()
    {
        $this->results->set_data( $this->get_requests() );
    }


}