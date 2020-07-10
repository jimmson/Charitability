<?php

use bones\base\page;
use bones\containers\div;
use bones\containers\form;
use bones\containers\table;
use bones\controls\option;
use bones\controls\span;
use bones\containers\button;
use bones\controls\h;
use bones\controls\img;
//use controls\sa as a;

use bootstrap\containers\bselect;
use bootstrap\containers\bpanel;
use bootstrap\controls\binput;
use bootstrap\layouts\grid;

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
        parent::__construct("Request Search");
    }

    public function define()
    {
        parent::define();

        $this->layout           = new grid();
        $this->form             = new form("", form::POST);
        $this->filters          = new bpanel("Search for a request");
        $this->name             = new binput("organisation_name");
        $this->search           = new button("search_button");
        $this->results          = new bpanel("Search results...");
        $this->table            = new table();
        $this->table_img        = new img();
        $this->table_link       = new sa();
        $this->table_req_type   = new span();
        $this->table_req_qty    = new span();
        $this->table_qty_type   = new span();

        $this                   ->set_layout($this->layout);
        $this->layout           ->add_row(grid::FULL_WIDTH);
        $this->layout           ->add_row(grid::FULL_WIDTH);

        $this->types            = new bselect("type");
        $types                  = new option();
        $types_default          = new option();

        $this->organisations    = new bselect("organisation");
        $organisation_default   = new option();
        $organisations          = new option();

        $types_default          ->set_value("0");
        $this->types            ->set_data(ssUtility::get_types("req_Type"));
        $types                  ->set_data_items("type_code", "type_label");
        $types                  ->set_data_properties("set_value", "set_text");

        $organisation_default   ->set_value("self");
        $organisations          ->set_data_items("organisation_id", "organisation_name");
        $organisations          ->set_data_properties("set_value", "set_text");
        $this->organisations     ->set_data(ssOrganisation::get_organisation_data());

        $this->table_img        ->set_data_items("file_location");
        $this->table_img        ->set_data_properties("set_src");

        $this->table_req_type   ->set_data_items("req_type_label");
        $this->table_req_type   ->set_data_properties("set_text");

        $this->table_qty_type   ->set_data_items("qty_type_label");
        $this->table_qty_type   ->set_data_properties("set_text");

        $this->table_req_qty    ->set_data_items("request_quantity");
        $this->table_req_qty    ->set_data_properties("set_text");

        $this->table_link       ->set_data_items("request_id", "request_reference");
        $this->table_link       ->set_data_properties("set_arg", "set_text");

        $this->filters          ->set_class("mt-20");
        $this->table            ->set_class("col-md-12", "table", "table-striped");
        $this->table_img        ->set_class("table-image");
        $this->search           ->set_class("btn", "btn-primary");
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
        $organisation_default   ->set_text("Self");
        $types_default          ->set_text("Select a Request type");

        $this->types            ->add($types_default, $types);
        $this->organisations    ->add($organisation_default, $organisations);
        $this->filters->body    ->add($this->organisations, $this->types);
        $this->filters->footer  ->add($this->search);
        $this->table            ->add(
            $this->table_img,
            $this->table_link,
            $this->table_req_type,
            $this->table_qty_type,
            $this->table_req_qty
        );
        $this->results->body    ->add($this->table);
        $this->form             ->add($this->filters);
        $this                   ->add($this->form, $this->results);
    }

    private function get_requests()
    {
        QUERY::QTABLE("sst_request");

        QUERY::QCOLUMNS(
            QUERY::QALIAS("qty_type.type_label", "qty_type_label"),
            QUERY::QALIAS("req_type.type_label", "req_type_label"),
            "request_id",
            "request_reference",
            "request_quantity",
            "organisation_name",
            "file_location"
        );

        QUERY::QJOIN("ssm_organisation", QUERY::condition("sst_request.organisation_id", "=", "ssm_organisation.organisation_id"));
        QUERY::QJOIN("ssm_file", QUERY::condition("ssm_organisation.logo_file_id", "=", "ssm_file.file_id"));
        QUERY::QJOIN("ssc_type", QUERY::condition("sst_request.request_type", "=", "req_type.type_code"), "req_type");
        QUERY::QJOIN("ssc_type", QUERY::condition("sst_request.quantity_type", "=", "qty_type.type_code"), "qty_type");
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
        $this->table->set_data($this->get_requests());
    }
}
