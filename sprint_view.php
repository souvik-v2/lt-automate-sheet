<?php
require('includes/application_top.php');
include('includes/header.php');

$project_id = (isset($_POST['project_id']) ? $_POST['project_id'] : '');
$p_result = $con->run("SELECT project_id, project_name FROM project ORDER BY project_id DESC");
$option_list  = '';
if (tep_db_num_rows($p_result) > 0) {
    while ($p_row = tep_db_fetch_array($p_result)) {
        $selected = (isset($action) && ($action == 'view') &&  ($p_row['project_id'] == $project_id) ? 'selected' : '');
        $option_list .= '<option value="' . $p_row['project_id'] . '"' . $selected . '>' . $p_row['project_name'] . '</option>';
    }
}

if (isset($action, $_POST['project_id']) && ($action == 'view')) {
    //project data
    $p_result = $con->run("SELECT `project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status`, `is_sprint` FROM project WHERE project_id = ? ", array($_POST['project_id']));
    // sprint data
    $sprint_sql = "SELECT s.`sprint_id`, p.`project_id`, p.`project_name`, s.`sprint_name`, s.`planned_story_point`, s.`actual_delivered`, s.`v2_delivered`, s.`lt_delivered`, s.`rework`, `lt_reoponed_sp`, `v2_carryover`, `lt_carryover`, `qa_passed`, `v2_reopen_percentage`, s.`lt_reopen_percentage`, s.`v2_carryover_percentage`, s.`lt_carryover_percentage`, s.`planned_vs_completed_ratio` FROM project p, sprint_data s WHERE p.project_id = s.project_id AND p.project_id = ?";
    //
    $result = $con->run($sprint_sql . " ORDER BY s.sprint_id DESC", array($_POST['project_id']));

    $sprint_data = array();
    foreach (tep_db_fetch_all($result) as $key => $row) {
        $sprint_data[$key] = $row;
    }
    //
    $is_sprint_status = tep_db_fetch_array($p_result);
    if($is_sprint_status['is_sprint'] == 1) {
        $sprint_sql .= " ORDER BY s.sprint_id DESC LIMIT 0, 8";
    } else {
        $sprint_sql .= " ORDER BY s.sprint_id DESC LIMIT 0, 6";
    }

    $is_sprint_graph_query = $con->run($sprint_sql, array($_POST['project_id']));
    $graph_data = array();
    foreach (tep_db_fetch_all($is_sprint_graph_query) as $key => $row) {
        $graph_data[$key] = $row;
    }
    //echo '<pre>'; print_r($graph_data);
    //
    if(isset($graph_data)){
        $json_data = json_encode($graph_data);
    }
}

if (isset($action) && ($action == 'deletesprint')) {
    $sql = "DELETE FROM sprint_data WHERE sprint_id = ?";
    $con->run($sql, array($_GET['sprint_id']));
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

<script src="js/graph.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#project_id').on('change', function() {
            this.form.submit();
        });
        <?php if (isset($json_data)) { ?>
            showGraph(<?php echo $json_data; ?>);
        <?php } ?>

        $('#example').DataTable();
    });
</script>
<?php include_once('includes/footer.php'); ?>