<?php
 namespace bootstrap\containers;

 use bones\base\container;
 use bones\containers\div;

 use bootstrap\layouts\panel;


 class bpanel extends container
 {
    public $heading;
    public $body;
    public $footer;

    public function __construct( $_heading = "")
    {
        parent::__construct();

        $layout = new panel();

        $this->set_layout($layout);
        $this->set_element("div");

        $this->heading  = new div();
        $this->footer   = new div();
        $this->body     = new div();

        $this           ->set_class("panel", "panel-default");
        $this->heading  ->set_class("panel-heading");
        $this->body     ->set_class("panel-body");
        $this->footer   ->set_class("panel-footer");

        $this->heading  ->set_text($_heading);
    }

    public function set_heading( $_heading )
    {
        $this->heading->set_text($_heading);
    }

    public function add( ...$_control )
    {
        $this->body->add( ...$_control );
    } 

}


