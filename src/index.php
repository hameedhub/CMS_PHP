<?php
include('config/path.php');
require_once('classes/DB.php');
require_once('classes/Session.php');
require_once('helper/Response.php');

Session::init();

if(Session::get('isLoggedIn')){
require_once('helper/SKey.php');
}


?>