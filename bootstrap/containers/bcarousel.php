<?php
 namespace bootstrap\containers;

 use bones\base\container;
 use bones\containers\div;

 use bootstrap\layouts\carousel;

 
 class bcarousel extends container
 {

    public function __construct( $_name = "" )
    {
        parent::__construct( $_name );

        $layout = new carousel();

        $this->set_layout($layout);
        $this->set_element("div");

        $this->set_class("carousel", "slide");
        $this->set_custom_attribute("data-ride", "carousel");
    }
 }


