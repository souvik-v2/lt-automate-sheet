<?php
require('includes/application_top.php');
include('includes/header.php');


$result = $con->run("SELECT `sprint_id`, `project_id`, `sprint_name`, `planned_story_point`, `actual_delivered`, `v2_delivered`, `lt_delivered`, `rework`, `lt_reoponed_sp`, `v2_carryover`, `lt_carryover`, `qa_passed`, `v2_reopen_percentage`, `lt_reopen_percentage`, `v2_carryover_percentage`, `lt_carryover_percentage`, `planned_vs_completed_ratio` FROM sprint_data WHERE sprint_id = ?", array($_GET['sprint_id']));

$row = tep_db_fetch_array($result);
//
$p_result = $con->run("SELECT project_id, project_name FROM project order by project_id desc");
$option_list  = '';
if (tep_db_num_rows($p_result) > 0) {
    while ($p_row = tep_db_fetch_array($p_result)) {
        $selected = (isset($_GET['action']) && ($_GET['action'] == 'editsprint') &&  ($p_row['project_id'] == $row['project_id']) ? 'selected' : '');
        $option_list .= '<option value="' . $p_row['project_id'] . '"' . $selected . '>' . $p_row['project_name'] . '</option>';
    }
}

if (isset($_GET['action']) && ($_GET['action'] == 'updatesprint')) {
    //case: new
    //echo "<pre>"; print_r($_POST);
    $dev_sql = $con->run("SELECT developers FROM project WHERE project_id = ?", array($_POST['project_id']));
    $dev_result = tep_db_fetch_array($dev_sql);
    //v2.0
    $dev_query = $con->run("SELECT developer_name FROM `developer` WHERE developer_id IN (" . $dev_result['developers'] . ")");
    $dev_name_string = '';
    while ($dev_query_result = tep_db_fetch_array($dev_query)) {
        $dev_name_string .= $dev_query_result['developer_name'] . ', ';
    }
    $dev_name_array = array_filter(explode(', ', $dev_name_string));
    //
    $csv = array();
    $csv_rw = array();
    $v2_completed = array();
    $lt_completed = array();
    $deplyment_closed_v2_carryover = array();
    $deplyment_closed_lt_carryover = array();
    $developer_name_array = array();
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

    $sprint_val = $_POST['sprint_name'];

    if (!file_exists($_FILES['csv']['tmp_name']) || !is_uploaded_file($_FILES['csv']['tmp_name'])) {
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
    if (!file_exists($_FILES['csv_rw']['tmp_name']) || !is_uploaded_file($_FILES['csv_rw']['tmp_name'])) {
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
        if ($ext == 'csv') {
            if (($handle = fopen($tmpName, 'r')) !== FALSE) {
                //necessary if a large csv file
                set_time_limit(0);
                $in = 0;
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if ($in == 0) {
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
                if (strpos($csv[0][$i], 'Issue key') !== false) {
                    $issue_key = $i;
                }
                if (strpos($csv[0][$i], 'Story Points') !== false) {
                    $point_key = $i;
                }
                if (strpos($csv[0][$i], 'Status') !== false) {
                    $status_key = $i;
                }
                if (strpos($csv[0][$i], 'Sprint') !== false) {
                    $sprint_key[] = $i;
                }
                if (strpos($csv[0][$i], 'Assignee') !== false) {
                    $dev_key = $i;
                }
            }
            $story = array();
            // for ($i = 1; $i < count($csv); $i++) {
            //     for ($j = 0; $j < count($csv[$i]); $j++) {
            //         if ($point_key && $j == 0) {
            //             $story['story'][] = $csv[$i][$point_key];
            //         }
            //         if (isset($sprint_key) && $j == 0) {
            //             $story['sprint'][] = $csv[$i][$sprint_key[1]];
            //         }
            //         if ($dev_key && $j == 0) {
            //             $story['developers'][] = $csv[$i][$dev_key];
            //         }
            //     }
            // }

            for ($i = 1; $i < count($csv); $i++) {
                for ($j = 0; $j < count($csv[$i]); $j++) {
                    if ($point_key && $j == 0) {
                        $story['story'][] = $csv[$i][$point_key];
                    }
                    // if (isset($sprint_key) && $j == 0) {
                    //     $story['sprint'][] = $csv[$i][$sprint_key[1]];
                    // }
                    //fetch all sprints ranjan
                    if (isset($sprint_key) && $j == 0) {
                        for ($x = 0; $x < count($sprint_key); $x++) {
                            $story['sprint'][$x][] = $csv[$i][$sprint_key[$x]];
                        }
                    }
                    if ($dev_key && $j == 0) {
                        $story['developers'][] = $csv[$i][$dev_key];
                    }
                    if ($status_key && $j == 0) {
                        $story['status'][] = $csv[$i][$status_key];
                    }
                    if (is_numeric($issue_key) && $j == 0) {
                        $story['issue'][] = $csv[$i][$issue_key];
                    }
                }
            }


            $story_point_array = $story['story'];
            $sprint_point_array = $story['sprint'];
            $developers_point_array = $story['developers'];
            $status_point_array = $story['status'];
            $issue_key_array = $story['issue'];
            // foreach ($developers_point_array as $k => $v) {
            //     if (in_array($v, $dev_name_array)) {
            //         $v2_score[] = $story_point_array[$k];
            //         $v2_carryover[] = (count($sprint_point_array) > 0  && ($sprint_point_array[$k] != '') ? $story_point_array[$k] : 0);
            //     } else {
            //         $lt_score[] = $story_point_array[$k];
            //         $lt_carryover[] = (count($sprint_point_array) > 0 && ($sprint_point_array[$k] != '')  ? $story_point_array[$k] : 0);
            //     }
            // }

            foreach ($developers_point_array as $k => $v) {
                if ($v == "")
                    continue;
                if (in_array($v, $dev_name_array) && ($story_point_array[$k] != '')) {
                    $ik = $issue_key_array[$k];
                    $developer_name_array[$v][$ik]['total'] = (int) $story_point_array[$k]; //v2.0
                    $v2_score[] = $story_point_array[$k];
                    $cnt_sprint_point_array = count($sprint_point_array);
                    $last_index = $cnt_sprint_point_array - 1;
                    //echo $k;die;
                    //completed next level 
                    // if first sprint is empty then we will not proceed further
                    if ($sprint_point_array[0][$k] == "")
                        continue;

                    if ($status_point_array[$k] == "Pending Deployment" || $status_point_array[$k] == "Closed") {
                        if ($story_point_array[$k] == "") {
                            $v2_completed[] = 0;
                            $developer_name_array[$v][$ik]['completed'] = 0;
                        } else {
                            $v2_completed[] = $story_point_array[$k];
                            $developer_name_array[$v][$ik]['completed'] = (int) $story_point_array[$k];
                        }
                    }

                    //$v2_carryover[] = ((count($sprint_point_array) > 0) && ($sprint_point_array[$k] != '')  ? $story_point_array[$k] : 0);
                    if ($sprint_point_array[$last_index][$k] != "") {
                        if ($sprint_point_array[$last_index][$k] == $sprint_val) {
                            $v2_carryover[] = 0;
                            $developer_name_array[$v][$ik]['carryover'] = 0;
                        } else {
                            $v2_carryover[] = $story_point_array[$k];
                            $developer_name_array[$v][$ik]['carryover'] = (int) $story_point_array[$k];
                        }
                    } else {
                        for ($y = 0; $y < $cnt_sprint_point_array; $y++) {
                            $sprint_v2_val = $sprint_point_array[$y][$k];
                            if ($sprint_v2_val == "") {
                                $sprint_v2_val = $sprint_point_array[$y - 1][$k];
                                if ($sprint_v2_val == $sprint_val) {
                                    $v2_carryover[] = 0;
                                    $developer_name_array[$v][$ik]['carryover'] = 0;
                                } else {
                                    $v2_carryover[] = $story_point_array[$k];
                                    $developer_name_array[$v]['carryover'][] = $story_point_array[$k];
                                    if (($status_point_array[$k] == "Pending Deployment" || $status_point_array[$k] == "Closed")) {
                                        $deplyment_closed_v2_carryover[] = $story_point_array[$k];
                                    } else {
                                        $developer_name_array[$v][$ik]['carryover'] = (int) $story_point_array[$k];
                                    }
                                }

                                // else
                                //     $v2_carryover[]=$story_point_array[$k];
                                break;
                            }
                        }
                    }
                } else {
                    $lt_score[] = $story_point_array[$k];
                    $cnt_sprint_point_array = count($sprint_point_array);
                    $last_index = $cnt_sprint_point_array - 1;


                    // if first sprint is empty then we will not proceed further
                    if ($sprint_point_array[0][$k] == "")
                        continue;

                    if ($status_point_array[$k] == "Pending Deployment" || $status_point_array[$k] == "Closed") {
                        if ($story_point_array[$k] == "")
                            $lt_completed[] = 0;
                        else
                            $lt_completed[] = $story_point_array[$k];
                    }


                    // $lt_carryover[] = ((count($sprint_point_array) > 0) && ($sprint_point_array[$k] != '') ? $story_point_array[$k] : 0);
                    if ($sprint_point_array[$last_index][$k] != "") {
                        if ($sprint_point_array[$last_index][$k] == $sprint_val)
                            $lt_carryover[] = 0;
                        else
                            $lt_carryover[] = $story_point_array[$k];
                    } else {
                        for ($z = 0; $z < $cnt_sprint_point_array; $z++) {
                            $sprint_lt_val = $sprint_point_array[$z][$k];
                            if ($sprint_lt_val == "") {
                                $sprint_lt_val = $sprint_point_array[$z - 1][$k];
                                if ($sprint_lt_val == $sprint_val)
                                    $lt_carryover[] = 0;
                                else {
                                    $lt_carryover[] = $story_point_array[$k];
                                    if (($status_point_array[$k] == "Pending Deployment" || $status_point_array[$k] == "Closed"))
                                        $deplyment_closed_lt_carryover[] = $story_point_array[$k];
                                }
                                break;
                            }
                        }
                    }
                }
            }

            $v2_total_completed = (int)array_sum($v2_completed);
            $lt_total_completed = (int)array_sum($lt_completed);
            $total_story_count = (int) array_sum($story_point_array);
            $total_v2_score = (int) array_sum($v2_score);
            $total_v2_carryover = (int) array_sum($v2_carryover);
            $total_lt_score = (int) array_sum($lt_score);
            $total_lt_carryover = (int) array_sum($lt_carryover);

            $total_deployment_closed_v2_carryover = (int) array_sum($deplyment_closed_v2_carryover);
            $total_deployment_closed_lt_carryover = (int) array_sum($deplyment_closed_lt_carryover);
            $v2_delivered = $v2_total_completed - $total_deployment_closed_v2_carryover;
            $lt_delivered = $lt_total_completed - $total_deployment_closed_lt_carryover;

            // $actual_delivered = $total_story_count - ($total_v2_carryover + $total_lt_carryover);
            $actual_delivered = $v2_delivered + $lt_delivered;
        }
    } else {
        $_SESSION['error'] = 'Sprint file not uploaded!!';
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
        if ($ext == 'csv') {
            if (($handle = fopen($tmpName, 'r')) !== FALSE) {
                //necessary if a large csv file
                set_time_limit(0);
                $in = 0;
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    if ($in == 0) {
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
                if (strpos($csv[0][$i], 'Issue key') !== false) {
                    $rw_issue_key = $i;
                }
            }
            $rw_story = array();
            // for ($i = 1; $i < count($csv_rw); $i++) {
            //     for ($j = 0; $j < count($csv_rw[$i]); $j++) {
            //         if ($rw_point_key && $j == 0) {
            //             $rw_story['rw_story'][] = $csv_rw[$i][$rw_point_key];
            //         }
            //         if (count($rw_sprint_key) > 0 && $j == 0) {
            //             $rw_story['rw_sprint'][] = $csv_rw[$i][$rw_sprint_key[count($rw_sprint_key) - 1]];
            //         }
            //         if ($rw_dev_key && $j == 0) {
            //             $rw_story['rw_developers'][] = $csv_rw[$i][$rw_dev_key];
            //         }
            //     }
            // }

            for ($i = 1; $i < count($csv_rw); $i++) {
                for ($j = 0; $j < count($csv_rw[$i]); $j++) {
                    if ($rw_point_key && $j == 0) {
                        $rw_story['rw_story'][] = $csv_rw[$i][$rw_point_key];
                    }
                    // if (isset($rw_sprint_key) && $j == 0) {
                    //     $rw_story['rw_sprint'][] = $csv_rw[$i][$rw_sprint_key[count($rw_sprint_key) - 1]];
                    // }
                    if (isset($rw_sprint_key) && $j == 0) {
                        for ($xx = 0; $xx < count($rw_sprint_key); $xx++) {
                            $rw_story['rw_sprint'][$xx][] = $csv_rw[$i][$rw_sprint_key[$xx]];
                        }
                    }
                    if ($rw_dev_key && $j == 0) {
                        $rw_story['rw_developers'][] = $csv_rw[$i][$rw_dev_key];
                    }
                    if (is_numeric($rw_issue_key) && $j == 0) {
                        $rw_story['rw_issue'][] = $csv_rw[$i][$rw_issue_key];
                    }
                }
            }


            $rw_story_point_array = $rw_story['rw_story'];
            $rw_sprint_point_array = $rw_story['rw_sprint'];
            $rw_developers_point_array = $rw_story['rw_developers'];
            $rw_issue_key_array = $rw_story['rw_issue'];
            // foreach ($rw_developers_point_array as $k => $v) {
            //     if (in_array($v, $dev_name_array)) {
            //         $rw_v2_score[] = (count($rw_sprint_point_array) > 0 && ($rw_sprint_point_array[$k] != '') ? $rw_story_point_array[$k] : 0);
            //     } else {
            //         $rw_lt_score[] = (count($rw_sprint_point_array) > 0 && ($rw_sprint_point_array[$k] != '') ? $rw_story_point_array[$k] : 0);
            //     }
            // }

            foreach ($rw_developers_point_array as $k => $v) {
                if (in_array($v, $dev_name_array)) {
                    // $rw_v2_score[] = (count($rw_sprint_point_array) > 0 && ($rw_sprint_point_array[$k] != '') ? $rw_story_point_array[$k] : 0);
                    $rw_v2_score[] = (($rw_story_point_array[$k] != '') ? $rw_story_point_array[$k] : 0);
                    $developer_name_array[$v][$rw_issue_key_array[$k]]['reopen'] = (($rw_story_point_array[$k] != '') ? (int) $rw_story_point_array[$k] : 0);
                } else {
                    // $rw_lt_score[] = (count($rw_sprint_point_array) > 0 && ($rw_sprint_point_array[$k] != '') ? $rw_story_point_array[$k] : 0);
                    $rw_lt_score[] = (($rw_story_point_array[$k] != '') ? $rw_story_point_array[$k] : 0);
                }
            }


            $rework = (int) array_sum($rw_v2_score);
            $lt_reoponed_sp = (int) array_sum($rw_lt_score);
        }
    } else {
        $_SESSION['error'] = 'Reopen file not uploaded!!';
    }
    //update
    $sql_data_array = array(
        'project_id' =>  $_POST['project_id'],
        'sprint_name' =>  $_POST['sprint_name'],
        'planned_story_point' =>  (int) $total_story_count,
        'actual_delivered' =>  (int) $actual_delivered,
        // 'v2_delivered' =>  (int) $total_v2_score,
        // 'lt_delivered' =>  (int) $total_lt_score,
        // 'v2_delivered' => (int) $v2_total_completed,
        // 'lt_delivered' => (int) $lt_total_completed,
        'v2_delivered' => (int) $v2_delivered,
        'lt_delivered' => (int) $lt_delivered,
        'rework' =>  (int) $rework,
        'lt_reoponed_sp' =>  (int) $lt_reoponed_sp,
        'v2_carryover' =>  (int) $total_v2_carryover,
        'lt_carryover' =>  (int) $total_lt_carryover,
        // 'qa_passed' => (int) ($total_v2_score - $rework),
        'qa_passed' => $v2_delivered,
        'v2_reopen_percentage' =>  (int) round(($rework / $total_v2_score) * 100),
        'lt_reopen_percentage' =>  (int) round(($lt_reoponed_sp / $total_lt_score) * 100),
        'v2_carryover_percentage' =>  (int) round(($total_v2_carryover / $total_v2_score) * 100),
        'lt_carryover_percentage' =>  (int) round(($total_lt_carryover / $total_lt_score) * 100),
        'planned_vs_completed_ratio' =>  (int) round(($actual_delivered / $total_story_count) * 100)
    );
    //echo '<pre>'; print_r($sql_data_array); die();
    //
    try {
        tep_db_perform($con, 'sprint_data', $sql_data_array, 'update', array('sprint_id', $_POST['sprint_id']));
        //v2.0
        if ((file_exists($_FILES['csv']['tmp_name']) || is_uploaded_file($_FILES['csv']['tmp_name'])) && (file_exists($_FILES['csv_rw']['tmp_name']) || is_uploaded_file($_FILES['csv_rw']['tmp_name']))) {
            if (count($developer_name_array) > 0) {
                $con->run("DELETE FROM sprint_report WHERE sprint_id = ?", array($_POST['sprint_id']));
                //
                $project_id = $_POST['project_id'];
                $sprint_id = $_POST['sprint_id'];
                $total_storypoint = $completed_storypoint = $carryover_storypoint = $reopen_storypoint = 0;
                foreach ($developer_name_array as $k => $v) {
                    $dev_sql_query = $con->run("SELECT developer_id FROM `developer` WHERE developer_name=?", array($k));

                    $dev_sql_data = tep_db_fetch_array($dev_sql_query);
                    $developer_id = $dev_sql_data['developer_id'];
                    foreach ($v as $p => $s) {
                        $issue_key_data = $p;
                        $total_storypoint = (array_key_exists("total", $s) ? $s['total'] : 0);
                        $completed_storypoint = (array_key_exists("completed", $s) ? $s['completed'] : 0);
                        $carryover_storypoint = (array_key_exists("carryover", $s) ? $s['carryover'] : 0);
                        $reopen_storypoint = (array_key_exists("reopen", $s) ? $s['reopen'] : 0);

                        if ($reopen_storypoint > 0) {
                            $reopen_storypoint = 1;
                            $completed_storypoint = $carryover_storypoint = 0;
                        } else if ($carryover_storypoint > 0) {
                            $carryover_storypoint = 1;
                            $completed_storypoint = $reopen_storypoint = 0;
                        } else {
                            $completed_storypoint = 1;
                            $carryover_storypoint = $reopen_storypoint = 0;
                        }
                        //
                        $report_data_array = array(
                            'project_id' =>  $project_id,
                            'sprint_id' =>  $sprint_id,
                            'developer_id' => (int) $developer_id,
                            'issue_key' => $issue_key_data,
                            'total_storypoint' => (int) $total_storypoint,
                            'completed_storypoint' => (int) $completed_storypoint,
                            'carryover_storypoint' => (int) $carryover_storypoint,
                            'reopen_storypoint' => (int) $reopen_storypoint
                        );
                        //echo '<pre>'; print_r($report_data_array); die();
                        tep_db_perform($con, 'sprint_report', $report_data_array);
                        $total_storypoint = $carryover_storypoint = $reopen_storypoint = 0;
                    }
                }
            }
        }
        $_SESSION['success'] = "Record updated successfully!!!";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    tep_redirect('sprint_view.php');
}
?>
<div class="container contact mt-3">
    <div class="row">
        <div class="col-md-3">
            <div class="contact-info">
                <img src="images/v-2-logo.svg" alt="image" />
                <h2>Edit Sprint</h2>
            </div>
        </div>
        <div class="col-md-9">
            <form method="POST" action="sprint_edit.php?action=updatesprint&sprint_id=<?php echo $_GET['sprint_id']; ?>" name="automate-sheet" enctype="multipart/form-data">
                <div class="contact-form">
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="fname">Project Name:</label>
                        <div class="col-sm-10">
                            <select name="project_id" class="form-control" required onchange="getID(this.value)">
                                <option value="" disabled="disabled">Select Project</option>
                                <?php echo $option_list; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-4" for="lname">Sprint Name:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Sprint Name" name="sprint_name" value="<?php echo $row['sprint_name']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-10" for="file">Upload To Update Planned Story Point:</label>
                        <div class="col-sm-10">
                            <input type="file" name="csv" id="file">
                            <input type="hidden" name="sprint_id" value="<?php echo $row['sprint_id']; ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-10" for="file_rw">Upload To Update Reopen Caculation CSV:</label>
                        <div class="col-sm-10">
                            <input type="file" name="csv_rw" id="file_rw">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn2 btn-default" name="automate">Update</button>
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