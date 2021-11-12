<?php
require('includes/application_top.php');
tep_db_close();
session_destroy();
// Redirect to the login page:
header('Location: index.php');
?>