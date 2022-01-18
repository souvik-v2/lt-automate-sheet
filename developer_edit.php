<?php
require('includes/application_top.php');
include('includes/header.php');

if (isset($action) && ($action == 'newdeveloper')) {
	//case: new
	//$developers = implode(', ', $_POST['developers']);
	$dev_check = $con->run("SELECT developer_name FROM developer WHERE developer_name = ?", array($_POST['developer_name']));
	if ($dev_check->rowCount() > 0) {
		$_SESSION['error'] = "Developer name already exists!";
		tep_redirect('developer_edit.php');
	} else {
		$sql_data_array = array(
			'developer_name' =>  $_POST['developer_name'],
			'project_id' =>  $_POST['project_id'],
			'developer_status' =>  $_POST['developer_status']
		);
		//
		try {
			tep_db_perform($con, 'developer', $sql_data_array);
			$_SESSION['success'] = "Developer added successfully!!!";
		} catch (Exception $e) {
			$_SESSION['error'] = $e->getMessage();
		}
		tep_redirect('developer.php');
	}
}

if (isset($action) && ($action == 'editdeveloper')) {
	$usql = "SELECT developer_id, developer_name, project_id, developer_status FROM developer WHERE developer_id = ?";
	$uresult = $con->run($usql, array($_GET['developer_id']));
	$data = tep_db_fetch_array($uresult);

	$assigne_flag_query = $con->run("SELECT developers from project WHERE FIND_IN_SET(?, REPLACE(developers, ', ', ',')) <> 0  AND project_id=?", array($_GET['developer_id'], $data['project_id']));
	$assigne_flag = (tep_db_num_rows($assigne_flag_query) > 0 ? 'disabled' : '');
}
if (isset($action) && ($action == 'updatedeveloper')) {

	$sql_data_array = array(
		'developer_name' =>  $_POST['developer_name'],
		'project_id' =>  $_POST['project_id'],
		'developer_status' =>  $_POST['developer_status'],
	);
	try {
		tep_db_perform($con, 'developer', $sql_data_array, 'update', array('developer_id', $_POST['developer_id']));
		$_SESSION['success'] = "Developer updated successfully!!!";
	} catch (Exception $e) {
		$_SESSION['error'] = $e->getMessage();
	}

	tep_redirect('developer.php');
}
//Project dropdown
$psql = "SELECT project_id, project_name FROM project ORDER BY project_id DESC";
$presult = $con->run($psql);
?>
<div class="container contact">
	<?php if (isset($action) && ($action == 'editdeveloper')) { ?>
		<div class="row mt-3">
			<div class="col-md-3">
				<div class="contact-info">
					<img src="images/v-2-logo.svg" alt="image" />
					<h2>Edit Developer</h2>
				</div>
			</div>
			<div class="col-md-9">
				<form method="POST" action="developer_edit.php?action=updatedeveloper" name="automate-sheet">
					<div class="contact-form">
						<div class="form-group">
							<label class="control-label col-sm-6" for="fname">Developer Name:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" value="<?php echo $data['developer_name'] ?>" name="developer_name" />
								<input type="hidden" value="<?php echo $data['developer_id'] ?>" name="developer_id" />
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-6" for="comment">Project Name:</label>
							<div class="col-sm-10 is-disabled">
								<select name="project_id" class="form-control" <?php echo $assigne_flag; ?>>
									<?php
									while ($pdata = tep_db_fetch_array($presult)) {
									?>
										<option value="<?php echo $pdata['project_id']; ?>" <?php echo ($data['project_id'] == $pdata['project_id'] ? 'selected' : ''); ?>><?php echo $pdata['project_name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-sm-6" for="comment">Developer Status:</label>
							<div class="col-sm-10">
								<select name="developer_status" class="form-control">
									<option value="1" <?php echo ($data['developer_status'] == '1' ? 'selected' : ''); ?>>Active</option>
									<option value="0" <?php echo ($data['developer_status'] == '0' ? 'selected' : ''); ?>>Deactive</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" class="btn btn-default" name="automate">Update Developer</button>
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
				<form method="POST" action="developer_edit.php?action=newdeveloper" name="automate-sheet" />
				<div class="contact-form">
					<div class="form-group">
						<label class="control-label col-sm-6" for="fname">Developer Name:</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="developer_name" required />
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-6" for="comment">Project Name:</label>
						<div class="col-sm-10">
							<select name="project_id" class="form-control">
								<?php
								while ($pdata = tep_db_fetch_array($presult)) {
								?>
									<option value="<?php echo $pdata['project_id']; ?>"><?php echo $pdata['project_name']; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-6" for="comment">Developer Status:</label>
						<div class="col-sm-10">
							<select name="developer_status" class="form-control">
								<option value="1">Active</option>
								<option value="0">Deactive</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-default" name="automate">Add Developer</button>
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
	<?php } ?>
</div>
<?php include_once('includes/footer.php'); ?>