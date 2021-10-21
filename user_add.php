<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.php');
}
include('includes/header.php');


if (isset($_GET['action']) && ($_GET['action'] == 'adduser')) {
    //echo "<pre>"; print_r($_POST);
    $sql = "INSERT INTO `user_accounts` (`username`, `password`, `email`, `role`, `status`) VALUES ('" . $_POST['username'] . "', '" . md5($_POST['password']). "', '" . $_POST['email']. "', '" . $_POST['role']. "', '0')";
    $result = tep_db_query($sql);
    $_SESSION['success'] = "Account created. Wait until admin approves your registration.";
    tep_redirect('user_management.php');
}
?>
<div class="container contact">
    <div class="row mt-3">
        <div class="col-md-3">
            <div class="contact-info">
                <img src="images/v-2-logo.svg" alt="image" />
                <h2>Add User</h2>
            </div>
        </div>
        <div class="col-md-9">
        <form action="user_add.php?action=adduser" method="post">
            <div class="contact-form">
                <div class="form-group">
                    <label class="control-label col-sm-6" for="fname">Username:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="username" placeholder="Username" id="username" required />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6" for="lname">Email:</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" placeholder="Email" id="email" required />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6" for="email">Password:</label>
                    <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" placeholder="Password" id="password" required />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-6" for="comment">Role:</label>
                    <div class="col-sm-10">
                        <select name="role" id="role" class="form-control">
                            <option value="0">User</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn2 btn-default" name="automate">Add User</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<?php include_once('includes/footer.php'); ?>