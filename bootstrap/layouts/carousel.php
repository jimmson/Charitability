<?php
namespace bootstrap\layouts;

use bones\base\layout;
use bones\containers\div;
use bones\containers\ol;
use bones\containers\li;
use bones\containers\a;
use bones\controls\span;

class carousel extends layout
{
    const LEFT  = 0;
    const RIGHT = 1;

    public function render($_container)
    {
        $controls 	= $_container->get_controls();
        $target 	= "#" . $_container->get_id();
        $active		= true;

        $carousel 	   = new div();
        $control_left  = $this->get_scroll_button(self::LEFT, $target);
        $control_right = $this->get_scroll_button(self::RIGHT, $target);
        $indicators	   = $this->get_indicators(count($controls), $target);

        $carousel ->set_class("carousel-inner");
        $carousel ->set_custom_attribute("role", "listbox");

        foreach ($controls as $control) {
            $item = new div();

            $item ->set_class("item");

            if ($active) {
                $active = false;
                $item ->set_class("active");
            }

            $item     ->add($control);
            $carousel ->add($item);
        }

        $indicators   	->render();
        $carousel   	->render();
        $control_left   ->render();
        $control_right  ->render();
    }

    private function get_scroll_button($_direction, $_target)
    {
        $scroller = new a();
        $icon	  = new span();
        $sr_only  = new span();

        $scroller ->set_class("carousel-control");
        $scroller ->set_class(($_direction == self::LEFT ? "left" : "right"));
        $scroller ->set_href($_target);
        $scroller ->set_custom_attribute("data-slide", ($_direction == self::LEFT ? "prev" : "next"));
        $scroller ->set_custom_attribute("role", "button");

        $icon ->set_class("glyphicon", "glyphicon-chevron-". ($_direction == self::LEFT ? "left" : "right"));
        $icon ->set_custom_attribute("aria-hidden", "true");

        $sr_only ->set_class("sr-only");
        $sr_only ->set_text(($_direction == self::LEFT ? "Previous" : "Next"));

        $scroller->add($icon, $sr_only);

        return $scroller;
    }

    private function get_indicators($num_indicators, $_target)
    {
        $ordered_list = new ol();

        $ordered_list ->set_class("carousel-indicators");

        for ($indicators = 0; $indicators < $num_indicators; $indicators++) {
            $indicator = new li();

            if ($indicators == 0) {
                $indicator ->set_class("active");
            }

            $indicator ->set_custom_attribute("data-target", $_target);
            $indicator ->set_custom_attribute("data-slide-to", strval($indicators));

            $ordered_list->add($indicator);
        }

        return $ordered_list;
    }
}
