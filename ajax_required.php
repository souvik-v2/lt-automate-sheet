<?php
require('includes/application_top.php');

if (isset($_GET['project_id']) && !empty($_GET['project_id'])) {
    if (isset($_SESSION['developers'])) {
        unset($_SESSION['developers']);
    }

    $dev_aql = $con->run("SELECT developers FROM project WHERE project_id = ?", array($_GET['project_id']));

    $dev_result = tep_db_fetch_array($dev_aql);
    $_SESSION['developers'] = $dev_result['developers'];
}
//
if (isset($_GET['sprint_nm'], $_GET['pID']) && !empty($_GET['sprint_nm'])) {
    $dev_aql = $con->run("SELECT sprint_name FROM sprint_data WHERE sprint_name = ? AND project_id = ?", array($_GET['sprint_nm'], $_GET['pID']));

    if(tep_db_num_rows($dev_aql) > 0) {
        echo 'found';
    } else {
        echo 'new';
    }
}