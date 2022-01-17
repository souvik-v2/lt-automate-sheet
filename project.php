<?php
require('includes/application_top.php');
include('includes/header.php');

if (isset($action) && ($action == 'deleteproject')) {
    try {
        $result = $con->run("DELETE FROM project WHERE project_id = ?", array($_GET['project_id']));
        $result_sprint = $con->run("DELETE FROM sprint_data WHERE project_id = ?", array($_GET['project_id']));
        $con->run("DELETE FROM sprint_report WHERE project_id = ?", array($_GET['project_id']));
        $_SESSION['success'] = "Project, sprint, report data deleted successfully!!!";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    
    tep_redirect('project.php');
}

$p_result = $con->run("SELECT `project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status` FROM project ORDER BY project_id DESC");
?>
<div class="container-fluid">
    <div class="row mt-3 mb-3">
        <div class="col">
        <h4>Project List</h4>
        </div>
    </div>
    <div class="row text-right">
        <div class="col-12 text-right">
            <a href="project_edit.php" class="btn" role="button">Add Project</a>
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
                        <th scope="col">Project Manager/Lead</th>
                        <th scope="col">Client Poc</th>
                        <th scope="col">Client Feedback</th>
                        <th scope="col">Team Allocation</th>
                        <th scope="col">Offshore Team Allocated</th>
                        <th scope="col">Offshore Team Billable</th>
                        <th scope="col">Onsite Team Allocated</th>
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
                            <td><?php echo date('d-M-Y', strtotime($row["status_date"])); ?></td>
                            <td><?php echo ucfirst($row["overall_status"]); ?></td>
                            <td><a href="project_edit.php?action=editproject&project_id=<?php echo $row['project_id']; ?>"><i class="far fa-edit"></i></a> |
                                <a onclick='deleteConfirmProject("<?php echo $row['project_id']; ?>")' href="javascript:void(0)"><i class="far fa-trash-alt"></i></a>
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
<?php include_once('includes/footer.php'); ?>