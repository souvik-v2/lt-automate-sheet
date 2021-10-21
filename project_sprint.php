<div class="row mb-3 mt-3">
        <div class="col-6">
            <form action="home.php?action=view" method="post">
                <div class="form-group">
                    <select name="project_id" id="project_id" class="browser-default custom-select custom-select-lg mb-3">
                        <option value="">Select project for sprint view</option>
                        <?php echo $option_list; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>
    <?php
    if (isset($_GET['action'], $_POST['project_id']) && ($_GET['action'] === 'view')) {
        if (tep_db_num_rows($result) > 0) {
    ?>
        <div class="row mb-3 mt-3">
            <div class="col-12">
                <p class="text-right">
                    <a href="javascript:void(0)" onclick="callAjax('<?php echo $_POST['project_id']; ?>')"><i class="fas fa-file-export"></i> <b>ExportAsExcel</b></a>
                </p>
            </div>
        </div>
            <?php $prow = tep_db_fetch_array($p_result);  ?>
            <div class="project-details">
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
                                <td colspan="3"><?php echo $prow["team_allocation"]; ?></td>
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

            <!--<div class="row mb-3">
                <div class="col">
                    <h4>Sprint Info</h4>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Sprint Name</th>
                                    <th scope="col">Planned Story Point</th>
                                    <th scope="col">Actual Delivered</th>
                                    <th scope="col">V2 Delivered</th>
                                    <th scope="col">LT Delivered</th>
                                    <th scope="col">V2 Reopen</th>
                                    <th scope="col">LT Reopen</th>
                                    <th scope="col">V2 Carryover</th>
                                    <th scope="col">LT Carryover</th>
                                    <th scope="col">QA Passed</th>
                                    <th scope="col">V2 Reopen Percentage</th>
                                    <th scope="col">LT Reopen Percentage</th>
                                    <th scope="col">V2 Carryover Percentage</th>
                                    <th scope="col">LT Carryover Percentage</th>
                                    <th scope="col">Planned Vs Completed ratio</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $c = 0;
                                //while ($row = tep_db_fetch_array($result)) {
                                foreach ($sprint_data as $row) {
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo ++$c; ?> </th>
                                        <td><?php echo $row["sprint_name"]; ?></td>
                                        <td><?php echo $row["planned_story_point"]; ?></td>
                                        <td><?php echo $row["actual_delivered"]; ?></td>
                                        <td><?php echo $row["v2_delivered"]; ?></td>
                                        <td><?php echo $row["lt_delivered"]; ?></td>
                                        <td><?php echo $row["rework"]; ?></td>
                                        <td><?php echo $row["lt_reoponed_sp"]; ?></td>
                                        <td><?php echo $row["v2_carryover"]; ?></td>
                                        <td><?php echo $row["lt_carryover"]; ?></td>
                                        <td><?php echo $row["qa_passed"]; ?></td>
                                        <td><?php echo $row["v2_reopen_percentage"]; ?>%</td>
                                        <td><?php echo $row["lt_reopen_percentage"]; ?>%</td>
                                        <td><?php echo $row["v2_carryover_percentage"]; ?>%</td>
                                        <td><?php echo $row["lt_carryover_percentage"]; ?>%</td>
                                        <td><?php echo $row["planned_vs_completed_ratio"]; ?>%</td>
                                        <td><a href="edit.php?action=editsprint&project_id=<?php echo $row['project_id']; ?>&sprint_id=<?php echo $row['sprint_id']; ?>"><i class="far fa-edit"></i></a> |
                                            <a onclick='deleteConfirm("<?php echo $row['sprint_id']; ?>")' href="javascript:void(0)"><i class="far fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>-->

    <?php
        } else {
            tep_redirect('home.php');
        }
    }
    ?>
