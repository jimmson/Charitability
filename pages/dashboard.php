<?php

 use bones\base\page;
 use bones\containers\nav;
 use bones\containers\div;
 use bones\containers\ul;
 use bones\containers\li;
 use bones\containers\a;
 use bones\controls\p;
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

        $this->app_area->set_class("col-sm-9", "col-sm-offset-3", "col-md-10", "col-md-offset-2", "main", "app-area");

        $this->add_stylesheet( "http://getbootstrap.com/dist/css/bootstrap.min.css" );
        $this->add_stylesheet( "/public/css/dashboard.css" );
        $this->add_stylesheet( "/public/css/style.css" );
        $this->add_meta( "viewport", "width=device-width, initial-scale=1" );
        $this->add_script( "http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js" );
        $this->add_script( "http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js" );

        parent::add( $this->get_nav_container() );
        parent::add( $this->get_menu_container() );
        parent::add( $this->app_area );
    }

    public function add( ...$_control )
    {
        $this->app_area->add( ...$_control );
    } 

    private function get_nav_container()
    {
        $nav         = new nav();
        $nav_header  = new div();
        $nav_menu    = new ul();

        $nav         ->set_class("navbar", "navbar-inverse", "navbar-fixed-top");
        $nav_menu    ->set_class("nav", "navbar-nav", "navbar-right");
        $nav_header  ->set_class("navbar-header");

        $nav_menu    ->add($this->get_profile_item());
        $nav_header  ->add($this->get_header());
        $nav         ->add($nav_header, $nav_menu);

    	return $nav;
    }

    private function get_header()
    {
        $header       = new a(); 
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

        return $header;

    }

    private function get_profile_item()
    {
        $user_fullname = ssSession::$user->get_name() . " " . ssSession::$user->get_surname();

        $user       = new li();
        $dropdown   = new ul();
        $header     = new li(); 
        $footer     = new li(); 
        $left       = new div(); 
        $right      = new div(); 
        $profile    = new a();
        $logout     = new a();
        $info       = new p();
        $link       = new a();
        $spicture   = new img();
        $lpicture   = new img();
        $name       = new span();
         
        $user       ->set_class("dropdown", "user", "user-menu");
        $dropdown   ->set_class("dropdown-menu");
        $link       ->set_class("dropdown-toggle");
        $header     ->set_class("user-header");
        $footer     ->set_class("user-footer");
        $spicture   ->set_class("user-image");
        $lpicture   ->set_class("img-circle");
        $left       ->set_class("pull-left");
        $right      ->set_class("pull-right");
        $profile    ->set_class("btn", "btn-default", "btn-primary");
        $logout     ->set_class("btn", "btn-default", "btn-primary");
        
        $info       ->set_text($user_fullname);
        $logout     ->set_text("Logout");
        $profile    ->set_text("Profile");
        $name       ->set_text($user_fullname);

        $logout     ->set_href("/login/logout");
        $profile    ->set_href("/user/view_user/" . ssSession::$user->get_id());

        $link       ->set_custom_attribute("data-toggle", "dropdown");    

        $spicture   ->set_src(ssSession::$user->get_picture());
        $lpicture   ->set_src(ssSession::$user->get_picture());

        $link       ->add( $spicture, $name );
        $left       ->add( $profile );
        $right      ->add( $logout );
        $header     ->add( $lpicture, $info );
        $footer     ->add( $left, $right );
        $dropdown   ->add( $header, $footer );
        $user       ->add( $link, $dropdown );

        return $user;
    }

    private function get_menu_container()
    {
    	$sidebar   = new div(); 
        $menu      = $this->build_menu(0);

        $menu      ->set_class("nav", "nav-stacked");
        $sidebar   ->set_class("col-sm-3", "col-md-2", "sidebar");
        
        $sidebar   ->add($menu);
        
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
            $sub_menu  = $this->build_menu( $menu_item["menu_item_id"] );

            $link->set_text($menu_item["menu_item_label"]);
            $link->set_href($menu_item["menu_item_link"]);

            $item->add( $link );

            if ( $sub_menu ) 
            {
                $item->add( $sub_menu );
                $item->set_class("panel");
            }

            $menu->add( $item );
        }

        return $menu; 
    }
    

}