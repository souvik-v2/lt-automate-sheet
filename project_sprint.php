<div class="filter-details chart">
    <form action="home.php?action=view" method="post">
        <div class="row mt-3 filter-box">
            <div class="col-4">
                <select name="project_id" id="project_id" class="browser-default custom-select custom-select-lg mb-3">
                    <option value="">Select project for sprint view</option>
                    <?php echo $option_list; ?>
                </select>
            </div>
            <div class="col-6 sprint-box">
                <?php if (isset($_POST['default'])) { ?>
                    <select name="sprint_id[]" class="form-control select2" id="sprints" multiple="multiple">
                        <?php echo $sprint_option_list; ?>
                    </select>
                    <span>OR</span>
                <?php } ?>
            </div>
            <div class="col-2 text-right">
                <input type="hidden" name="default" value="1">
                <button type="submit" class="btn">Show Graph</button>
            </div>
        </div>
    </form>
</div>

<?php
if (isset($action, $_POST['project_id']) && ($action == 'view')) {
    if (tep_db_num_rows($result) > 0) {
?>
        <div class="row mb-3 mt-3">
            <div class="col-12">
                <p class="text-right">
                    <a href="javascript:void(0)" onclick="callAjax('<?php echo $_POST['project_id']; ?>')"><i class="fas fa-file-export"></i> <b>ExportAsExcel</b></a>
                </p>
            </div>
        </div>
        <?php
        $p_query = $con->run("SELECT `project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status`, `is_sprint` FROM project WHERE project_id = ?", array($_POST['project_id']));
        $prow = tep_db_fetch_array($p_query); ?>
        <div class="project-details chart">
            <div class="heading">
                <h4>PROJECT HEALTH DASHBOARD</h4>
            </div>
            <div class="project-dashboard">
                <div class="pr-left">
                    <table class="table">
                        <tr>
                            <th>Project Name</th>
                            <td><?php echo $prow["project_name"]; ?></td>
                        </tr>
                        <tr>
                            <th>Delivery Manager</th>
                            <td><?php echo $prow["delivery_manager"]; ?></td>
                        </tr>
                        <tr>
                            <th>Project Manager</th>
                            <td><?php echo $prow["project_manager"]; ?></td>
                        </tr>
                        <tr>
                            <th>Client Poc</th>
                            <td><?php echo $prow["client_poc"]; ?></td>
                        </tr>
                        <tr>
                            <th>Client Feedback</th>
                            <td><?php echo $prow["client_feedback"]; ?></td>
                        </tr>
                        <tr>
                            <th>Team Allocation</th>
                            <td colspan="3">-</td>
                        </tr>
                        <tr>
                            <th>Offshore Team</th>
                            <td>Allocated</td>
                            <td><span class="allocated"><?php echo $prow["offshore_team_allocated"]; ?></span></td>
                            <th>Bilable</th>
                            <td><span class="allocated"><?php echo $prow["offshore_team_billable"]; ?></span></td>
                        </tr>
                        <tr>
                            <th>Onsite Team</th>
                            <td>Allocated</td>
                            <td><span class="allocated"><?php echo $prow["onsite_team_allocated"]; ?></span></td>
                        </tr>
                    </table>
                </div>
                <div class="pro-right">
                    <table class="table">
                        <tr>
                            <th>Status Date</th>
                            <td><?php echo  date('d-M-Y', strtotime($prow['status_date'])); ?></td>
                        </tr>
                        <tr>
                            <th>Overall Status</th>
                            <td align="center">
                                <span style="background-color: <?php echo ($prow["overall_status"] == 'amber' ? 'yellow' : $prow["overall_status"]); ?>; color:#000; padding: 8px 20px;">
                                    <?php echo ucfirst($prow["overall_status"]); ?>
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    <?php  }
    if (tep_db_num_rows($result) > 0) {
    ?>

        <div id="filter-graph">
            <div class="row">
                <div class="col-8">
                    <div id="chart-container-1" class="chart">
                        <canvas id="graphCanvas1"></canvas>
                    </div>
                </div>
                <div class="col-4">
                    <div id="chart-container-2" class="chart">
                        <canvas id="graphCanvas2"></canvas>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div id="chart-container-3" class="chart">
                        <canvas id="graphCanvas3"></canvas>
                    </div>
                </div>
                <div class="col-6">
                    <div id="chart-container-4" class="chart">
                        <canvas id="graphCanvas4"></canvas>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div id="chart-container-5" class="chart">
                        <canvas id="graphCanvas5"></canvas>
                    </div>
                </div>
            </div>
        </div>

    <?php
    } else {
    ?>
        <div class="row mb-3 mt-3">
            <div class="col text-center">
                <p>No data to display</p>
            </div>
        </div>
<?php
    }
}
?>
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