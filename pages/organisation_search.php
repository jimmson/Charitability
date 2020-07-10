<?php

use bones\base\page;
use bones\containers\div;
use bones\containers\form;
use bones\containers\table;
use bones\containers\button;
use bones\controls\input;
use bones\controls\h;
use bones\controls\img;
//use controls\sa as a;

use bootstrap\containers\bpanel;
use bootstrap\controls\binput;
use bootstrap\layouts\grid;

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
        parent::__construct("Organisation Search");
    }

    public function define()
    {
        parent::define();

        $this->layout       = new grid();
        $this->form         = new form("", form::POST);
        $this->filters      = new bpanel("Search for an organisation");
        $this->name         = new binput("organisation_name");
        $this->search       = new button("search_button");
        $this->results      = new bpanel("Search results...");
        $this->table        = new table();
        $this->table_img    = new img();
        $this->table_link   = new sa();

        $this               ->set_layout($this->layout);
        $this->layout       ->add_row(grid::FULL_WIDTH);
        $this->layout       ->add_row(grid::FULL_WIDTH);

        $this->table_img    ->set_data_items("file_location");
        $this->table_img    ->set_data_properties("set_src");
        $this->table_link   ->set_data_items("organisation_id", "organisation_name");
        $this->table_link   ->set_data_properties("set_arg", "set_text");

        $this->name         ->set_label("Name:");
        $this->table_img    ->set_label("Logo:");
        $this->table_link   ->set_label("Organisation:");

        $this->filters      ->set_class("mt-20");
        $this->table        ->set_class("col-md-12", "table", "table-striped");
        $this->name         ->set_class("form-control");
        $this->search       ->set_class("btn", "btn-primary");
        $this->table_img    ->set_class("table-image");

        $this->search       ->set_text("Search");

        $this->table_link   ->set_page("organisation");
        $this->table_link   ->set_mode("view_organisation");

        $this->form              ->add($this->filters);
        $this->filters->body     ->add($this->name);
        $this->filters->footer   ->add($this->search);
        $this->results->body     ->add($this->table);
        $this->table             ->add($this->table_img, $this->table_link);
        $this                    ->add($this->form, $this->results);
    }

    private function get_organisations()
    {
        QUERY::QTABLE("ssm_organisation");
        QUERY::QCOLUMNS("organisation_id", "organisation_name", "file_location");
        QUERY::QJOIN("ssm_file", QUERY::condition("ssm_organisation.logo_file_id", "=", "ssm_file.file_id"));

        if (isset($_POST["organisation_name"]) && $_POST["organisation_name"] != "") {
            QUERY::QWHERE(QUERY::condition("organisation_name", "=", QUERY::quote($_POST["organisation_name"])));
        }

        return DB::select(QUERY::QSELECT());
    }

    public function search_organisation()
    {
        $this->table->set_data($this->get_organisations());
    }
}
