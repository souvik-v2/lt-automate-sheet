<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.html');
}
include('includes/header.php');

if (isset($_GET['action']) && ($_GET['action'] === 'updateuser')) {
    //update
    $sql = "UPDATE user_accounts SET username = '" . $_POST['username'] . "', email = '" . $_POST['email'] . "', role = '" . $_POST['role'] . "', status = '" . $_POST['status'] . "' WHERE id = '" . $_POST['id'] . "' ";
    $result = tep_db_query($sql);
    $_SESSION['success'] = "User updated successfully!!!";
    tep_redirect('user_edit.php?id='.$_POST['id']);
    //header('Location: user_edit.php?id='.$_POST['id']);
}

$result = tep_db_query("SELECT id, username, email, password, role, status FROM user_accounts WHERE id = '". $_GET['id']."'");
$row = tep_db_fetch_array($result);
?>
<div class="content container">
    <h2>Edit User</h2>
    <div class="row">
        <div class="col-12">
            <form method="POST" action="user_edit.php?action=updateuser" name="automate-sheet">
                <div class="form-group">
                    <label for="formGroupExampleInput2">Username</label>
                    <input type="text" class="form-control" name="username" value="<?php echo $row['username']; ?>">
                </div>
                <!--<div class="form-group">
                    <label for="formGroupExampleInput2">Password</label>
                    <input type="password" name="password" class="form-control" value="<?php //echo $row['password']; ?>">
                </div> -->
                <div class="form-group">
                    <label for="formGroupExampleInput2">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput">User Role </label>
                    <select name="role" class="form-control">
                        <option value="0" <?php if($row['role'] == 0) echo 'selected="selected"'; ?>>User</option>
                        <option value="1" <?php if($row['role'] == 1) echo 'selected="selected"'; ?>>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput">User Status </label>
                    <select name="status" class="form-control">
                        <option value="0" <?php if($row['status'] == 0) echo 'selected="selected"'; ?>>Deactive</option>
                        <option value="1" <?php if($row['status'] == 1) echo 'selected="selected"'; ?>>Active</option>
                    </select>
                </div>
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button class="btn btn-primary" type="submit" name="automate">Update User</button>
            </form>
        </div>
    </div></div>

<?php include_once('includes/footer.php'); ?>