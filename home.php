<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.html');
}
include('includes/header.php');

$p_sql = "SELECT project_id, project_name FROM project order by project_id desc";
$p_result = tep_db_query($p_sql);
$option_list  = '';
if (tep_db_num_rows($p_result) > 0) {
    while ($p_row = tep_db_fetch_array($p_result)) {
        $selected = (isset($_GET['action']) && ($_GET['action'] === 'view') &&  ($p_row['project_id'] === $_POST['project_id']) ? 'selected' : '');
        $option_list .= '<option value="' . $p_row['project_id'] . '"' . $selected . '>' . $p_row['project_name'] . '</option>';
    }
}

if (isset($_GET['action']) && ($_GET['action'] === 'view')) {
    $sql = "SELECT s.`sprint_id`, p.`project_id`, p.`project_name`, s.`sprint_name`, s.`planned_story_point`, s.`actual_delivered`, s.`v2_delivered`, s.`lt_delivered`, s.`rework`, `lt_reoponed_sp`, `v2_carryover`, `lt_carryover`, `qa_passed`, `v2_reopen_percentage`, s.`lt_reopen_percentage`, s.`v2_carryover_percentage`, s.`lt_carryover_percentage`, s.`planned_vs_completed_ratio` FROM project p, sprint_data s WHERE p.project_id = s.project_id AND p.project_id = '" . $_POST['project_id'] . "'";
    $result = tep_db_query($sql);
}

if (isset($_GET['action']) && ($_GET['action'] === 'deletesprint')) {
    $sql = "DELETE FROM sprint_data WHERE sprint_id = '" . $_GET['sprint_id'] . "'";
    tep_db_query($sql);
    $_SESSION['success'] = "Record deleted successfully!!!";
    tep_redirect('home.php');
}
?>
<div class="container content">
    <h2>Project Sprint</h2>
    <div class="text-right content-1">
        <a href="project.php">
            <button type="button" class="tn btn-primary btn-md"> Project List </button>
        </a>
        <a href="project_edit.php">
            <button type="button" class="tn btn-primary btn-md"> Add Project </button>
        </a>
        <a href="sprint.php">
            <button type="button" class="tn btn-primary btn-md"> Add Sprint </button>
        </a>
    </div>
    <div class="row">
        <div class="col-12">
            <form action="home.php?action=view" method="post">
                <div class="form-group">
                    <label for="project_id">Selcet Project To View Sprint</label>
                    <select name="project_id" id="project_id" class="form-control">
                        <option value="">Select Project</option>
                        <?php echo $option_list; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <?php
    if (isset($_GET['action']) && ($_GET['action'] === 'view')) {
        if (tep_db_num_rows($result) > 0) {
    ?>
            <p class="text-right">
                <a href="export.php?project_id=<?php echo $_POST['project_id']; ?>" target="_blank" rel="noopener noreferrer"><i class="fas fa-file-export"></i> <b>ExportAsExcel</b></a>
            </p>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Sprint Name</th>
                            <th scope="col">Planned Story Point</th>
                            <th scope="col">Actual delivered</th>
                            <th scope="col">V2</th>
                            <th scope="col">LT</th>
                            <th scope="col">Rework</th>
                            <th scope="col">Reopened SP (LT)</th>
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
                        while ($row = tep_db_fetch_array($result)) {
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
            <hr />
    <?php
        } else {
            echo "No data to display!!";
        }
    }
    ?>
</div>
<script type="text/javascript">
    function deleteConfirm(id) {
        //console.log(id);
        if (confirm("Are you sure to delete this record?")) {
            location.href = "home.php?action=deletesprint&sprint_id=" + id;
        }
    }
    $(document).ready(function() {
        $('#project_id').on('change', function() {
            this.form.submit();
        });
    });
</script>

<?php include_once('includes/footer.php'); ?>