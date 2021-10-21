<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.php');
}
include('includes/header.php');

if (isset($_GET['action']) && ($_GET['action'] === 'deleteproject')) {
    $result = tep_db_query("DELETE FROM project WHERE project_id = '" . $_GET['project_id'] . "'");
    $_SESSION['success'] = "Project deleted successfully!!!";
    tep_redirect('project.php');
}

$p_result = tep_db_query("SELECT `project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status` FROM project order by project_id desc");
?>
<div class="container-fluid">
    <div class="row mt-3 mb-3">
        <div class="col">
        <h4>Project List</h4>
        </div>
    </div>
    
    <div class="row  mb-3">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Project Name</th>
                        <th scope="col">Delivery Manager</th>
                        <th scope="col">Project Manager</th>
                        <th scope="col">Client Poc</th>
                        <th scope="col">Client Feedback</th>
                        <th scope="col">Team Allocation</th>
                        <th scope="col">Offshore Team Allocated</th>
                        <th scope="col">Offshore Team Billable</th>
                        <th scope="col">Onsite Team Allocated</th>
                        <th scope="col">Onsite Team Billable</th>
                        <th scope="col">Status Date</th>
                        <th scope="col">Overall Status</th>
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
                            <td><?php echo $row["project_name"]; ?></td>
                            <td><?php echo $row["delivery_manager"]; ?></td>
                            <td><?php echo $row["project_manager"]; ?></td>
                            <td><?php echo $row["client_poc"]; ?></td>
                            <td><?php echo $row["client_feedback"]; ?></td>
                            <td><?php echo $row["team_allocation"]; ?></td>
                            <td><?php echo $row["offshore_team_allocated"]; ?></td>
                            <td><?php echo $row["offshore_team_billable"]; ?></td>
                            <td><?php echo $row["onsite_team_allocated"]; ?></td>
                            <td><?php echo $row["onsite_team_billable"]; ?></td>
                            <td><?php echo $row["status_date"]; ?></td>
                            <td><?php echo $row["overall_status"]; ?></td>
                            <td><a href="project_edit.php?action=editproject&project_id=<?php echo $row['project_id']; ?>"><i class="far fa-edit"></i></a> |
                                <a onclick='deleteConfirm("<?php echo $row['project_id']; ?>")' href="javascript:void(0)"><i class="far fa-trash-alt"></i></a>
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
<script>
    function deleteConfirm(id) {
        //console.log(id);
        if (confirm("Are you sure to delete this record?")) {
            location.href = "project.php?action=deleteproject&project_id=" + id;
        }
    }
</script>
<?php include_once('includes/footer.php'); ?>