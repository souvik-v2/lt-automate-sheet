<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.php');
}

if (isset($_GET['project_id'])) {
    if (isset($_SESSION['developers'])) {
        unset($_SESSION['developers']);
    }

    $dev_aql = tep_db_query("SELECT developers FROM project WHERE project_id = '" . tep_db_input($_GET['project_id']) . "'");

    $dev_result = tep_db_fetch_array($dev_aql);
    $_SESSION['developers'] = $dev_result['developers'];
}
