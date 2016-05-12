<!DOCTYPE html>

<?php
require ("bones_include.php");
require ("app_include.php");
require ("bootstrap_include.php");

use bones\base\page;

ssSession::start();

if (ssSession::available()) ssSession::focus();

ssApp::handle_request( $_GET["url"] );

?>
