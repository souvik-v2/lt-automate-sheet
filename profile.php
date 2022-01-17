<?php
require('includes/application_top.php');
include('includes/header.php');
if ($_SESSION['loginuser']['dev'] == 'true') {
    try {
        $stmt = $con->run("SELECT dev_password as password FROM developer WHERE developer_id = ?", array($_SESSION['loginuser']['id']));
        $data = tep_db_fetch_array($stmt);
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
} else {
    try {
        $stmt = $con->run("SELECT password FROM user_accounts WHERE id = ?", array($_SESSION['loginuser']['id']));
        $data = tep_db_fetch_array($stmt);
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}
?>

<div class="container">
    <div class="row mb-3 mt-3">
        <div class="col">
            <h4>Profile Page</h4>
        </div>
    </div>
    <div class="row text-right">
        <div class="col-12 text-right">
            <a href="change_password.php?user_id=<?php echo $_SESSION['loginuser']['id']; ?>" class="btn" role="button">Change Password</a>
        </div>
    </div>
    <div class="row mb-3 mt-3">
        <p>Your account details are below:</p>
        <table class="table table-striped">
            <tr>
                <td>Username:</td>
                <td><?= $_SESSION['loginuser']['name'] ?></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><?= '<b>********</b>'; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php
include_once('includes/footer.php');
?>