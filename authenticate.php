<?php
session_start();
require('includes/db.php');

require('includes/function.php');

if ( !isset($_POST['username'], $_POST['password']) ) {
    $_SESSION['error'] = "Please fill both the username and password fields!";
    tep_redirect('index.php');
}

$username = $_POST['username'];
$password = $_POST['password'];
$check_query = $con->run("SELECT id, username, password, role FROM user_accounts WHERE username = ? AND status = ?", array($username, 1));

if ($check_query->rowCount() > 0) {
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
        $_SESSION['error'] = "Password not matched!!";
        tep_redirect('index.php');
    }

} else {
    $_SESSION['error'] = "Username/Password not matched!!";
    tep_redirect('index.php');
}



