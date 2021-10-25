<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.php');
}
include('includes/header.php');

if (isset($_GET['action']) && ($_GET['action'] === 'updatepassword')) {
    $query = "SELECT password FROM user_accounts WHERE password = '". md5($_POST["old_password"]) . "' and id = ". $_POST['id'];
    $result = tep_db_query($query);

    if(tep_db_num_rows($result) > 0) {
        $new_psw = tep_db_fetch_array($result);
        $sql = "UPDATE user_accounts SET password = '" . md5($_POST['new_password']) . "' WHERE id = '" . $_POST['id'] . "' ";
        $result = tep_db_query($sql);
        $_SESSION['success'] = "Password updated successfully!!!";
        tep_redirect('user_management.php');
    }
    //update
    $_SESSION['error'] = "Password not matched!!!";
    tep_redirect('change_password.php?user_id='.$_POST['id']);
}
?>
<div class="container contact">

    <div class="row mt-3">
        <div class="col-md-3">
            <div class="contact-info">
                <img src="images/v-2-logo.svg" alt="image" />
                <h2>Change Password</h2>
            </div>
        </div>
        <div class="col-md-9">
            <form method="POST" action="change_password.php?action=updatepassword" name="automate-sheet">
                <div class="contact-form">
                    <div class="form-group">
                        <label class="control-label col-sm-6">Current Password:</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="password" name="old_password" placeholder="Current Password" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-6">New Password:</label>
                        <div class="col-sm-10">
                        <input class="form-control" type="password" name="new_password" placeholder="New Password" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="hidden" name="id" value="<?php echo $_GET['user_id']; ?>">
                            <button type="submit" class="btn btn2 btn-default" name="automate">Update Password</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>