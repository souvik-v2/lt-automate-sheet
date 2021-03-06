<?php
require('includes/application_top.php');
include('includes/header.php');

if (isset($action) && ($action == 'updateuser')) {
    //update
    $sql_data_array = array (
        $_POST['username'],
        $_POST['email'],
        $_POST['role'],
        $_POST['status'],
        $_POST['id']
    );

    try {
        $result = $con->run("UPDATE user_accounts SET username = ?, email = ?, role = ?, status = ? WHERE id = ?", $sql_data_array);
        $_SESSION['success'] = "User updated successfully!!!";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    tep_redirect('user_management.php');
}

$result = $con->run("SELECT id, username, email, password, role, status FROM user_accounts WHERE id = ?", array($_GET['id']));
$row = tep_db_fetch_array($result);
?>
<div class="container contact">

    <div class="row mt-3">
        <div class="col-md-3">
            <div class="contact-info">
                <img src="images/v-2-logo.svg" alt="image" />
                <h2>Edit User</h2>
            </div>
        </div>
        <div class="col-md-9">
            <form method="POST" action="user_edit.php?action=updateuser" name="automate-sheet">
                <div class="contact-form">
                    <div class="form-group">
                        <label class="control-label col-sm-6" for="fname">Username:</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="username" placeholder="Username" id="username" value="<?php echo $row['username']; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-6" for="lname">Email:</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="email" name="email" placeholder="Email" id="email" value="<?php echo $row['email']; ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-6" for="comment">Role:</label>
                        <div class="col-sm-10">
                            <select name="role" class="form-control">
                                <option value="0" <?php if ($row['role'] == 0) echo 'selected="selected"'; ?>>User</option>
                                <option value="1" <?php if ($row['role'] == 1) echo 'selected="selected"'; ?>>Admin</option>
                                <option value="1" <?php if ($row['role'] == 2) echo 'selected="selected"'; ?>>Developer</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-6" for="comment">Status:</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-control">
                                <option value="0" <?php if ($row['status'] == 0) echo 'selected="selected"'; ?>>Deactive</option>
                                <option value="1" <?php if ($row['status'] == 1) echo 'selected="selected"'; ?>>Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn2 btn-default" name="automate">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>