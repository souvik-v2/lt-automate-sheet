<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.php');
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

    if(!file_exists($_FILES['csv']['tmp_name']) || !is_uploaded_file($_FILES['csv']['tmp_name'])) {
        $total_story_count = $row['planned_story_point'];
        $total_v2_score = $row['v2_delivered'];
        $total_lt_score = $row['lt_delivered'];
        $total_v2_carryover = $row['v2_carryover'];
        $total_lt_carryover = $row['lt_carryover'];
        $actual_delivered = $row['actual_delivered'];
        $qa_passed = +$row['qa_passed'];
        $v2_reopen_percentage = $row['v2_reopen_percentage'];
        $lt_reopen_percentage = $row['lt_reopen_percentage'];
        $v2_carryover_percentage = $row['v2_carryover_percentage'];
        $lt_carryover_percentage = $row['lt_carryover_percentage'];
        $planned_vs_completed_ratio = $row['planned_vs_completed_ratio'];
    }
    //
    if(!file_exists($_FILES['csv_rw']['tmp_name']) || !is_uploaded_file($_FILES['csv_rw']['tmp_name'])) {
        $rework = $row['rework'];
        $lt_reoponed_sp = $row['lt_reoponed_sp'];
    }
    //
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
                if (strpos($csv[0][$i], 'Resource') !== false) {
                    $resource_key = $i;
                }
            }
            $story = array();
            for ($i = 1; $i < count($csv); $i++) {
                for ($j = 0; $j < count($csv[$i]); $j++) {
                    if ($point_key && $j == 0) {
                        $story['story'][] = $csv[$i][$point_key];
                    }
                    if (isset($sprint_key) && $j == 0) {
                        $story['sprint'][] = $csv[$i][$sprint_key[count($sprint_key) - 2]];
                    }
                    if ($resource_key && $j == 0) {
                        $story['resource'][] = $csv[$i][$resource_key];
                    }
                }
            }
            $story_point_array = $story['story'];
            $sprint_point_array = $story['sprint'];
            $resource_point_array = $story['resource'];
            foreach ($resource_point_array as $k => $v) {
                if ($v == 1) {
                    $v2_score[] = $story_point_array[$k];
                    $v2_carryover[] = (count($sprint_point_array) && ($sprint_point_array[$k] != '') > 0 ? $story_point_array[$k] : 0);
                } else {
                    $lt_score[] = $story_point_array[$k];
                    $lt_carryover[] = (count($sprint_point_array) && ($sprint_point_array[$k] != '') > 0 ? $story_point_array[$k] : 0);
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
                if (strpos($csv_rw[0][$i], 'Resource') !== false) {
                    $rw_resource_key = $i;
                }
            }
            $rw_story = array();
            for ($i = 1; $i < count($csv_rw); $i++) {
                for ($j = 0; $j < count($csv_rw[$i]); $j++) {
                    if ($rw_point_key && $j == 0) {
                        $rw_story['rw_story'][] = $csv_rw[$i][$rw_point_key];
                    }
                    if (count($rw_sprint_key)>0 && $j == 0) {
                        $rw_story['rw_sprint'][] = $csv_rw[$i][$rw_sprint_key[count($rw_sprint_key)-1]];
                    }
                    if ($rw_resource_key && $j == 0) {
                        $rw_story['rw_resource'][] = $csv_rw[$i][$rw_resource_key];
                    }
                }
            }
            $rw_story_point_array = $rw_story['rw_story'];
            $rw_sprint_point_array = $rw_story['rw_sprint'];
            $rw_resource_point_array = $rw_story['rw_resource'];
            foreach ($rw_resource_point_array as $k => $v) {
                if ($v == 1) {
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
    //update
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
    //echo '<pre>'; print_r($sql_data_array); die();
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
            <form method="POST" action="edit.php?action=updatesprint&sprint_id=<?php echo $_GET['sprint_id'];?>" name="automate-sheet" enctype="multipart/form-data">
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
                    <label for="formGroupExampleInput2">Upload To Update Planned Story Point</label>
                    <input type="file" name="csv" id="file">
                    <input type="hidden" name="sprint_id" value="<?php echo $row['sprint_id']; ?>">
                </div>
                <div class="form-group">
                    <label for="file_rw">Upload To Update Rework Caculation CSV</label>
                    <input type="file" name="csv_rw" id="file_rw">
                </div>
                <button class="btn btn-primary" type="submit" name="automate">Update Planned Story Point CSV</button>
            </form>
        </div>
    </div>
</div>
<?php include_once('includes/footer.php'); ?>