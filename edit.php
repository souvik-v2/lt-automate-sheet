<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.html');
}
include('includes/header.php');


$result = tep_db_query("SELECT `sprint_id`, `project_id`, `sprint_name`, `planned_story_point`, `actual_delivered`, `v2_delivered`, `lt_delivered`, `rework`, `lt_reoponed_sp`, `v2_carryover`, `lt_carryover`, `qa_passed`, `v2_reopen_percentage`, `lt_reopen_percentage`, `v2_carryover_percentage`, `lt_carryover_percentage`, `planned_vs_completed_ratio` FROM sprint_data WHERE sprint_id = '" . $_GET['sprint_id'] . "'");
$row = tep_db_fetch_array($result);
//
$p_result = tep_db_query("SELECT project_id, project_name FROM project order by project_id desc");
$option_list  = '';
if (tep_db_num_rows($p_result) > 0) {
    while ($p_row = tep_db_fetch_array($p_result)) {
        $selected = (isset($_GET['action']) && ($_GET['action'] === 'editsprint') &&  ($p_row['project_id'] === $row['project_id']) ? 'selected' : '');
        $option_list .= '<option value="' . $p_row['project_id'] . '"' . $selected . '>' . $p_row['project_name'] . '</option>';
    }
}

if (isset($_GET['action']) && ($_GET['action'] === 'updatesprint')) {
    //case: new
    //echo "<pre>"; print_r($_POST);
    // check there are no errors
    $csv = array();
    $csv_rw = array();
    $total_story_count = $row['planned_story_point'];
    $total_v2_score = $row['v2_delivered'];
    $total_lt_score = $row['lt_delivered'];
    $total_v2_carryover = $row['v2_carryover'];
    $total_lt_carryover = $row['lt_carryover'];
    $actual_delivered = $row['actual_delivered'];
    $rework = $row['rework'];
    $qa_passed = $row['qa_passed'];
    $lt_reoponed_sp = $row['lt_reoponed_sp'];
    $v2_reopen_percentage = $row['v2_reopen_percentage'];
    $lt_reopen_percentage = $row['lt_reopen_percentage'];
    $v2_carryover_percentage = $row['v2_carryover_percentage'];
    $lt_carryover_percentage = $row['lt_carryover_percentage'];
    $planned_vs_completed_ratio = $row['planned_vs_completed_ratio'];
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
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $csv[] = $data;
                }
                fclose($handle);
            }
        }
        if (count($csv) > 0) {
            for ($i = 0; $i < count($csv[0]); $i++) {
                if (strpos($csv[0][$i], 'Story Points') !== false) {
                    $key = $i;
                }
            }
            $story = array();
            for ($i = 1; $i < count($csv); $i++) {
                for ($j = 0; $j < count($csv[$i]); $j++) {
                    if ($key && $j == 0) {
                        $story[] = $csv[$i][$key];
                    }
                }
            }
            $total_story_count = array_sum($story);
        }
    } else {
        $_SESSION['error'] = '"File upload error!!';
    }
    //rework calculation
    if($_FILES['csv_rw']['error'] == 0) {
            
        $name = $_FILES['csv_rw']['name'];
        $tmp = explode('.', $name);
        $extention = end($tmp);

        $ext = strtolower($extention);
        $type = $_FILES['csv_rw']['type'];
        $tmpName = $_FILES['csv_rw']['tmp_name'];

        // check the file is a csv
        if($ext === 'csv'){
            if(($handle = fopen($tmpName, 'r')) !== FALSE) {
                //necessary if a large csv file
                set_time_limit(0);
                $in = 0;
                while(($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if($in === 0) {
                        $csv_rw_index[] = $data;
                    }
                    $csv_rw[] = $data;
                    $in++;
                }
                fclose($handle);
            }
        }
        if(count($csv_rw) > 0) {
            for($i=0; $i<count($csv_rw[0]); $i++) { 
                if (strpos($csv_rw[0][$i], 'Story Points') !== false) {
                    $rw_point_key = $i;
                }
                if (strpos($csv_rw[0][$i], 'Sprint') !== false) {
                    $rw_sprint_key = $i;
                }
                if (strpos($csv_rw[0][$i], 'Resource') !== false) {
                    $rw_resource_key = $i;
                }
            }
            $rw_story = array();
            for($i=1; $i<count($csv_rw); $i++) {
                for($j=0; $j<count($csv_rw[$i]); $j++) { 
                    if ($rw_point_key && $j==0) {
                        $rw_story['rw_story'][] = $csv_rw[$i][$rw_point_key];
                    }
                    if (isset($rw_sprint_key) && $j==0) {
                        $rw_story['rw_sprint'][] = $csv_rw[$i][$rw_sprint_key];
                    }
                    if ($rw_resource_key && $j==0) {
                        $rw_story['rw_resource'][] = $csv_rw[$i][$rw_resource_key];
                    }
                }
            }
            $rw_story_point_array = $rw_story['rw_story'];
            $rw_sprint_point_array = $rw_story['rw_sprint'];
            $rw_resource_point_array = $rw_story['rw_resource'];
            foreach($rw_resource_point_array as $k => $v ) {
                if($v == 1) {
                    $rw_v2_score[] = $rw_story_point_array[$k];
                } else {
                    $rw_lt_score[] = $rw_story_point_array[$k];
                }
                }
            $rework = array_sum($rw_v2_score);
            $lt_reoponed_sp = array_sum($rw_lt_score);
        }
    } else {
        $_SESSION['error'] = '"File upload error!!';
    }
    //update
    if ( ($_FILES['csv']['error'] == 0) && ($_FILES['csv_rw']['error'] == 0) ) {
        $sql_data_array = array (
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
            'qa_passed' =>  ($total_v2_score-$rework),
            'v2_reopen_percentage' =>  round(($rework/$total_v2_score)*100),
            'lt_reopen_percentage' =>  round(($lt_reoponed_sp/$total_lt_score)*100),
            'v2_carryover_percentage' =>  round(($total_v2_carryover/$total_v2_score)*100),
            'lt_carryover_percentage' =>  round(($total_lt_carryover/$total_lt_score)*100),
            'planned_vs_completed_ratio' =>  round(($actual_delivered/$total_story_count)*100)
        );
    } else {
        $sql_data_array = array (
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
            'qa_passed' =>  $qa_passed,
            'v2_reopen_percentage' =>  $v2_reopen_percentage,
            'lt_reopen_percentage' =>  $lt_reopen_percentage,
            'v2_carryover_percentage' =>  $v2_carryover_percentage,
            'lt_carryover_percentage' =>  $lt_carryover_percentage,
            'planned_vs_completed_ratio' =>  $planned_vs_completed_ratio
        );
    }
    //
    tep_db_perform('sprint_data', $sql_data_array, 'update', 'sprint_id=' . $_POST['sprint_id']);
    $_SESSION['success'] = "Record updated successfully!!!";
    tep_redirect('home.php');
}
?>
<div class="content container">
    <h2>Edit Sprint</h2>
    <div class="row">
        <div class="col-12">
            <form method="POST" action="edit.php?action=updatesprint" name="automate-sheet" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="formGroupExampleInput">Project Name </label>
                    <select name="project_id" class="form-control">
                        <option value="">Select Project</option>
                        <?php echo $option_list; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="formGroupExampleInput2">Sprint Name</label>
                    <input type="text" class="form-control" placeholder="Sprint Name" name="sprint_name" value="<?php echo $row['sprint_name']; ?>">
                </div>
                <div class="form-group">
                    <span class="text-danger">*</span>
                    <label for="formGroupExampleInput2">Upload To Update Planned Story Point</label>
                    <input type="file" name="csv" id="file">
                    <input type="hidden" name="sprint_id" value="<?php echo $row['sprint_id']; ?>">
                </div>
                <div class="form-group">
                    <span class="text-danger">*</span>
                    <label for="file_rw">Upload To Update Rework Caculation CSV</label>
                    <input type="file" name="csv_rw" id="file_rw">
                </div>
                <button class="btn btn-primary" type="submit" name="automate">Update Planned Story Point CSV</button>
            </form>
        </div>
    </div>
</div>
<?php include_once('includes/footer.php'); ?>