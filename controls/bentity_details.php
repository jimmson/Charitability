<?php

 //use bones\base\control;
 
 use bones\controls\img;
 use bones\controls\span;
 use bones\controls\label;
 use bones\containers\div;

 class bentity_details extends sentity_details
 {
     const INLINE = 0;
     const GROUP  = 1;

     private $label_control;
     private $form_layout;

     public function __construct($_name = "", $_form_layout = self::GROUP)
     {
         parent::__construct($_name);

         $this->set_renderer("b_render_control");

         $this->form_layout = $_form_layout;
     }

     public function set_label($_text)
     {
         $this->label_control = new label("label");
         $this->label_control->set_text($_text);
     }
 
     public function get_label()
     {
         return $this->label_control;
     }

     protected function b_render_control()
     {
         $this->set_renderer(self::DEFAULT_RENDERER);

         $div_group = new div("");

         $div_group->set_class("form-group");

         if ($this->label_control) {
             if (!$this->get_id()) {
                 $this->set_id($this->get_name());
                 $this->label_control->set_for($this->get_id());
             }

             $this->label_control ->set_class("control-label");
             $div_group->add($this->label_control);
         }

         if ($this->form_layout == self::INLINE) {
             if ($this->label_control) {
                 $this->label_control ->set_class("col-sm-4 ");
             }

             $div_wrap            = new div("");
             $div_wrap            ->set_class("col-sm-8");
             $div_wrap ->add($this);
             $div_group->add($div_wrap);
         } else {
             $div_group->add($this);
         }
         $div_group->render();
     }
 }
