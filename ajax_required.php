<?php
require('includes/application_top.php');

if (isset($_GET['sprint_nm'], $_GET['pID']) && !empty($_GET['sprint_nm'])) {
    $dev_aql = $con->run("SELECT sprint_name FROM sprint_data WHERE sprint_name = ? AND project_id = ?", array($_GET['sprint_nm'], $_GET['pID']));

    if (tep_db_num_rows($dev_aql) > 0) {
        echo 'found';
    } else {
        echo 'new';
    }
}

if (isset($_GET['action'], $_GET['projectID']) && !empty($_GET['projectID']) && ($_GET['action'] == 'reportcall')) {

    $sprint_query = $con->run("SELECT sprint_id, sprint_name FROM sprint_data WHERE project_id = ? ORDER BY sprint_id DESC", array($_GET['projectID']));
    $sprint_option_list  = '';
    if (tep_db_num_rows($sprint_query) > 0) {
        while ($s_row = tep_db_fetch_array($sprint_query)) {
            $sprint_option_list .= '<option value="' . $s_row['sprint_id'] . '">' . $s_row['sprint_name'] . '</option>';
        }
    }
    $developer_query = $con->run("SELECT developer_id, developer_name, developer_status FROM developer WHERE project_id = ? ORDER BY developer_name", array($_GET['projectID']));
    $developer_option_list  = '';
    if (tep_db_num_rows($developer_query) > 0) {
        while ($d_row = tep_db_fetch_array($developer_query)) {
            $developer_option_list .= '<option value="' . $d_row['developer_id'] . '">' . $d_row['developer_name'] . '</option>';
        }
    }
?>
    <div class="row">
        <div class="col-md-6">
            <label for="developers">Sprint: </label>
            <select name="sprint_id" class="form-control" id="sprint_id">
                <option value="">Select Sprint</option>
                <?php echo $sprint_option_list; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="developers">Developer: </label>
            <select name="developer_id" class="form-control" id="developer_id">
                <option value="">Select Developer</option>
                <?php echo $developer_option_list; ?>
            </select>
        </div>
    </div>
    <?php
}
if (isset($_GET['action'], $_GET['projID']) && !empty($_GET['projID']) && ($_GET['action'] == 'sprintcall')) {
    $result = $con->run("SELECT sprint_id, sprint_name FROM sprint_data WHERE project_id = ? ORDER BY sprint_id DESC", array($_GET['projID']));

    if (tep_db_num_rows($result) > 0) {
        $option_list = '';
        while ($row = tep_db_fetch_array($result)) {
            $option_list .= '<option value="' . $row['sprint_id'] . '">' . $row['sprint_name'] . '</option>';
        }
    ?>
        <select name="sprint_id[]" class="form-control select2" id="sprints" multiple="multiple">
            <?php echo $option_list; ?>
        </select>
        <span>OR</span>
        <script src="js/bootstrap-multiselect.js"></script>
        <link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css" />
        <script>
            $('.select2').multiselect({
                nonSelectedText: 'Choose sprints to show in the graph',
                includeSelectAllOption: false,
                buttonWidth: '100%',
                enableFiltering: false
            });
        </script>
<?php
    }
}
