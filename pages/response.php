<?php

use bones\base\page;
use bones\containers\div;
use bones\containers\form;
use bones\containers\button;
use bones\containers\select;
use bones\controls\input;
use bones\controls\h;
use bones\controls\img;
use bones\controls\option;
use bones\controls\span;

use bootstrap\controls\binput;
use bootstrap\controls\bp;
use bootstrap\controls\btextarea;
use bootstrap\controls\bspan;
use bootstrap\containers\bselect;
use bootstrap\containers\bpanel;
use bootstrap\layouts\grid;

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
        parent::__construct("Response");
    }

    public function define()
    {
        parent::define();

        $this->response     = new ssresponse();
        $this->layout       = new grid();
        $this->heading      = new h("");
        $this->actions      = new div();
        $heading_wrapper    = new div();
        $column_heading     = new div();
        $column_action      = new div();

        $this->layout       ->add_row(grid::FULL_WIDTH);
        $this               ->set_layout($this->layout);

        $heading_wrapper    ->set_class("row", "page-header");
        $column_heading     ->set_class("col-md-6");
        $column_action      ->set_class("col-md-6", "align-right", "lh-6");

        $column_action      ->add($this->actions);
        $column_heading     ->add($this->heading);
        $heading_wrapper    ->add($column_heading, $column_action);
        $this               ->add($heading_wrapper);
    }

    public function define_maintenance()
    {
        $panel                  = new bpanel("Response");
        $this->info             = new div();
        $this->request_info     = new div();
        $this->response_info    = new div();
        $this->form             = new form("", form::POST);
        $this->submit           = new button("submit_button");
        $this->quantity         = new binput("quantity");
        $this->request_id       = new input("request_id");
        $this->reference        = new binput("reference");
        $this->description      = new btextarea("description");
        $this->entity_details   = new bentity_details();
        $form_layout            = new grid();

        $form_layout            ->add_row(grid::HALF_WIDTH, grid::HALF_WIDTH);
        $panel->body            ->set_layout($form_layout);

        $this->request_id       ->set_hidden(true);

        $this->entity_details   ->set_label("Organisation:");
        $this->quantity         ->set_label("Quantity:");
        $this->reference        ->set_label("Reference:");
        $this->description      ->set_label("Description:");

        $this->quantity         ->set_class("form-control");
        $this->reference        ->set_class("form-control");
        $this->description      ->set_class("form-control");
        $this->submit           ->set_class("btn", "btn-primary");

        $this->submit           ->set_text("Submit");

        $this->layout           ->add_row(grid::FULL_WIDTH);
        $this->layout           ->add_row(grid::FULL_WIDTH);
        $this                   ->set_layout($this->layout);

        $this->info             ->add(
            $this->entity_details,
            $this->request_id,
            $this->reference,
            $this->quantity
        );
        $panel                  ->add($this->info, $this->description);
        $panel->footer          ->add($this->submit);
        $this->response_info    ->add($panel);
        $this->form             ->add($this->response_info);
        $this                   ->add($this->form, $this->request_info);
    }

    public function define_view()
    {
        $panel                  = new bpanel("Response");
        $this->info             = new div();
        $this->request_info     = new div();
        $this->response_info    = new div();
        $this->form             = new form("", form::POST);
        $this->submit           = new button("submit_button");
        $this->quantity         = new bp("quantity");
        $this->request_id       = new input("request_id");
        $this->reference        = new bp("reference");
        $this->description      = new bp("description");
        $this->entity_details   = new bentity_details();

        $this->request_id       ->set_hidden(true);

        $this->entity_details   ->set_label("Organisation:");
        $this->quantity         ->set_label("Quantity:");
        $this->reference        ->set_label("Reference:");
        $this->description      ->set_label("Description:");

        $this->submit           ->set_class("btn", "btn-primary");

        $this->submit           ->set_text("Submit");

        $this->layout           ->add_row(grid::FULL_WIDTH);
        $this->layout           ->add_row(grid::FULL_WIDTH);
        $this                   ->set_layout($this->layout);

        $this->info             ->add(
            $this->entity_details,
            $this->request_id,
            $this->reference,
            $this->quantity,
            $this->description
        );
        $panel->body            ->add($this->info);
        $panel->footer          ->add($this->submit);
        $this->response_info    ->add($panel);
        $this->form             ->add($this->response_info);
        $this                   ->add($this->form, $this->request_info);
    }


    public function add_response($_args = null)
    {
        $this                   ->define_maintenance();

        $this->response->request->focus($_args[0]);
        $this->request_info     ->add($this->get_request_container());

        $this->heading          ->set_text("Add request");
        $this->request_id       ->set_value($this->response->request->get_id());
        $this->entity_details   ->set_src(ssSession::$organisation->logo->get_location());
        $this->entity_details   ->set_text(ssSession::$organisation->get_name());
        $this->form             ->set_action("/response/save_response");
    }

    public function change_response($_args = null)
    {
        $this                   ->define_maintenance();

        $this->form         ->set_action("/response/update_response/" . $this->response->get_id());
    }

    public function view_response($_args = null)
    {
        $this                   ->define_view();

        $this->response->focus($_args[0]);

        $this->quantity         ->set_text($this->response->get_quantity());
        $this->request_id       ->set_value($this->response->get_request_id());
        $this->reference        ->set_text($this->response->get_reference());
        $this->description      ->set_text($this->response->get_description());

        $this->request_info->add($this->get_request_container());

        $this->heading          ->set_text("View response");
        $this->entity_details   ->set_src($this->response->organisation->logo->get_location());
        $this->entity_details   ->set_text($this->response->organisation->get_name());
        $this->form             ->set_action("/response/change_response/" . $this->response->get_id());
        $this->submit           ->set_text("Change");
    }

    public function update_response($_args = null)
    {
        $this->response->focus($_args[0]);
        $this->save_response();
    }

    public function save_response()
    {
        $this->response->set_organisation_id(ssSession::$organisation->get_id());
        $this->response->set_user_id(ssSession::$user->get_id());
        $this->response->set_request_id($_POST["request_id"]);
        $this->response->set_description($_POST["description"]);
        $this->response->set_quantity($_POST["quantity"]);

        $this->response->save();
    }

    public function get_request_container()
    {
        $panel            = new bpanel("Request");
        $info             = new div();
        $types            = new bp();
        $quantity         = new bp();
        $reference        = new bp();
        $description      = new bp("");
        $status           = new bp();
        $quantity_type    = new bp();
        $entity_details   = new bentity_details();
        $form_left        = new div();
        $form_right       = new div();
        $form_layout      = new grid();

        $form_layout      ->add_row(grid::HALF_WIDTH, grid::HALF_WIDTH);
        $form_layout      ->add_row(grid::FULL_WIDTH);
        $info             ->set_layout($form_layout);

        $entity_details   ->set_src($this->response->request->organisation->logo->get_location());
        $entity_details   ->set_text($this->response->request->organisation->get_name());

        $entity_details   ->set_label("Organisation");
        $types            ->set_label("Type");
        $quantity         ->set_label("Quantity");
        $quantity_type    ->set_label("Quantity Type");
        $status           ->set_label("Status");
        $reference        ->set_label("Reference");
        $description      ->set_label("Description");

        $types            ->set_text($this->response->request->type->get_label());
        $quantity         ->set_text($this->response->request->get_quantity());
        $quantity_type    ->set_text($this->response->request->quantity_type->get_label());
        $status           ->set_text($this->response->request->status->get_label());
        $reference        ->set_text($this->response->request->get_reference());
        $description      ->set_text($this->response->request->get_description());

        $info             ->set_class("col-md-12", "form-horizontal");

        $form_left        ->add($entity_details, $reference, $status);
        $form_right       ->add($types, $quantity_type, $quantity);
        $info             ->add($form_left, $form_right);
        $info             ->add($description);
        $panel->body      ->add($info);

        return $panel;
    }
}
