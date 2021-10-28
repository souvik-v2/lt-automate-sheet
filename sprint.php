<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.php');
}
include('includes/header.php');

$p_result = tep_db_query("SELECT project_id, project_name FROM project order by project_id desc");
$option_list  = '';
if (tep_db_num_rows($p_result) > 0) {
    while ($p_row = tep_db_fetch_array($p_result)) {
        $selected = (isset($_GET['action']) && ($_GET['action'] === 'view') &&  ($p_row['project_id'] === $_POST['project_id']) ? 'selected' : '');
        $option_list .= '<option value="' . $p_row['project_id'] . '"' . $selected . '>' . $p_row['project_name'] . '</option>';
    }
}

if (isset($_GET['action']) && ($_GET['action'] === 'newsprint')) {
    //case: new
    //echo "<pre>"; print_r($_POST);
    $dev_sql = tep_db_query("SELECT developers FROM project WHERE project_id = '" . tep_db_input($_POST['project_id']) . "'");
    $dev_result = tep_db_fetch_array($dev_sql);
    $dev_name_array = explode(', ', $dev_result['developers']);

    $csv = array();
    $csv_rw = array();
    $total_story_count = 0;
    $total_v2_score = 0;
    $total_lt_score = 0;
    $total_v2_carryover = 0;
    $total_lt_carryover = 0;
    $actual_delivered = 0;
    $rework = 0;
    $qa_passed = 0;
    $lt_reoponed_sp = 0;
    $v2_reopen_percentage = 0;
    $lt_reopen_percentage = 0;
    $v2_carryover_percentage = 0;
    $lt_carryover_percentage = 0;
    $planned_vs_completed_ratio = 0;
    //story point calculation
    if ($_FILES['csv']['error'] == 0) {

        $name = $_FILES['csv']['name'];
        $tmp = explode('.', $name);
        $extention = end($tmp);

        $ext = strtolower($extention);
        $type = $_FILES['csv']['type'];
        $tmpName = $_FILES['csv']['tmp_name'];

        // check the file is a csv
        if ($ext === 'csv') {
            if (($handle = fopen($tmpName, 'r')) !== FALSE) {
                //necessary if a large csv file
                set_time_limit(0);
                $in = 0;
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if ($in === 0) {
                        $csv_index[] = $data;
                    }
                    $csv[] = $data;
                    $in++;
                }
                fclose($handle);
            }
        }
        if (count($csv) > 0) {
            for ($i = 0; $i < count($csv[0]); $i++) {
                if (strpos($csv[0][$i], 'Story Points') !== false) {
                    $point_key = $i;
                }
                if (strpos($csv[0][$i], 'Sprint') !== false) {
                    $sprint_key[] = $i;
                }
                if (strpos($csv[0][$i], 'Assignee') !== false) {
                    $dev_key = $i;
                }
            }
            $story = array();
            for ($i = 1; $i < count($csv); $i++) {
                for ($j = 0; $j < count($csv[$i]); $j++) {
                    if ($point_key && $j == 0) {
                        $story['story'][] = $csv[$i][$point_key];
                    }
                    if (isset($sprint_key) && $j == 0) {
                        $story['sprint'][] = $csv[$i][$sprint_key[1]];
                    }
                    if ($dev_key && $j == 0) {
                        $story['developers'][] = $csv[$i][$dev_key];
                    }
                }
            }
            $story_point_array = $story['story'];
            $sprint_point_array = $story['sprint'];
            $developers_point_array = $story['developers'];

            foreach ($developers_point_array as $k => $v) {
                if (in_array($v, $dev_name_array)) {
                    $v2_score[] = $story_point_array[$k];
                    $v2_carryover[] = ((count($sprint_point_array) > 0) && ($sprint_point_array[$k] != '')  ? $story_point_array[$k] : 0);
                } else {
                    $lt_score[] = $story_point_array[$k];
                    $lt_carryover[] = ((count($sprint_point_array) > 0) && ($sprint_point_array[$k] != '') ? $story_point_array[$k] : 0);
                }
            }
            $total_story_count = array_sum($story_point_array);
            $total_v2_score = array_sum($v2_score);
            $total_v2_carryover = array_sum($v2_carryover);
            $total_lt_score = array_sum($lt_score);
            $total_lt_carryover = array_sum($lt_carryover);
            $actual_delivered = $total_story_count - ($total_v2_carryover + $total_lt_carryover);
        }
    } else {
        $_SESSION['error'] = '"File upload error!!';
    }
    //rework calculation
    if ($_FILES['csv_rw']['error'] == 0) {

        $name = $_FILES['csv_rw']['name'];
        $tmp = explode('.', $name);
        $extention = end($tmp);

        $ext = strtolower($extention);
        $type = $_FILES['csv_rw']['type'];
        $tmpName = $_FILES['csv_rw']['tmp_name'];

        // check the file is a csv
        if ($ext === 'csv') {
            if (($handle = fopen($tmpName, 'r')) !== FALSE) {
                //necessary if a large csv file
                set_time_limit(0);
                $in = 0;
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if ($in === 0) {
                        $csv_rw_index[] = $data;
                    }
                    $csv_rw[] = $data;
                    $in++;
                }
                fclose($handle);
            }
        }
        if (count($csv_rw) > 0) {
            for ($i = 0; $i < count($csv_rw[0]); $i++) {
                if (strpos($csv_rw[0][$i], 'Story Points') !== false) {
                    $rw_point_key = $i;
                }
                if (strpos($csv_rw[0][$i], 'Sprint') !== false) {
                    $rw_sprint_key[] = $i;
                }
                if (strpos($csv_rw[0][$i], 'Assignee') !== false) {
                    $rw_dev_key = $i;
                }
            }
            $rw_story = array();
            for ($i = 1; $i < count($csv_rw); $i++) {
                for ($j = 0; $j < count($csv_rw[$i]); $j++) {
                    if ($rw_point_key && $j == 0) {
                        $rw_story['rw_story'][] = $csv_rw[$i][$rw_point_key];
                    }
                    if (isset($rw_sprint_key) && $j == 0) {
                        $rw_story['rw_sprint'][] = $csv_rw[$i][$rw_sprint_key[count($rw_sprint_key) - 1]];
                    }
                    if ($rw_dev_key && $j == 0) {
                        $rw_story['rw_developers'][] = $csv_rw[$i][$rw_dev_key];
                    }
                }
            }
            $rw_story_point_array = $rw_story['rw_story'];
            $rw_sprint_point_array = $rw_story['rw_sprint'];
            $rw_developers_point_array = $rw_story['rw_developers'];
            foreach ($rw_developers_point_array as $k => $v) {
                if (in_array($v, $dev_name_array)) {
                    $rw_v2_score[] = (count($rw_sprint_point_array) > 0 && ($rw_sprint_point_array[$k] != '') ? $rw_story_point_array[$k]: 0);
                } else {
                    $rw_lt_score[] = (count($rw_sprint_point_array) > 0 && ($rw_sprint_point_array[$k] != '') ? $rw_story_point_array[$k]: 0);
                }
            }
            $rework = array_sum($rw_v2_score);
            $lt_reoponed_sp = array_sum($rw_lt_score);
        }
    } else {
        $_SESSION['error'] = '"File upload error!!';
    }
    //insert
    $sql_data_array = array(
        'project_id' =>  $_POST['project_id'],
        'sprint_name' =>  $_POST['sprint_name'],
        'planned_story_point' =>  $total_story_count,
        'actual_delivered' =>  $actual_delivered,
        'v2_delivered' =>  $total_v2_score,
        'lt_delivered' =>  $total_lt_score,
        'rework' =>  $rework,
        'lt_reoponed_sp' =>  $lt_reoponed_sp,
        'v2_carryover' =>  $total_v2_carryover,
        'lt_carryover' =>  $total_lt_carryover,
        'qa_passed' => ($total_v2_score - $rework),
        'v2_reopen_percentage' =>  round(($rework / $total_v2_score) * 100),
        'lt_reopen_percentage' =>  round(($lt_reoponed_sp / $total_lt_score) * 100),
        'v2_carryover_percentage' =>  round(($total_v2_carryover / $total_v2_score) * 100),
        'lt_carryover_percentage' =>  round(($total_lt_carryover / $total_lt_score) * 100),
        'planned_vs_completed_ratio' =>  round(($actual_delivered / $total_story_count) * 100),
        'created_date' => 'now()'
    );
    //echo '<pre>'; print_r($sql_data_array); die();
    //
    tep_db_perform('sprint_data', $sql_data_array);
    $_SESSION['success'] = "Record created successfully!!!";
    tep_redirect('sprint_view.php');
}
?>
<div class="container contact mt-3">
	<div class="row">
		<div class="col-md-3">
			<div class="contact-info">
				<img src="images/v-2-logo.svg" alt="image"/>
				<h2>Add Sprint</h2>
			</div>
		</div>
		<div class="col-md-9">
        <form method="POST" action="sprint.php?action=newsprint" name="automate-sheet" enctype="multipart/form-data">
			<div class="contact-form">
				<div class="form-group">
				  <label class="control-label col-sm-4" for="fname">Project Name:</label>
				  <div class="col-sm-10">          
                    <select name="project_id" class="form-control" onchange="getID(this.value)" required>
                        <option value="" disabled="disabled">Select Project</option>
                        <?php echo $option_list; ?>
                    </select>
				  </div>
				</div>
				<div class="form-group">
				  <label class="control-label col-sm-4" for="lname">Sprint Name:</label>
				  <div class="col-sm-10">          
                    <input type="text" class="form-control" placeholder="Sprint Name" name="sprint_name" required>
				</div>
				<div class="form-group mt-3">
				  <label class="control-label col-sm-10" for="file">Upload Planned Story Point:</label>
				  <div class="col-sm-10">
                    <input type="file" name="csv" id="file">
				  </div>
				</div>
				<div class="form-group">
				  <label class="control-label col-sm-10" for="file_rw">Upload Reopen Caculation CSV:</label>
				  <div class="col-sm-10">
                    <input type="file" name="csv_rw" id="file_rw">
				  </div>
				</div>
				<div class="form-group">        
				  <div class="col-sm-offset-2 col-sm-10">
					<button type="submit" class="btn btn2 btn-default" name="automate">Add Sprint Data</button>
				  </div>
				</div>
			</div>
        </form>    
		</div>
	</div>
</div>
<script>
    function getID(pid) {
        $.ajax({
            url: "ajax_required.php",
            type: "get", //send it through get method
            data: {
                project_id: pid
            },
            success: function(response) {
                //Do Something
            }
        });
    }
</script>
<?php include_once('includes/footer.php'); ?>