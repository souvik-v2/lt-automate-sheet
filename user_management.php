<?php
require('includes/application_top.php');
include('includes/header.php');

if (isset($action) && ($action == 'deleteuser')) {
    $sql = $con->run("DELETE FROM user_accounts WHERE id = ?", array( $_GET['id']));
    $_SESSION['success'] = "User deleted successfully!!!";
}
$uid = $_SESSION['loginuser']['id'];
$result = $con->run("SELECT id, username, email, password, role, status FROM user_accounts WHERE id != ? order by id desc", array($uid));
?>
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <h4>User List</h4>
        </div>
    </div>
    <div class="row text-right">
        <div class="col-12 text-right">
            <a href="user_add.php" class="btn" role="button">Add User</a>
        </div>
    </div>
    <?php
        if (tep_db_num_rows($result) > 0) {
    ?>
            <div class="row mt-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $c = 0;
                        //echo '<pre>'; print_r(tep_db_fetch_array($result));
                        while ($row = tep_db_fetch_array($result)) {
                        ?>
                            <tr>
                                <th scope="row"><?php echo ++$c; ?> </th>
                                <td><?php echo $row["username"]; ?></td>
                                <td><?php echo $row["email"]; ?></td>
                                <td><?php echo ($row["role"] == '0' ? 'User' : 'Admin'); ?></td>
                                <td><?php echo ($row["status"] == '0' ? 'Deactive' : 'Active'); ?></td>
                                <td><a href="user_edit.php?id=<?php echo $row['id']; ?>"><i class="far fa-edit"></i></a> |
                                    <a onclick='deleteConfirmUser("<?php echo $row['id']; ?>")' href="javascript:void(0)"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <hr />
    <?php
        } else {
            echo "No data to display!!";
        }
    ?>
</div>
<?php include_once('includes/footer.php'); ?>