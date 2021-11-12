<?php
require('includes/application_top.php');
include('includes/header.php');
try {
    $stmt = $con->run("SELECT password, email FROM user_accounts WHERE id = ? ORDER BY id DESC", array($_SESSION['loginuser']['id']));
    $data = tep_db_fetch_array($stmt);
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
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
                <td><?= $data['password'] ?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><?= $data['email'] ?></td>
            </tr>
        </table>
    </div>
</div>

<?php
include_once('includes/footer.php');
?>