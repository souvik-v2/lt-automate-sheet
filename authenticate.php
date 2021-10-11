<?php
session_start();
require('includes/db.php');

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if ( !isset($_POST['username'], $_POST['password']) ) {
	// Could not get the data that should have been sent.
	exit('Please fill both the username and password fields!');
}
// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
$username = tep_db_prepare_input($_POST['username']);
$password = tep_db_prepare_input($_POST['password']);
$check_query = tep_db_query("SELECT id, username, password, role FROM user_accounts WHERE username = '" . tep_db_input($username) . "' AND status = 1");
if (tep_db_num_rows($check_query) == 1) {
    $check = tep_db_fetch_array($check_query);

    if (tep_validate_password($password, $check['password'])) {
        tep_session_register('loginuser');
        $loginuser = array(
            'loggedin' => true,
            'id' => $check['id'],
            'name' => $check['username'],
            'role' => ($check['role'] == 1 ? 'admin' : 'user')
        );
        $_SESSION['success'] = "Logged in successfully!!";
        tep_redirect('home.php');
    } else {
        tep_redirect('index.html');
    }

} else {
    tep_redirect('index.html');
}



