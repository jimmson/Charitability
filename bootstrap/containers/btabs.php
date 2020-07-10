<?php
 namespace bootstrap\containers;

 use bones\base\container;
 use bones\containers\select;
 use bones\containers\div;
 use bones\controls\label;

 use bootstrap\layouts\tab;

 class btabs extends container
 {
     public function __construct($_name = "")
     {
         parent::__construct($_name);

         $layout = new tab();

         $this->set_layout($layout);
        
         $this->set_element("div");
     }
 }
