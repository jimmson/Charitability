<?php

use bones\base\page;
use bones\containers\div;
use bones\containers\form;
use bones\containers\select;
use bones\containers\a;
use bones\containers\table;
use bones\controls\input;
use bones\containers\button;
use bones\controls\h;
use bones\controls\img;
use bones\controls\option;
use bones\controls\span;

use bootstrap\controls\binput;
use bootstrap\controls\btextarea;
use bootstrap\controls\bspan;
use bootstrap\controls\bp;
use bootstrap\containers\bselect;
use bootstrap\containers\bpanel;
use bootstrap\layouts\grid;

class request extends dashboard
{
    private $request;

    //containers
    private $form;
    private $info;

    //controls
    private $organisation;
    private $type;
    private $quantity;
    private $quantity_type;

    private $submit;
    private $heading;

    public function __construct()
    {
        parent::__construct( "Request" );
    }

    public function define()
    {
        parent::define();

        $this->request      = new ssRequest();
        $this->layout       = new grid();
        $this->heading      = new h("");
        $this->actions      = new div();
        $heading_wrapper    = new div();
        $column_heading     = new div();
        $column_action      = new div();

        $this->layout       ->add_row( grid::FULL_WIDTH );
        $this               ->set_layout( $this->layout ); 

        $heading_wrapper    ->set_class("row", "page-header");
        $column_heading     ->set_class("col-md-6");
        $column_action      ->set_class("col-md-6", "align-right", "lh-6");

        $column_action      ->add( $this->actions ); 
        $column_heading     ->add( $this->heading ); 
        $heading_wrapper    ->add( $column_heading, $column_action ); 
        $this               ->add( $heading_wrapper ); 
    }

    public function define_maintenance()
    {
        $this->layout->add_row(grid::FULL_WIDTH);
        
        $this->info             = new div();
        $this->form             = new form("", form::POST);
        $panel                  = new bpanel("Details");
        $this->submit           = new button("submit_button");
        $form_layout            = new grid();
        $this->types            = new bselect("type");
        $this->quantity         = new binput("quantity");
        $this->reference        = new bp("reference");
        $this->status           = new bp("status");
        $this->description      = new btextarea("description");
        $this->quantity_type    = new bselect("quantity_type");
        $types                  = new option();
        $quantity_types         = new option();
        $quantity_types_default = new option();
        $types_default          = new option();
        $this->entity_details   = new bentity_details();
        $form_left              = new div();
        $form_right             = new div();


        $form_layout            ->add_row(grid::HALF_WIDTH, grid::HALF_WIDTH);
        $form_layout            ->add_row(grid::FULL_WIDTH);
        $this->info             ->set_layout( $form_layout ); 

        $types_default          ->set_value("0");
        $types_default          ->set_text("Select a Request type");
        $this->types            ->add( $types_default );
        $this->types            ->set_data(ssUtility::get_types("req_Type"));
        $types                  ->set_data_items("type_code","type_label");
        $types                  ->set_data_properties("set_value","set_text");

        $quantity_types_default ->set_value("0");
        $quantity_types_default ->set_text("Select a Quantity type");
        $this->quantity_type    ->add( $quantity_types_default );
        $this->quantity_type    ->set_data(ssUtility::get_types("qty_Type"));
        $quantity_types         ->set_data_items("type_code","type_label");
        $quantity_types         ->set_data_properties("set_value","set_text");

        $this->types            ->set_label("Type:");
        $this->quantity         ->set_label("Quantity:");
        $this->quantity_type    ->set_label("Quantity Type:");
        $this->status           ->set_label("Status:");
        $this->reference        ->set_label("Reference:");
        $this->entity_details   ->set_label("Organisation:");
        $this->description      ->set_label("Description:");

        $this->info             ->set_class("col-md-12", "form-horizontal");
        $this->types            ->set_class("form-control"); 
        $this->quantity         ->set_class("form-control");
        $this->quantity_type    ->set_class("form-control");
        $this->description      ->set_class("form-control");
        $this->submit           ->set_class("btn", "btn-primary");

        $this->submit           ->set_text("Submit");

        $this->types            ->add( $types );
        $this->quantity_type    ->add( $quantity_types );
        $form_left              ->add( $this->entity_details, $this->reference,  $this->status);
        $form_right             ->add( $this->types, $this->quantity_type, $this->quantity);
        $this->info             ->add( $form_left, $form_right, $this->description );
        $panel                  ->add( $this->info );
        $panel->footer          ->add( $this->submit );
        $this->form             ->add( $panel );
        $this                   ->add( $this->form );
    }

    public function define_view()
    {
        $this->layout->add_row(grid::FULL_WIDTH);
        
        $this->info             = new div();
        $this->form             = new form("", form::POST);
        $panel                  = new bpanel("Details");
        $form_layout            = new grid();
        $this->add_response     = new a("add_response");
        $this->types            = new bp("type");
        $this->quantity         = new bp("quantity");
        $this->reference        = new bp("reference");
        $this->status           = new bp("status");
        $this->description      = new bp("description");
        $this->quantity_type    = new bp("quantity_type");
        $this->entity_details   = new bentity_details();
        $form_left              = new div();
        $form_right             = new div();

        $form_layout            ->add_row(grid::HALF_WIDTH, grid::HALF_WIDTH);
        $form_layout            ->add_row(grid::FULL_WIDTH);
        $this->info             ->set_layout( $form_layout ); 

        $this->types            ->set_label("Type:");
        $this->quantity         ->set_label("Quantity:");
        $this->quantity_type    ->set_label("Quantity Type:");
        $this->status           ->set_label("Status:");
        $this->reference        ->set_label("Reference:");
        $this->entity_details   ->set_label("Organisation:");
        $this->description      ->set_label("Description:");

        $this->info             ->set_class("col-md-12", "form-horizontal");
        $this->add_response     ->set_class("btn", "btn-primary");

        $this->add_response     ->set_text("Add Response");

        $form_left        ->add( $this->entity_details, $this->reference,  $this->status);
        $form_right       ->add( $this->types, $this->quantity_type, $this->quantity);
        $this->info       ->add( $form_left, $form_right, $this->description );
        $panel            ->add( $this->info );
        $panel->footer    ->add( $this->add_response );
        $this->form       ->add( $panel );
        $this             ->add( $this->form );
    }

    public function add_request()
    {
        $this                   ->define_maintenance();
        $this->heading          ->set_text("Add request");
        $this->reference        ->set_text("Auto generated code");
        $this->status           ->set_text("New");
        $this->entity_details   ->set_src(ssSession::$organisation->logo->get_location());  
        $this->entity_details   ->set_text(ssSession::$organisation->get_name());  
        $this->form             ->set_action("/request/save_request"); 
    }

    public function change_request( $_args = null )
    {
        $this->request  ->focus( $_args[0] );
        $this->form     ->set_action("/request/update_request/" . $this->request->get_id());
        $this->heading  ->set_text("Update request");
    }

    public function view_request( $_args = null )
    {
        $this->request          ->focus( $_args[0] );
        $this                   ->define_view();
        $this->info             ->set_control_properties("set_readonly", true);
        $this->form             ->set_action("/request/change_request/" . $this->request->get_id());
        $this->add_response     ->set_href("/response/add_response/" . $this->request->get_id());
        $this->heading          ->set_text("View request");
        $this->description      ->set_text($this->request->get_description());
        $this->types            ->set_text($this->request->type->get_label());
        $this->quantity_type    ->set_text($this->request->quantity_type->get_label());
        $this->status           ->set_text($this->request->status->get_label());
        $this->reference        ->set_text($this->request->get_reference());
        $this->quantity         ->set_text($this->request->get_quantity());
        $this->entity_details   ->set_text($this->request->organisation->get_name());  
        $this->entity_details   ->set_src($this->request->organisation->logo->get_location());  

        $this->form             ->add($this->get_responses());
    }

    public function update_request($_args = null)
    {
        $this->request->focus( $_args[0] );
        $this->save_request();
    }

    public function save_request()
    {
        $this->request->type->set_code          ( $_POST["type"] );
        $this->request->set_description         ( $_POST["description"] );
        $this->request->set_quantity            ( $_POST["quantity"] );
        $this->request->set_organisation_id     ( ssSession::$organisation->get_id() );
        $this->request->quantity_type->set_code ( $_POST["quantity_type"] );
        $this->request->save();
    }

    private function get_responses()
    {
        $panel            = new bpanel("Responses");
        $results          = new div();
        $results_table    = new table();
        $table_img        = new img();
        $table_link       = new sa();
        $table_link       = new sa();
        $table_res_qty    = new span();

        $results_table    ->set_data( $this->get_response_data() ); 

        $table_img        ->set_data_items("file_location");
        $table_img        ->set_data_properties("set_src");

        $table_res_qty    ->set_data_items("response_quantity");
        $table_res_qty    ->set_data_properties("set_text");

        $table_link       ->set_data_items("response_id", "response_reference");
        $table_link       ->set_data_properties("set_arg", "set_text");
        $table_link       ->set_page("response");
        $table_link       ->set_mode("view_response");

        $results          ->set_class("col-md-12"); 
        $results_table    ->set_class("table", "table-striped"); 
        $table_img        ->set_class("table-image"); 

        $table_img        ->set_label("Organisation");
        $table_res_qty    ->set_label("Quantity");
        $table_link       ->set_label("Response");

        $results_table    ->add( $table_img, $table_link, $table_res_qty );
        $results          ->add( $results_table );
        $panel            ->add( $results );

        return $panel;
    }

     private function get_response_data( )
    {
        QUERY::QTABLE("sst_response");

        QUERY::QCOLUMNS(
            "response_id", 
            "response_reference", 
            "response_quantity",
            "organisation_name",
            "file_location"
        );

        QUERY::QJOIN("ssm_organisation", QUERY::condition("sst_response.organisation_id",  "=", "ssm_organisation.organisation_id"));
        QUERY::QJOIN("ssm_file",         QUERY::condition("ssm_organisation.logo_file_id", "=", "ssm_file.file_id"));

        QUERY::QWHERE(QUERY::condition("request_id", "=", $this->request->get_id())); 

        return DB::select(QUERY::QSELECT());

    }

}