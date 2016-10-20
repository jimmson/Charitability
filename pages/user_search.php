<?php

 use bones\base\page;
 use bones\containers\div;
 use bones\containers\form;
 use bones\containers\table;
 use bones\controls\input;
 use bones\containers\button;
 use bones\controls\h;
 use bones\controls\span;
 use bones\controls\img;
 //use controls\sa as a;

 use bootstrap\controls\binput;

class user_search extends dashboard
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
        parent::__construct( "User Search" );
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
        $this->name         = new binput("user_name");
        $this->table_img    = new img();
        $this->table_link   = new sa();
        $this->table_surname= new span();

        $this->table_surname->set_data_items("user_surname");
        $this->table_surname->set_data_properties("set_text");
        $this->table_img    ->set_data_items("user_picture_path");
        $this->table_img    ->set_data_properties("set_src");
        $this->table_link   ->set_data_items("user_id","user_name");
        $this->table_link   ->set_data_properties("set_arg","set_text");

        $this->filters      ->set_class("col-md-12", "form-horizontal");
        $this->results      ->set_class("col-md-12", "table", "table-striped"); 
        $this->table_img    ->set_class("table-image"); 

        $this->name         ->set_label("Name:");
        $this->table_img    ->set_label("Picture");
        $this->table_link   ->set_label("Name");
        $this->table_surname->set_label("Surname");

        $this->table_link   ->set_page("user");
        $this->table_link   ->set_mode("view_user");

        $this->title        ->set_class("page-header");
        $this->name         ->set_class("form-control");
        $this->search       ->set_class("btn", "btn-lg", "btn-primary");

        $this->search       ->set_text("Search");
        $this->headding     ->set_text("Search for an user");

        $this->title        ->add( $this->headding, $this->search );
        $this->filters      ->add( $this->name );
        $this->results      ->add( $this->table_img, $this->table_link, $this->table_surname);
        $this->form         ->add( $this->title, $this->filters, $this->results );
        $this               ->add( $this->form );
    }

    private function get_users ( )
    {
        QUERY::QTABLE("ssm_user");
        QUERY::QCOLUMNS("user_id", "user_picture_path", "user_name", "user_surname");

        if ( isset($_POST["user_name"]) && $_POST["user_name"] != "")
        {
          QUERY::QWHERE(QUERY::condition("user_name", "=", QUERY::quote($_POST["user_name"])));            
        }

        return DB::select(QUERY::QSELECT());
    }

    public function search_user()
    {
        $this->results->set_data( $this->get_users() );
    }

    

}