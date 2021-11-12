<?php
/*
Page: application_top.php
Author: Souvik Patra
*/
session_start();

require('includes/function.php');
require('includes/db.php');
// If the user is not logged in redirect to the login page...
//print_r($_SESSION); die();
if (!isset($_SESSION['loginuser']['loggedin']) && ('index.php' !== basename($_SERVER['PHP_SELF']))) {
    tep_redirect('index.php');
}
//set action variable
$action = (isset($_GET['action']) && !empty($_GET['action']) ? $_GET['action'] : '');

?>