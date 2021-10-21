<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.php');
}
include('includes/header.php');

if (isset($_GET['action']) && ($_GET['action'] === 'deleteuser')) {
    $sql = tep_db_query("DELETE FROM user_accounts WHERE id = '" . $_GET['id'] . "'");
    $_SESSION['success'] = "User deleted successfully!!!";
}

$result = tep_db_query("SELECT id, username, email, password, role, status FROM user_accounts WHERE role != '1' order by id desc");
?>
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <h4>User List</h4>
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
                        while ($row = tep_db_fetch_array($result)) {
                        ?>
                            <tr>
                                <th scope="row"><?php echo ++$c; ?> </th>
                                <td><?php echo $row["username"]; ?></td>
                                <td><?php echo $row["email"]; ?></td>
                                <td><?php echo ($row["role"] === '0' ? 'User' : 'Admin'); ?></td>
                                <td><?php echo ($row["status"] === '0' ? 'Deactive' : 'Active'); ?></td>
                                <td><a href="user_edit.php?id=<?php echo $row['id']; ?>"><i class="far fa-edit"></i></a> |
                                    <a onclick='deleteConfirm("<?php echo $row['id']; ?>")' href="javascript:void(0)"><i class="far fa-trash-alt"></i></a>
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
<script>
    function deleteConfirm(id) {
        //console.log(id);
        if (confirm("Are you sure to delete this user?")) {
            location.href = "user_management.php?action=deleteuser&id=" + id;
        }
    }
</script>
<?php include_once('includes/footer.php'); ?>