<?php
require('includes/application_top.php');
// template header
include('includes/header.php');

$project_id = (isset($_POST['project_id']) ? $_POST['project_id'] : '');
try {
    $p_sql = "SELECT project_id, project_name FROM project order by project_id desc";
    $p_result = $con->run($p_sql);
    //echo $_POST['project_id'].'<pre>'; print_r($p_result->fetchAll());
    $option_list  = '';
    if (tep_db_num_rows($p_result) > 0) {
        while ($p_row = tep_db_fetch_array($p_result)) {
            $selected = (isset($action) && ($action == 'view') &&  ($p_row['project_id'] == $project_id) ? 'selected' : '');
            $option_list .= '<option value="' . $p_row['project_id'] . '"' . $selected . '>' . $p_row['project_name'] . '</option>';
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

if (isset($action, $_POST['project_id']) && ($action == 'view')) {
    try {
        //project data
        $p_result = $con->run("SELECT `project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status`, `is_sprint` FROM project WHERE project_id = ? ORDER BY project_id DESC", array($project_id));

        // sprint data
        $sql = "SELECT s.`sprint_id`, p.`project_id`, p.`project_name`, s.`sprint_name`, s.`planned_story_point`, s.`actual_delivered`, s.`v2_delivered`, s.`lt_delivered`, s.`rework`, `lt_reoponed_sp`, `v2_carryover`, `lt_carryover`, `qa_passed`, `v2_reopen_percentage`, s.`lt_reopen_percentage`, s.`v2_carryover_percentage`, s.`lt_carryover_percentage`, s.`planned_vs_completed_ratio` FROM project p, sprint_data s WHERE p.project_id = s.project_id AND p.project_id = ?";

        $result = $con->run($sql . " ORDER BY s.sprint_id DESC", array($project_id));
        //
        if(isset($_POST['sprint_id'])) {
            $result = $con->run("SELECT sprint_id, sprint_name FROM sprint_data WHERE project_id = ? ORDER BY sprint_id DESC", array($project_id));
            $sprint_option_list = '';
            $checked = (count($_POST['sprint_id']) > 0 ? 'selected' : '');
            while ($row = tep_db_fetch_array($result)) {
                $sprint_option_list .= '<option value="' . $row['sprint_id'] . '"'. (in_array($row['sprint_id'], $_POST['sprint_id']) ? 'selected' : '') . '>' . $row['sprint_name'] . '</option>';
            }
        }
        //
        if (isset($_POST['sprint_id']) && (count($_POST['sprint_id']) > 0)) {
            $sprint_id = implode(', ', $_POST['sprint_id']);
            $is_sprint_graph_query = $con->run($sql . " AND s.sprint_id IN(".$sprint_id.")", array($project_id));
        } else {
            $is_sprint_status = tep_db_fetch_array($p_result);
            if ($is_sprint_status['is_sprint'] == 1) {
                $is_sprint_graph_query = $con->run($sql . " ORDER BY s.sprint_id ASC LIMIT 0, 8", array($project_id));
            } else {
                $is_sprint_graph_query = $con->run($sql . " ORDER BY s.sprint_id ASC LIMIT 0, 6", array($project_id));
            }
        }

        if (tep_db_num_rows($is_sprint_graph_query) > 0) {
            $fetchdata = tep_db_fetch_all($is_sprint_graph_query);
            foreach ($fetchdata as $key => $row) {
                $graph_data[$key] = $row;
            }
            //echo json_encode($graph_data);
            if (isset($graph_data)) {
                $json_data = json_encode($graph_data);
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}
?>

<div class="container">
    <?php include('project_sprint.php'); ?>
</div>
<script src="js/graph.js?v=<?php echo time(); ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        // $('#project_id').on('change', function() {
        //     this.form.submit();
        // });
        $('#project_id').on('change', function() {
            var project_id = $("#project_id").val();
            $.ajax({
                url: "ajax_required.php?action=sprintcall",
                type: "get",
                async: false,
                data: {
                    projID: project_id,
                },
                success: function(response) {
                    $(".sprint-box").html(response);
                },
            });
        });
        <?php if (isset($json_data)) { ?>
            showGraph(<?php echo $json_data; ?>);
        <?php } ?>
    });
</script>
<style>
    .multiselect-container {
        width: 100% !important;
    }

    .filter-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn {
        margin-top: 0 !important;
    }
    .col-6.sprint-box {
    display: flex;
    align-items: center;
}
.col-6.sprint-box span {
    margin: 0 0 10px 30px;
}
</style>
<?php include_once('includes/footer.php'); ?>