<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
	tep_redirect('index.php');
}
include('includes/header.php');

if (isset($_GET['action']) && ($_GET['action'] === 'newproject')) {
	//case: new
	//echo "<pre>"; print_r($_POST['developers']); //die();
	$developers = implode(', ', $_POST['developers']);

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
		'overall_status' =>  tep_db_input($_POST['overall_status']),
		'is_sprint' =>  tep_db_input($_POST['is_sprint']),
		'developers' => strtolower(trim($developers))
	);
	//
	tep_db_perform('project', $sql_data_array);
	$_SESSION['success'] = "Record created successfully!!!";
	tep_redirect('project.php');
}

if (isset($_GET['action']) && ($_GET['action'] === 'editproject')) {
	$usql = "SELECT `project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status`, is_sprint, developers FROM project WHERE project_id = '" . tep_db_input($_GET['project_id']) . "'";
	$uresult = tep_db_query($usql);
	$data = tep_db_fetch_array($uresult);
}
if (isset($_GET['action']) && ($_GET['action'] === 'updateproject')) {
	$developers = implode(', ', $_POST['developers']);
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
		'overall_status' =>  tep_db_input($_POST['overall_status']),
		'is_sprint' =>  tep_db_input($_POST['is_sprint']),
		'developers' => strtolower(trim($developers))
	);
	tep_db_perform('project', $sql_data_array, 'update', 'project_id=' . $_POST['project_id']);

	$_SESSION['success'] = "Project updated successfully!!!";
	tep_redirect('project.php');
}
?>
<div class="container contact">
	<?php if (isset($_GET['action']) && ($_GET['action'] === 'editproject')) { ?>
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
							<label class="control-label col-sm-6" for="comment">Team Allocation:</label>
							<div class="col-sm-10">
								<input type="number" class="form-control" placeholder="Team Allocation" name="team_allocation" value="<?php echo $data['team_allocation'] ?>" />
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
							<label class="control-label col-sm-4" for="comment">Developers:</label>
							<div class="col-10 controls">
								<div id="field">
									<?php
									$devs = explode(', ', $data['developers']);
									if (count($devs) > 0) {
									}
									?>
									<input type="text" class="form-control developers" placeholder="Developers" name="developers[]" id="field1" value="<?php echo $devs[0]; ?>" />
									<button id="b1" class="btn add-more" type="button">+</button>
								</div>
							</div>
							<div id="fieldList" class="col-8 mt-4">
								<?php
								if (count($devs) > 1) {
									for ($i = 1; $i < count($devs); $i++) {
										$r = rand(2, 99);
								?>
										<div class="rw" id="rmv_<?= $r; ?>">
											<input type="text" class="form-control developers mt-2" placeholder="Developers" name="developers[]" id="field<?= $r; ?>" value="<?= $devs[$i]; ?>" />
											<span class="rm" id="d_<?= $r; ?>" onclick="rmInput('<?= $r; ?>')">-</span>
										</div>
								<?php
									}
								}
								?>
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
						<label class="control-label col-sm-6" for="comment">Team Allocation:</label>
						<div class="col-sm-10">
							<input type="number" class="form-control" placeholder="Team Allocation" name="team_allocation" />
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
						<label class="control-label col-sm-4" for="comment">Developers:</label>
						<div class="col-10 controls">
							<div id="field">
								<input type="text" class="form-control developers" placeholder="Developers" name="developers[]" id="field1" />
								<button id="b1" class="btn add-more" type="button">+</button>
							</div>
						</div>
						<div id="fieldList" class="col-8 mt-4"></div>
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
<?php include_once('includes/footer.php'); ?>
<script>
	$(function() {
		$("#b1").click(function(e) {
			e.preventDefault();
			var r = Math.floor(Math.random() * 99) + 1;
			$("#fieldList").append('<div class="rw" id="rmv_' + r + '"><input type="text" class="form-control developers mt-2" placeholder="Developers" name="developers[]" id="field' + r + '" /><span class="rm" id="d_' + r + '" onclick="rmInput(' + r + ')">-</span></div>');
		});

	});

	function rmInput(r) {
		$('#rmv_' + r).remove();
	}
</script>
<style>
	#field {
		display: flex;
		justify-content: space-between;
	}

	#field input {
		max-height: 42px;
	}

	#b1 {
		margin: 0 5px;
	}

	.rw {
		display: flex;
		justify-content: space-between;
	}

	span.rm {
		font-size: 30px;
		color: red;
		margin-left: 8px;
		cursor: pointer;
	}
</style>