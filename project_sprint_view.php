<div class="row mb-3 mt-3">
        <div class="col-4">
            <form action="sprint_view.php?action=view" method="post">
                <div class="form-group">
                    <select name="project_id" id="project_id" class="browser-default custom-select custom-select-lg mb-3">
                        <option value="">Select project for sprint table</option>
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
        <?php  }
        if (tep_db_num_rows($result) > 0) {
        ?>

        <div class="row mb-3">
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
        </div>

    <?php
        } else {
            tep_redirect('home.php');
        }
    }
    ?>
