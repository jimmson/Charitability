<!DOCTYPE html>

<?php

require ("start.php");

use bones\controls\input;
use bones\base\page;
use bones\containers\nav;
use bones\containers\div;
use bones\layouts\grid;

$page 		= new login("Welcome");

print_r($_GET);
print_r($_POST);

$page->render();

/*
$control   	= new input("user_input");
$nav 	   	= new nav("nav_container");
$div 		= new div("div_container");
$page 		= new login();

$page->add_stylesheet( "http://getbootstrap.com/dist/css/bootstrap.min.css" );
$page->add_meta( "viewport", "width=device-width, initial-scale=1" );
$page->render();


$control->set_class("form-control");
$control->set_placeholder("pLaCeHoLdEr");

$nav->add($control);
$nav->set_class("navbar", "navbar-inverse", "navbar-fixed-top");
$nav->render();

$grid_layout = new grid();
$grid_layout->add_row(grid::FULL_WIDTH);
$grid_layout->add_row(grid::HALF_WIDTH, grid::HALF_WIDTH);

$div->set_layout( $grid_layout );
$div->add($control, $control, $control);
$div->render();
*/

?>
