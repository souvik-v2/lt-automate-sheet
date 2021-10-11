<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.html');
}
include('includes/header.php');

if (isset($_GET['action']) && ($_GET['action'] === 'newproject')) {
    //case: new
    //echo "<pre>"; print_r($_POST);
    $sql_data_array = array(
        'project_name' =>  tep_db_input($_POST['project_name']),
        'delivery_manager' =>  tep_db_input($_POST['delivery_manager']),
        'project_manager' =>  tep_db_input($_POST['project_manager']),
        'client_poc' =>  tep_db_input($_POST['client_poc']),
        'client_feedback' =>  tep_db_input($_POST['client_feedback']),
        'team_allocation' =>  tep_db_input($_POST['team_allocation']),
        'offshore_team_allocated' =>  tep_db_input($_POST['offshore_team_allocated']),
        'offshore_team_billable' =>  tep_db_input($_POST['offshore_team_billable']),
        'onsite_team_allocated' =>  tep_db_input($_POST['onsite_team_allocated']),
        'onsite_team_billable' =>  tep_db_input($_POST['onsite_team_billable']),
        'status_date' =>  'now()',
        'overall_status' =>  tep_db_input($_POST['overall_status'])
    );
    //
    tep_db_perform('project', $sql_data_array);
    $_SESSION['success'] = "Record created successfully!!!";
    tep_redirect('project.php');
}

if (isset($_GET['action']) && ($_GET['action'] === 'editproject')) {
    $usql = "SELECT `project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status` FROM project WHERE project_id = '" . $_GET['project_id'] . "'";
    $uresult = tep_db_query($usql);
    $data = tep_db_fetch_array($uresult);
}
if (isset($_GET['action']) && ($_GET['action'] === 'updateproject')) {
    $sql_data_array = array(
        'project_name' =>  tep_db_input($_POST['project_name']),
        'delivery_manager' =>  tep_db_input($_POST['delivery_manager']),
        'project_manager' =>  tep_db_input($_POST['project_manager']),
        'client_poc' =>  tep_db_input($_POST['client_poc']),
        'client_feedback' =>  tep_db_input($_POST['client_feedback']),
        'team_allocation' =>  tep_db_input($_POST['team_allocation']),
        'offshore_team_allocated' =>  tep_db_input($_POST['offshore_team_allocated']),
        'offshore_team_billable' =>  tep_db_input($_POST['offshore_team_billable']),
        'onsite_team_allocated' =>  tep_db_input($_POST['onsite_team_allocated']),
        'onsite_team_billable' =>  tep_db_input($_POST['onsite_team_billable']),
        'status_date' =>  tep_db_input($_POST['status_date']),
        'overall_status' =>  tep_db_input($_POST['overall_status'])
    );
    tep_db_perform('project', $sql_data_array, 'update', 'project_id=' . $_POST['project_id']);

    $_SESSION['success'] = "Project updated successfully!!!";
    tep_redirect('project.php');
}
?>
<div class="content container">
    <?php if (isset($_GET['action']) && ($_GET['action'] === 'editproject')) { ?>
        <h2>Edit Project</h2>
        <div class="row">
            <div class="col-12">
                <form method="POST" action="project_edit.php?action=updateproject" name="automate-sheet">
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Project Name</label>
                        <input type="text" class="form-control" value="<?php echo $data['project_name'] ?>" name="project_name" />
                        <input type="hidden" value="<?php echo $data['project_id'] ?>" name="project_id" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Delivery Manager</label>
                        <input type="text" class="form-control" placeholder="Delivery Manager" name="delivery_manager" value="<?php echo $data['delivery_manager'] ?>" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Project Manager</label>
                        <input type="text" class="form-control" placeholder="Project Manager" name="project_manager" value="<?php echo $data['project_manager'] ?>" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Client Poc</label>
                        <input type="text" class="form-control" placeholder="Client Poc" name="client_poc" value="<?php echo $data['client_poc'] ?>" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Client Feedback</label>
                        <input type="text" class="form-control" placeholder="Client Feedback" name="client_feedback" value="<?php echo $data['client_feedback'] ?>" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Team Allocation</label>
                        <input type="number" class="form-control" placeholder="Team Allocation" name="team_allocation" value="<?php echo $data['team_allocation'] ?>" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Offshore Team Allocated</label>
                        <input type="number" class="form-control" placeholder="Offshore Team Allocated" name="offshore_team_allocated" value="<?php echo $data['offshore_team_allocated'] ?>" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Offshore Team Billable</label>
                        <input type="number" class="form-control" placeholder="Offshore Team Billable" name="offshore_team_billable" value="<?php echo $data['offshore_team_billable'] ?>" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Onsite Team Allocated</label>
                        <input type="number" class="form-control" placeholder="Onsite Team Allocated" name="onsite_team_allocated" value="<?php echo $data['onsite_team_allocated'] ?>" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Onsite Team Billable</label>
                        <input type="number" class="form-control" placeholder="Onsite Team Billable" name="onsite_team_billable" value="<?php echo $data['onsite_team_billable'] ?>" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Overall Status</label>
                        <select name="overall_status" class="form-control">
                            <option value="green" <?php echo ($data['overall_status'] == 'green' ? 'selected' : ''); ?>>Green</option>
                            <option value="red" <?php echo ($data['overall_status'] == 'red' ? 'selected' : ''); ?>>Red</option>
                            <option value="amber" <?php echo ($data['overall_status'] == 'amber' ? 'selected' : ''); ?>>Amber</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit" name="automate">Update Project</button>
                </form>
            </div>
        </div>
    <?php } else { ?>
        <h2>Add Project</h2>
        <div class="row">
            <div class="col-12">
                <form method="POST" action="project_edit.php?action=newproject" name="automate-sheet" />
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Project Name</label>
                        <input type="text" class="form-control" placeholder="Project Name" name="project_name" required />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Delivery Manager</label>
                        <input type="text" class="form-control" placeholder="Delivery Manager" name="delivery_manager" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Project Manager</label>
                        <input type="text" class="form-control" placeholder="Project Manager" name="project_manager" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Client Poc</label>
                        <input type="text" class="form-control" placeholder="Client Poc" name="client_poc" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Client Feedback</label>
                        <input type="text" class="form-control" placeholder="Client Feedback" name="client_feedback" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Team Allocation</label>
                        <input type="number" class="form-control" placeholder="Team Allocation" name="team_allocation" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Offshore Team Allocated</label>
                        <input type="number" class="form-control" placeholder="Offshore Team Allocated" name="offshore_team_allocated" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Offshore Team Billable</label>
                        <input type="number" class="form-control" placeholder="Offshore Team Billable" name="offshore_team_billable" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Onsite Team Allocated</label>
                        <input type="number" class="form-control" placeholder="Onsite Team Allocated" name="onsite_team_allocated" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Onsite Team Billable</label>
                        <input type="number" class="form-control" placeholder="Onsite Team Billable" name="onsite_team_billable" />
                    </div>
                    <div class="form-group">
                        <label for="formGroupExampleInput2">Overall Status</label>
                        <select name="overall_status" class="form-control">
                            <option value="green">Green</option>
                            <option value="red">Red</option>
                            <option value="amber">Amber</option>
                        </select>
                    </div>
                    <button class="btn btn-primary" type="submit" name="automate">Add Project</button>
                </form>
            </div>
        </div>
    <?php } ?>
</div>
<?php include_once('includes/footer.php'); ?>