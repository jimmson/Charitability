<?php
 namespace bootstrap\containers;

 use bones\base\container;
 use bones\containers\div;
 use bones\controls\label;
 
 class btab extends container
 {
    public function __construct( $_name )
    {
        parent::__construct( $_name );
        
        $this->set_element("div");
        $this->set_id($_name);
        $this->set_class("tab-pane", "fade", "bTab");
    }
 }


