<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.php');
}
include('includes/header.php');

$project_id = (isset($_POST['project_id']) ? $_POST['project_id'] : '');
$p_sql = "SELECT project_id, project_name FROM project";
$p_result = tep_db_query($p_sql);
$option_list  = '';
if (tep_db_num_rows($p_result) > 0) {
    while ($p_row = tep_db_fetch_array($p_result)) {
        $selected = (isset($_GET['action']) && ($_GET['action'] === 'view') &&  ($p_row['project_id'] === $project_id) ? 'selected' : '');
        $option_list .= '<option value="' . $p_row['project_id'] . '"' . $selected . '>' . $p_row['project_name'] . '</option>';
    }
}

if (isset($_GET['action'], $_POST['project_id']) && ($_GET['action'] === 'view')) {
    //project data
    $p_result = tep_db_query("SELECT `project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status`, `is_sprint` FROM project WHERE project_id ='" . $_POST['project_id'] . "'");
    // sprint data
    $sprint_sql = "SELECT s.`sprint_id`, p.`project_id`, p.`project_name`, s.`sprint_name`, s.`planned_story_point`, s.`actual_delivered`, s.`v2_delivered`, s.`lt_delivered`, s.`rework`, `lt_reoponed_sp`, `v2_carryover`, `lt_carryover`, `qa_passed`, `v2_reopen_percentage`, s.`lt_reopen_percentage`, s.`v2_carryover_percentage`, s.`lt_carryover_percentage`, s.`planned_vs_completed_ratio` FROM project p, sprint_data s WHERE p.project_id = s.project_id AND p.project_id = '" . $_POST['project_id'] . "'";
    //
    $result = tep_db_query($sprint_sql);
    $sprint_data = array();
    foreach ($result as $row) {
        $sprint_data[] = $row;
    }
    //
    $is_sprint_status = tep_db_fetch_array($p_result);
    if($is_sprint_status['is_sprint'] == 1) {
        $sprint_sql .= " order by s.sprint_id desc LIMIT 0, 8";
    } else {
        $sprint_sql .= " order by s.sprint_id desc LIMIT 0, 6";
    }

    $is_sprint_graph_query = tep_db_query($sprint_sql);
    $graph_data = array();
    foreach ($is_sprint_graph_query as $row) {
        $graph_data[] = $row;
    }
    //echo '<pre>'; print_r($graph_data);
    //
    if(isset($graph_data)){
        $json_data = json_encode($graph_data);
    }
}

if (isset($_GET['action']) && ($_GET['action'] === 'deletesprint')) {
    $sql = "DELETE FROM sprint_data WHERE sprint_id = '" . $_GET['sprint_id'] . "'";
    tep_db_query($sql);
    $_SESSION['success'] = "Record deleted successfully!!!";
    tep_redirect('sprint_view.php');
}
?>

<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-6">
            <h4>Sprint Page</h4>
        </div>
        <div class="col-6 text-right">
            <a href="sprint.php" class="btn" role="button">Add Sprint</a>
        </div>
    </div>
    <?php include('project_sprint_view.php'); ?>
</div>

<script src="includes/chart.js"></script>
<script type="text/javascript">
    function deleteConfirm(id) {
        //console.log(id);
        if (confirm("Are you sure to delete this record?")) {
            location.href = "sprint_view.php?action=deletesprint&sprint_id=" + id;
        }
    }
    $(document).ready(function() {
        $('#project_id').on('change', function() {
            this.form.submit();
        });
        <?php if (isset($json_data)) { ?>
            showGraph(<?php echo $json_data; ?>);
        <?php } ?>

        $('#example').DataTable();
    });

    function callAjax(pid) {
        $.ajax({
            url: "export.php",
            type: "get", //send it through get method
            data: {
                project_id: pid
            },
            success: function(response) {
                //Do Something
                alert('Excelsheet downloaded successfully!!');
            },
            error: function(xhr) {
                //Do Something to handle error
            }
        });
    }
</script>
<?php include_once('includes/footer.php'); ?>