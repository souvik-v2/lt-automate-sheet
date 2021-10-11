<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.html');
}
include('includes/header.php');

$stmt = tep_db_query("SELECT password, email FROM user_accounts WHERE id = '" . $_SESSION['loginuser']['id'] ."'");
$data = tep_db_fetch_array($stmt);
?>

<div class="content">
    <h2>Profile Page</h2>
    <div>
        <p>Your account details are below:</p>
        <table>
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