<?php
namespace bootstrap\layouts;

use bones\base\layout;
use bones\containers\div;
use bones\containers\ul;
use bones\containers\li;
use bones\containers\a;

class tab extends layout
{
    public function render($_container)
    {
        $counter 	= 0;
        $active_set = false;
        $controls 	= $_container->get_controls();

        $nav 	= new ul();
        $panes 	= new div();

        $nav 	->set_class("nav", "nav-tabs");
        $panes  ->set_class("tab-content");

        foreach ($controls as $control) {
            $tab = new li();
            $link = new a();

            if (!$active_set) {
                $tab 		->set_class("active");
                $control 	->set_class("in", "active");
                $active_set = true;
            }

            $link ->set_href("#" . $control->get_id());
            $link ->set_custom_attribute("data-toggle", "tab");
            $link ->set_text($control->get_id());

            $tab ->add($link);
            $nav ->add($tab);

            $panes ->add($control);
        }

        $nav ->render();
        $panes ->render();
    }
}
