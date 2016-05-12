<?php

 use bones\containers\a;
 
 class sa extends a
 {
    private $page;
    private $mode;
    private $arg;

    public function __construct( $_name = "")
    {
        parent::__construct( $_name );
        
        $this->set_element("a");
    }

    public function set_page( $_page )
    {
        $this->page = $_page;
    }
 
    public function get_page()
    {
        return $this->page;    
    }

    public function set_mode( $_mode )
    {
        $this->mode = $_mode;
    }
 
    public function get_mode()
    {
        return $this->mode;    
    }

    public function set_arg( $_arg )
    {
        $this->arg = $_arg;
    }
 
    public function get_arg()
    {
        return $this->arg;    
    }

    public function get_href()
    {
        if ( $this->href )
            return $this->href;
        else        
            return "/" . $this->get_page() . "/" . $this->get_mode() . "/" . $this->get_arg();    
    }

 }


