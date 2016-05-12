<?php

 use bones\base\page;
 use bones\containers\div;
 use bones\containers\form;
 use bones\containers\select;
 use bones\controls\input;
 use bones\controls\button;
 use bones\controls\h;
 use bones\controls\img;
 use bones\controls\option;
 use bones\controls\span;

 use bootstrap\controls\binput;
 use bootstrap\controls\btextarea;
 use bootstrap\controls\bspan;
 use bootstrap\containers\bselect;

class response extends dashboard
{
    private $response;

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
        parent::__construct( "Response" );
    }

    public function define()
    {
        parent::define();

        $this->response          = new ssresponse();

        $this->info             = new div();
        $this->request_info     = new div();
        $this->title            = new div();
        $this->form             = new form("", form::POST);
        $this->page_heading     = new h("");
        $this->heading          = new h("", h::IMPORTANCE_2);
        $this->submit           = new button("submit_button");
        $this->quantity         = new binput("quantity");
        $this->request_id       = new input("request_id");
        $this->reference        = new binput("reference");
        $this->description      = new btextarea("description");
        $this->entity_details   = new bentity_details();

        $this->request_id       ->set_hidden(true);

        $this->entity_details   ->set_label("Organisation:");
        $this->quantity         ->set_label("Quantity:");
        $this->reference        ->set_label("Reference:");
        $this->description      ->set_label("Description:");

        $this->info             ->set_class("col-md-6", "form-horizontal");
        $this->request_info     ->set_class("col-md-6", "form-horizontal");        
        $this->title            ->set_class("page-header");
        $this->quantity         ->set_class("form-control");
        $this->reference        ->set_class("form-control");
        $this->description      ->set_class("form-control");
        $this->submit           ->set_class("btn", "btn-lg", "btn-primary");
        $this->heading          ->set_class("heading");


        $this->submit           ->set_text("Submit");
        $this->heading          ->set_text("Response");

        $this->title            ->add( $this->page_heading, $this->submit );
        $this->info             ->add( $this->heading, $this->entity_details, $this->request_id, 
                                       $this->reference, $this->quantity,  $this->description );
        $this->form             ->add( $this->title, $this->info, $this->request_info);
        $this                   ->add( $this->form );
    }

    public function add_response( $_args = null )
    {

        $this->response->request->focus( $_args[0] );
        $this->request_info->add( $this->get_request_container() );

        $this->request_id       ->set_value($this->response->request->get_id());
        $this->entity_details   ->set_src(ssSession::$organisation->get_logo());  
        $this->entity_details   ->set_text(ssSession::$organisation->get_name()); 
        $this->page_heading     ->set_text("Add response");
        $this->form             ->set_action("/response/save_response"); 
    }

    public function change_response( $_args = null )
    {
        $data = $this->get_response_data( $_args[0] );

        $this->form         ->set_action("/response/update_response/" . $this->response->get_id());
        $this->page_heading ->set_text("Update response");
    }

    public function view_response( $_args = null )
    {
        $data = $this   ->get_response_data( $_args[0] );

       // $this->request = new ssRequest();
       // $this->request->focus( $this->response->get_request_id() );
        $this->request_info->add( $this->get_request_container() );

        $this->info     ->set_control_properties("set_readonly", true);

        $this->entity_details   ->set_src($this->response->organisation->get_logo());  
        $this->entity_details   ->set_text($this->response->organisation->get_name()); 
        $this->form             ->set_action("/response/change_response/" . $this->response->get_id());
        $this->page_heading     ->set_text("View response");
        $this->submit           ->set_text("Change");
    }

    private function get_response_data( $_response_id )
    {
        $this->response->focus( $_response_id );

        $data = $this->response->get_data_array();

        $this->info ->set_control_values( $data );

        $this->description->set_text( $data["description"]);
    }

    public function update_response($_args = null)
    {
        $this->response->focus( $_args[0] );
        $this->save_response();
    }

    public function save_response()
    {
        $this->response->set_organisation_id  ( ssSession::$organisation->get_id() );
        $this->response->set_user_id          ( ssSession::$user->get_id() );
        $this->response->set_request_id       ( $_POST["request_id"] );
        $this->response->set_description      ( $_POST["description"] );
        $this->response->set_quantity         ( $_POST["quantity"] );

        $this->response->save();
    }

    public function get_request_container()
    {

        $info             = new div();
        $heading          = new h("", h::IMPORTANCE_2);
        $types            = new bspan();
        $quantity         = new bspan();
        $reference        = new bspan();
        $description      = new btextarea("");
        $status           = new bspan();
        $quantity_type    = new bspan();
        $entity_details   = new bentity_details();

        $entity_details   ->set_src($this->response->request->organisation->get_logo());  
        $entity_details   ->set_text($this->response->request->organisation->get_name());  

        $entity_details   ->set_label("Organisation");
        $types            ->set_label("Type");
        $quantity         ->set_label("Quantity");
        $quantity_type    ->set_label("Quantity Type");
        $status           ->set_label("Status");
        $reference        ->set_label("Reference");
        $description      ->set_label("Description");

        $heading          ->set_text("Origional request");
        $types            ->set_text( $this->response->request->type->get_label() );
        $quantity         ->set_text( $this->response->request->get_quantity() );
        $quantity_type    ->set_text( $this->response->request->quantity_type->get_label() );
        $status           ->set_text( $this->response->request->status->get_label() );
        $reference        ->set_text( $this->response->request->get_reference() );
        $description      ->set_text( $this->response->request->get_description() );

        $info             ->set_class("col-md-12", "form-horizontal");
        $types            ->set_class("form-control"); 
        $quantity         ->set_class("form-control");
        $quantity_type    ->set_class("form-control");
        $status           ->set_class("form-control");
        $reference        ->set_class("form-control");
        $description      ->set_class("form-control");
        $heading          ->set_class("heading");


        $info             ->add( $heading, $entity_details, $reference,  $status, $types, $quantity_type, $quantity,  $description );

        return $info;
    }

    

}