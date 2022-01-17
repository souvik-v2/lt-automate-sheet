<?php
session_start();
require('includes/db.php');

require('includes/function.php');

if (!isset($_POST['username'], $_POST['password'])) {
    $_SESSION['error'] = "Please fill both the username and password fields!";
    tep_redirect('index.php');
}

$username = $_POST['username'];
$password = $_POST['password'];
$check_query = $con->run("SELECT id, username, password, role FROM user_accounts WHERE username = ? AND status = ?", array($username, 1));

if ($check_query->rowCount() == 1) {
    $check = tep_db_fetch_array($check_query);

    if (tep_validate_password($password, $check['password'])) {
        tep_session_register('loginuser');
        $loginuser = array(
            'loggedin' => true,
            'id' => $check['id'],
            'name' => $check['username'],
            'role' => ($check['role'] == 1 ? 'admin' : 'user'),
            'dev' => false,
            //'dev' => ($check['role'] == 2 ? true : false),
        );
        $_SESSION['success'] = "Logged in successfully!!";
        // if($check['role'] == 2) {
        //     tep_redirect('developer_home.php');
        // } else {
        //     tep_redirect('home.php');
        // }
        tep_redirect('home.php');
    } else {
        $_SESSION['error'] = "Password not matched!!";
        tep_redirect('index.php');
    }
} else if ($check_query->rowCount() > 1) {
    //Two rows with same username.
    $_SESSION['error'] = "Multiple entry with same username";
    tep_redirect('index.php');
} else {
    $check_dev_query = $con->run("SELECT developer_id, developer_name, dev_password, dev_role FROM developer WHERE developer_name = ? AND developer_status = ?", array($username, 1));
    if ($check_dev_query->rowCount() == 1) {
        $check = tep_db_fetch_array($check_dev_query);
        if (tep_validate_password($password, $check['dev_password'])) {
            tep_session_register('loginuser');
            $loginuser = array(
                'loggedin' => true,
                'id' => $check['developer_id'],
                'name' => $check['developer_name'],
                'role' => ($check['dev_role'] == 1 ? 'admin' : 'user'),
                'dev' => true,
            );
            $_SESSION['success'] = "Logged in successfully!!";
            tep_redirect('developer_home.php');
        } else {
            $_SESSION['error'] = "Password not matched!!";
            tep_redirect('index.php');
        }
    } else {
        $_SESSION['error'] = "Username/Password not matched!!";
        tep_redirect('index.php');
    }
}
