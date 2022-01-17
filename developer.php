<?php
require('includes/application_top.php');
include('includes/header.php');

if (isset($action) && ($action == 'deactivedeveloper')) {
    try {
        $_GET['developer_status'] = ($_GET['developer_status'] == 1 ? 0 : 1);
        $result = $con->run("UPDATE developer SET developer_status = ? WHERE developer_id = ?", array($_GET['developer_status'], $_GET['developer_id']));
        $_SESSION['success'] = "Developer status updated successfully!!!";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    
    tep_redirect('developer.php');
}

$p_result = $con->run("SELECT d.developer_id, d.developer_name, p.project_id, p.project_name, d.developer_status FROM developer d, project p WHERE d.project_id = p.project_id ORDER BY d.developer_id DESC");
?>
<div class="container-fluid">
    <div class="row mt-3 mb-3">
        <div class="col">
        <h4>Developer List</h4>
        </div>
    </div>
    <div class="row text-right">
        <div class="col-12 text-right">
            <a href="developer_edit.php" class="btn" role="button">Add developer</a>
        </div>
    </div>
    <div class="row  mb-3">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Developer Name</th>
                        <th scope="col">Project Name</th>
                        <th scope="col">Developer Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $c = 0;
                    while ($row = tep_db_fetch_array($p_result)) {
                    ?>
                        <tr>
                            <th scope="row"><?php echo ++$c; ?> </th>
                            <td><?php echo $row["developer_name"]; ?></td>
                            <td><?php echo $row["project_name"]; ?></td>
                            <td><?php echo ($row["developer_status"] == 1 ? 'Active' : 'Deactive'); ?></td>
                            <td><a href="developer_edit.php?action=editdeveloper&developer_id=<?php echo $row['developer_id']; ?>"><i class="far fa-edit"></i></a> |
                                <a onclick='deactiveConfirmDeveloper("<?php echo $row['developer_id']; ?>", "<?php echo $row['developer_status']; ?>")' href="javascript:void(0)"><i class="fa fa-ban" aria-hidden="true"></i></a>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>

<?php include_once('includes/footer.php'); ?>