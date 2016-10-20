<?php

 use bones\base\page;
 use bones\containers\nav;
 use bones\containers\div;
 use bones\containers\ul;
 use bones\containers\li;
 use bones\containers\button;
 use bones\containers\a;
 use bones\controls\p;
 use bones\controls\i;
 use bones\controls\img;
 use bones\controls\span;

class dashboard extends page
{

    private $app_area;

	public function __construct( $_title )
	{
		parent::__construct( $_title );
    }

    public function define()
    {
        $this->app_area = new div();
        $this->wrapper = new div();

        $this->app_area ->set_id("page-wrapper");
        $this->wrapper  ->set_id("wrapper");

        $this->add_stylesheet( "/public/bootstrap/dist/css/bootstrap.min.css" );
        $this->add_stylesheet( "/public/metisMenu/dist/metisMenu.min.css" );
        $this->add_stylesheet( "/public/css/sb-admin-2.css" );
        $this->add_stylesheet( "/public/font-awesome/css/font-awesome.min.css" );
        $this->add_stylesheet( "/public/fileinput/css/fileinput.min.css" );
        $this->add_stylesheet( "/public/css/style.css" );
        $this->add_stylesheet( "/public/css/dashboard.css" );

        $this->add_meta( "viewport", "width=device-width, initial-scale=1" );

        $this->add_script( "/public/jquery/dist/jquery.min.js" );
        $this->add_script( "/public/bootstrap/dist/js/bootstrap.min.js" );
        $this->add_script( "/public/fileinput/js/fileinput.min.js" );
        $this->add_script( "/public/metisMenu/dist/metisMenu.min.js" );
        $this->add_script( "/public/js/sb-admin-2.js" );

        $this->wrapper->add( 
            $this->get_nav_container(),
            $this->app_area 
        );

        parent::add($this->wrapper);
    }        

    public function add( ...$_control )
    {
        $this->app_area->add( ...$_control );
    } 

    public function set_layout( $_layout )
    {
         $this->app_area->set_layout( $_layout );
    } 

    private function get_nav_container()
    {
        $nav  = new nav();

        $nav ->set_class("navbar", "navbar-default", "navbar-static-top");
        $nav ->set_custom_attribute("role", "navigation");

        $nav ->set_style("margin-bottom", "0px");

        $nav->add(
            $this->get_header(), 
            $this->get_header_links(),
            $this->get_menu_container()
        );

        return $nav;
    }

    private function get_header()
    {

        $nav_header   = new div();
        $brand        = new a(); 
        $nav_togle    = new button("", button::BUTTON);
        $icon_bar     = new span();

        $nav_header   ->set_class("navbar-header");
        $brand        ->set_class("navbar-brand");
        $nav_togle    ->set_class("navbar-toggle"); 
        $icon_bar     ->set_class("icon-bar");

        $nav_togle    ->set_custom_attribute("data-toggle", "collapse");
        $nav_togle    ->set_custom_attribute("data-target", ".navbar-collapse");

        $brand        ->set_text(ssSession::$organisation->get_name());

        $nav_togle    ->add($icon_bar, $icon_bar, $icon_bar);  
        $nav_header   ->add($nav_togle, $brand);

        return $nav_header;

       /* $header       = new a(); 
        $logo         = new img();
        $organisation = new span();

        $header       ->set_class("navbar-brand");
        $logo         ->set_class("user-image"); 
        $organisation ->set_class("");

        if (ssSession::$organisation)
        {
            $logo         ->set_src(ssSession::$organisation->get_logo());
            $organisation ->set_text(ssSession::$organisation->get_name());
        }
        else
        {
            $organisation ->set_text("Self");
        }

        $header->add($logo, $organisation);

        return $header;*/

    }

    private function get_header_links()
    {
        $user_fullname = ssSession::$user->get_name() . " " . ssSession::$user->get_surname();

        // Menu controls  
        $top_links      = new ul();
        $user_item      = new li();
        $user_dropdown  = new ul();
        $user_toggle    = new a();

        // User Dropdown controls
        $header     = new li(); 
        $footer     = new li(); 
        $left       = new div(); 
        $right      = new div(); 
        $profile    = new a();
        $logout     = new a();
        $info       = new p();
        $spicture   = new img();
        $lpicture   = new img();
        $name       = new span();

        $top_links      ->set_class("nav", "navbar-top-links", "navbar-right");
        $user_item      ->set_class("dropdown");
        $user_toggle    ->set_class("dropdown-toggle");
        $user_dropdown  ->set_class("dropdown-menu", "dropdown-user");
        $header         ->set_class("user-header");
        $footer         ->set_class("user-footer");
        $spicture       ->set_class("user-image");
        $lpicture       ->set_class("img-circle");
        $left           ->set_class("pull-left");
        $right          ->set_class("pull-right");
        $profile        ->set_class("btn", "btn-default", "btn-primary");
        $logout         ->set_class("btn", "btn-default", "btn-primary");

        $name           ->set_text($user_fullname);
        $info           ->set_text($user_fullname);
        $logout         ->set_text("Logout");
        $profile        ->set_text("Profile");

        $user_toggle    ->set_custom_attribute("data-toggle", "dropdown");

        $logout         ->set_href("/login/logout");
        $profile        ->set_href("/user/view_user/" . ssSession::$user->get_id());

        $spicture       ->set_src(ssSession::$user->get_picture());
        $lpicture       ->set_src(ssSession::$user->get_picture());

        $user_toggle    ->add( $spicture, $name );
        $left           ->add( $profile );
        $right          ->add( $logout );
        $header         ->add( $lpicture, $info );
        $footer         ->add( $left, $right );
        $user_dropdown  ->add( $header, $footer );
        $user_item      ->add( $user_toggle, $user_dropdown );
        $top_links      ->add( $user_item );

        return $top_links;
    }

    private function get_menu_container()
    {
        $sidebar      = new div(); 
        $sidebar_nav  = new div(); 

        $menu         = $this->build_menu(0);

        $menu         ->set_class("nav");
        $sidebar      ->set_class("navbar-default", "sidebar");
        $sidebar_nav  ->set_class("sidebar-nav", "navbar-collapse");

        $menu         ->set_id("side-menu");

        $sidebar      ->set_custom_attribute("role", "navigation");
        
        $sidebar_nav  ->add( $menu );
        $sidebar      ->add( $sidebar_nav );
        
        return $sidebar;
    }

    private function build_menu( $_parent_menu_id )
    {
        QUERY::QTABLE("ssm_menu_item");
        QUERY::QWHERE(QUERY::condition("parent_menu_item_id", "=", $_parent_menu_id));
        QUERY::QAND  (QUERY::condition("category_code",       "=", QUERY::quote("menu_sidebar")));

        $menu_items = DB::select(QUERY::QSELECT());
       
        if (empty($menu_items)) 
            return null;

        $menu = new ul(); 

        foreach ( $menu_items as $menu_item )
        {
            $item      = new li(); 
            $link      = new a();
            $icon      = new i();
            $arrow     = new span();
            $sub_menu  = $this->build_menu( $menu_item["menu_item_id"] );
            $icon_type = trim($menu_item["menu_icon"]);

            $arrow      ->set_class("fa arrow"); 
            $link       ->set_text( $menu_item["menu_item_label"] );
            $link       ->set_href( $menu_item["menu_item_link" ] );

            $item->add( $link );

            if ( $icon_type != "")
            {
                $icon ->set_class("fa", $icon_type, "fa-fw");
                $link->add( $icon );
            } 

            if ( $sub_menu ) 
            {
                $sub_menu->set_class("nav", "nav-second-level");

                $link->add( $arrow );
                $item->add( $sub_menu );
            }

            $menu->add( $item );
        }

        return $menu;
    }
}