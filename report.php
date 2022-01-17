<?php
require('includes/application_top.php');
include('includes/header.php');

$report_query = $con->run("SELECT p.project_name, s.sprint_name, d.developer_name, sr.issue_key, sr.total_storypoint, sr.completed_storypoint, sr.carryover_storypoint, sr.reopen_storypoint, sr.developer_comments FROM project p, sprint_report sr, sprint_data s, developer d WHERE p.project_id = sr.project_id AND s.sprint_id = sr.sprint_id AND d.developer_id = sr.developer_id ORDER BY sr.sprint_report_id DESC");

$project_query = $con->run("SELECT DISTINCT(p.project_id), p.project_name FROM project p, sprint_report sp WHERE p.project_id = sp.project_id ORDER BY p.project_id DESC");
$project_option_list  = '';
if (tep_db_num_rows($project_query) > 0) {
    while ($p_row = tep_db_fetch_array($project_query)) {
        $selected = (isset($_GET['action']) && ($_GET['action'] == 'filter') &&  ($p_row['project_id'] == $_POST['project_id']) ? 'selected' : '');
        $project_option_list .= '<option value="' . $p_row['project_id'] . '"' . $selected . '>' . $p_row['project_name'] . '</option>';
    }
}
//
if (isset($action) && ($action == 'filter')) {
    $sprint_query = $con->run("SELECT sprint_id, sprint_name FROM sprint_data WHERE project_id = ? ORDER BY sprint_id DESC", array($_POST['project_id']));
    $sprint_option_list  = '';
    if (tep_db_num_rows($sprint_query) > 0) {
        while ($s_row = tep_db_fetch_array($sprint_query)) {
            $selected = (isset($_GET['action']) && ($_GET['action'] == 'filter') &&  ($s_row['sprint_id'] == $_POST['sprint_id']) ? 'selected' : '');
            $sprint_option_list .= '<option value="' . $s_row['sprint_id'] . '"' . $selected . '>' . $s_row['sprint_name'] . '</option>';
        }
    }
    //
    $developer_query = $con->run("SELECT developer_id, developer_name, developer_status FROM developer WHERE project_id = ? ORDER BY developer_name", array($_POST['project_id']));
    $developer_option_list  = '';
    if (tep_db_num_rows($developer_query) > 0) {
        while ($d_row = tep_db_fetch_array($developer_query)) {
            $selected = (isset($_GET['action']) && ($_GET['action'] == 'filter') &&  ($d_row['developer_id'] == $_POST['developer_id']) ? 'selected' : '');
            $developer_option_list .= '<option value="' . $d_row['developer_id'] . '"' . $selected . '>' . $d_row['developer_name'] . '</option>';
        }
    }
    //
    $project_text = ($_POST['project_id'] != '' ? " AND p.project_id= " . $_POST['project_id'] : '');
    $sprint_text = ($_POST['sprint_id'] != '' ? " AND s.sprint_id= " . $_POST['sprint_id'] : '');
    $dev_text = ($_POST['developer_id'] != '' ? " AND d.developer_id= " . $_POST['developer_id'] : '');

    $report_query = $con->run("SELECT p.project_name, s.sprint_name, d.developer_name, sr.issue_key, sr.total_storypoint, sr.completed_storypoint, sr.carryover_storypoint, sr.reopen_storypoint, sr.developer_comments FROM project p, sprint_report sr, sprint_data s, developer d WHERE p.project_id = sr.project_id AND s.sprint_id = sr.sprint_id AND d.developer_id = sr.developer_id" . $project_text . $sprint_text . $dev_text . " GROUP BY p.project_name, s.sprint_name, d.developer_name, sr.issue_key ORDER BY s.sprint_id DESC");
}

?>
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <h4>Report</h4>
        </div>
    </div>
    <form method="POST" action="report.php?action=filter" name="automate-sheet">
        <div class="row mt-3">
            <div class="col-md-4">
                <label for="project">Project: </label>
                <select name="project_id" class="form-control" id="report_project_id">
                    <option value="">Select Project</option>
                    <?php echo $project_option_list; ?>
                </select>
            </div>
            <div class="col-md-8" id="sprint-div">
                <?php if (isset($_GET['action']) && ($_GET['action'] == 'filter')) { ?>
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
                <?php } ?>
            </div>
        </div>
        <div class="row text-right mt-3">
            <div class="col-12 text-right">
                <input type="submit" value="Search" name="search" class="btn" id="btn-submit">
            </div>
        </div>
    </form>
    <?php
    if (tep_db_num_rows($report_query) > 0) {
    ?>
        <hr />
        <div class="row  mb-3">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Sprint Name</th>
                            <th scope="col">Developer Name</th>
                            <th scope="col">Ticket ID</th>
                            <th scope="col">Total Story Point</th>
                            <th scope="col">Status</th>
                            <th scope="col">Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $c = 0;
                        while ($row = tep_db_fetch_array($report_query)) {
                            if ($row["reopen_storypoint"] == 1) {
                                $status = 'Reopen';
                            } else {
                                $status = ($row["carryover_storypoint"] == 1 ? 'Carryover' : 'Completed');
                            }
                        ?>
                            <tr>
                                <th scope="row"><?php echo ++$c; ?> </th>
                                <td><?php echo $row["project_name"]; ?></td>
                                <td><?php echo $row["sprint_name"]; ?></td>
                                <td><?php echo $row["developer_name"]; ?></td>
                                <td><?php echo $row["issue_key"]; ?></td>
                                <td><?php echo $row["total_storypoint"]; ?></td>
                                <td><?php echo $status; ?></td>
                                <td><?php echo $row["developer_comments"]; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    } else {
        echo "No data to display!!";
    }
    ?>
</div>
<?php include_once('includes/footer.php'); ?>