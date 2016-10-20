<?php

 use bones\base\control;
 
 use bones\controls\img;
 use bones\controls\span;

 class sentity_details extends control
 {
    private $img;
    private $span;

    public function __construct( $_name = "")
    {
        parent::__construct( $_name );

        $this->img  = new img();
        $this->span = new span();

        $this->set_class("entity-details");
        
        $this->set_element("div");
    }

    public function set_src( $_src )
    {
        $this->img->set_src($_src);
    }
 
    public function set_text( $_text )
    {
        return $this->span->set_text( $_text );    
    }

    public function get_body()
    {
        $this->set_renderer(self::DEFAULT_RENDERER);

        $this->img->render();
        $this->span->render();
    }
 }


