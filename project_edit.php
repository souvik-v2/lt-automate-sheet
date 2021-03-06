<?php
require('includes/application_top.php');
include('includes/header.php');

if (isset($action) && ($action == 'newproject')) {
	//case: new
	$developers = implode(', ', $_POST['developers']);

	$sql_data_array = array(
		'project_name' =>  $_POST['project_name'],
		'delivery_manager' =>  $_POST['delivery_manager'],
		'project_manager' =>  $_POST['project_manager'],
		'client_poc' =>  $_POST['client_poc'],
		'client_feedback' =>  $_POST['client_feedback'],
		'team_allocation' => 0,
		'offshore_team_allocated' =>  (int) $_POST['offshore_team_allocated'],
		'offshore_team_billable' =>  (int) $_POST['offshore_team_billable'],
		'onsite_team_allocated' =>  (int) $_POST['onsite_team_allocated'],
		'onsite_team_billable' => 0,
		'status_date' =>  'now()',
		'overall_status' => $_POST['overall_status'],
		'is_sprint' =>  (int) $_POST['is_sprint'],
		'developers' => trim($developers)
	);
	//
	try {
		tep_db_perform($con, 'project', $sql_data_array);
		//$last_id = $con->lastInsertId();
		$_SESSION['success'] = "Project created successfully!!!";
	} catch (Exception $e) {
		$_SESSION['error'] = $e->getMessage();
	}
	tep_redirect('project.php');
}

if (isset($action) && ($action == 'editproject')) {
	$usql = "SELECT `project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status`, is_sprint, developers FROM project WHERE project_id = ?";
	$uresult = $con->run($usql, array($_GET['project_id']));
	$data = tep_db_fetch_array($uresult);
}
if (isset($action) && ($action == 'updateproject')) {

	// $usql = "SELECT `project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status`, is_sprint, developers FROM project WHERE project_id = ?";
	// $uresult = $con->run($usql, array($_POST['project_id']));
	// $data = tep_db_fetch_array($uresult);
	//echo '<pre>'; print_r($_POST);die();
	$developers = implode(', ', $_POST['developers']);
	//$developers = $data['developers'];
	//echo($developers);die;
	$sql_data_array = array(
		'project_name' =>  $_POST['project_name'],
		'delivery_manager' =>  $_POST['delivery_manager'],
		'project_manager' =>  $_POST['project_manager'],
		'client_poc' =>  $_POST['client_poc'],
		'client_feedback' =>  $_POST['client_feedback'],
		'team_allocation' => 0,
		'offshore_team_allocated' =>  (int) $_POST['offshore_team_allocated'],
		'offshore_team_billable' =>  (int) $_POST['offshore_team_billable'],
		'onsite_team_allocated' =>  (int) $_POST['onsite_team_allocated'],
		'onsite_team_billable' => 0,
		'overall_status' =>  $_POST['overall_status'],
		'is_sprint' =>  (int) $_POST['is_sprint'],
		'developers' => trim($developers)
	);
	try {
		tep_db_perform($con, 'project', $sql_data_array, 'update', array('project_id', $_POST['project_id']));
		$_SESSION['success'] = "Project updated successfully!!!";
	} catch (Exception $e) {
		$_SESSION['error'] = $e->getMessage();
	}

	tep_redirect('project.php');
}
$developer_query = $con->run("SELECT developer_id, developer_name, developer_status FROM developer ORDER BY developer_name");

?>
<div class="container contact">
	<?php if (isset($action) && ($action == 'editproject')) { ?>
		<div class="row mt-3">
			<div class="col-md-3">
				<div class="contact-info">
					<img src="images/v-2-logo.svg" alt="image" />
					<h2>Edit Project</h2>
				</div>
			</div>
			<div class="col-md-9">
				<form method="POST" action="project_edit.php?action=updateproject" name="automate-sheet">
					<div class="contact-form">
						<div class="form-group">
							<label class="control-label col-sm-6" for="fname">Project Name:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" value="<?php echo $data['project_name'] ?>" name="project_name" />
								<input type="hidden" value="<?php echo $data['project_id'] ?>" name="project_id" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-6" for="lname">Delivery Manager:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" placeholder="Delivery Manager" name="delivery_manager" value="<?php echo $data['delivery_manager'] ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-6" for="email">Project Manager/Lead:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" placeholder="Project Manager" name="project_manager" value="<?php echo $data['project_manager'] ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-6" for="comment">Client Poc:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" placeholder="Client Poc" name="client_poc" value="<?php echo $data['client_poc'] ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-6" for="comment">Client Feedback:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" placeholder="Client Feedback" name="client_feedback" value="<?php echo $data['client_feedback'] ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-6" for="comment">Offshore Team Allocated:</label>
							<div class="col-sm-10">
								<input type="number" class="form-control" placeholder="Offshore Team Allocated" name="offshore_team_allocated" value="<?php echo $data['offshore_team_allocated'] ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-6" for="comment">Offshore Team Billable:</label>
							<div class="col-sm-10">
								<input type="number" class="form-control" placeholder="Offshore Team Billable" name="offshore_team_billable" value="<?php echo $data['offshore_team_billable'] ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-6" for="comment">Onsite Team Allocated:</label>
							<div class="col-sm-10">
								<input type="number" class="form-control" placeholder="Onsite Team Allocated" name="onsite_team_allocated" value="<?php echo $data['onsite_team_allocated'] ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-6" for="comment">Overall Status:</label>
							<div class="col-sm-10">
								<select name="overall_status" class="form-control">
									<option value="green" <?php echo ($data['overall_status'] == 'green' ? 'selected' : ''); ?>>Green</option>
									<option value="red" <?php echo ($data['overall_status'] == 'red' ? 'selected' : ''); ?>>Red</option>
									<option value="amber" <?php echo ($data['overall_status'] == 'amber' ? 'selected' : ''); ?>>Amber</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-6" for="comment">Board:</label>
							<div class="col-sm-10">
								<select name="is_sprint" class="form-control">
									<option value="0" <?php echo ($data['is_sprint'] == '0' ? 'selected' : ''); ?>>Sprint</option>
									<option value="1" <?php echo ($data['is_sprint'] == '1' ? 'selected' : ''); ?>>Kanban Board</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-6" for="comment">Developers:</label>
							<div class="col-sm-10">
								<select name="developers[]" class="form-control select2" multiple="multiple">
									<?php
									$dev = explode(', ', $data['developers']);
									while ($developer = tep_db_fetch_array($developer_query)) {
									?>
										<option value="<?php echo $developer['developer_id']; ?>" <?php echo (in_array($developer['developer_id'], $dev) ? 'selected' : ''); ?> <?php echo ($developer['developer_status'] == 0 ? 'disabled' : ''); ?>><?php echo $developer['developer_name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-default" name="automate">Update Project</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	<?php } else { ?>
		<div class="row mt-3">
			<div class="col-md-3">
				<div class="contact-info">
					<img src="images/v-2-logo.svg" alt="image" />
					<h2>Add Project</h2>
				</div>
			</div>
			<div class="col-md-9">
				<form method="POST" action="project_edit.php?action=newproject" name="automate-sheet" />
				<div class="contact-form">
					<div class="form-group">
						<label class="control-label col-sm-6" for="fname">Project Name:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="project_name" required />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-6" for="lname">Delivery Manager:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" placeholder="Delivery Manager" name="delivery_manager" required />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-6" for="email">Project Manager/Lead:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" placeholder="Project Manager" name="project_manager" required />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-6" for="comment">Client Poc:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" placeholder="Client Poc" name="client_poc" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-6" for="comment">Client Feedback:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" placeholder="Client Feedback" name="client_feedback" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-6" for="comment">Offshore Team Allocated:</label>
						<div class="col-sm-10">
							<input type="number" class="form-control" placeholder="Offshore Team Allocated" name="offshore_team_allocated" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-6" for="comment">Offshore Team Billable:</label>
						<div class="col-sm-10">
							<input type="number" class="form-control" placeholder="Offshore Team Billable" name="offshore_team_billable" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-6" for="comment">Onsite Team Allocated:</label>
						<div class="col-sm-10">
							<input type="number" class="form-control" placeholder="Onsite Team Allocated" name="onsite_team_allocated" />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-6" for="comment">Overall Status:</label>
						<div class="col-sm-10">
							<select name="overall_status" class="form-control">
								<option value="green">Green</option>
								<option value="red">Red</option>
								<option value="amber">Amber</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-6" for="comment">Board:</label>
						<div class="col-sm-10">
							<select name="is_sprint" class="form-control">
								<option value="0">Sprint</option>
								<option value="1">Kanban Board</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-10">
						<select name="developers[]" class="form-control select2" multiple="multiple">
									<?php
									while ($developer = tep_db_fetch_array($developer_query)) {
									?>
										<option value="<?php echo $developer['developer_id']; ?>" <?php echo ($developer['developer_status'] == 0 ? 'disabled' : ''); ?>><?php echo $developer['developer_name']; ?></option>
									<?php } ?>
								</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-default" name="automate">Add Project</button>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	<?php } ?>
</div>
<script src="js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
<script type="text/javascript">
	$('.select2').multiselect({
		nonSelectedText: 'Select Developer',
		includeSelectAllOption: false,
		buttonWidth: '100%',
		enableFiltering: false
	});
</script>
<style>
	.multiselect-container {
        width: 100% !important;
    }
</style>
<?php include_once('includes/footer.php'); ?>