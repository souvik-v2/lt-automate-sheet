<?php
require('includes/application_top.php');
include('includes/header.php');

$developer_id = $_SESSION['loginuser']['id'];
$report_query = $con->run("SELECT sr.sprint_report_id, s.sprint_name, d.developer_id, sr.issue_key, sr.total_storypoint, sr.completed_storypoint, sr.carryover_storypoint, sr.reopen_storypoint, sr.developer_comments FROM project p, sprint_report sr, sprint_data s, developer d WHERE p.project_id = sr.project_id AND s.sprint_id = sr.sprint_id AND d.developer_id = sr.developer_id AND d.developer_id = " . $developer_id . " GROUP BY s.sprint_name, sr.issue_key ORDER BY s.sprint_id DESC");

if (isset($action) && ($action == 'comment')) {
    try {
        $result = $con->run("UPDATE sprint_report SET developer_comments = ? WHERE sprint_report_id = ?", array($_POST['developer_comments'], $_POST['sprint_report_id']));
        $_SESSION['success'] = "Comment added successfully!!!";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }

    tep_redirect('developer_home.php');
}
?>
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <h4>Developer Sprint Dashboard</h4>
        </div>
    </div>
    <?php
    if (tep_db_num_rows($report_query) > 0) {
        $total = 0;
        $sp = '';
    ?>
        <hr />
        <div class="row  mb-3">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Sprint Name</th>
                            <th scope="col">Ticket Number</th>
                            <th scope="col">Total Story Point</th>
                            <th scope="col">Status</th>
                            <th scope="col">Comments</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $c = 0;
                        while ($row = tep_db_fetch_array($report_query)) {
                            $ssp = $row['sprint_name'];
                            if ($row["reopen_storypoint"] == 1) {
                                $status = 'Reopen';
                            } else {
                                $status = ($row["carryover_storypoint"] == 1 ? 'Carryover' : 'Completed');
                            }
                            if (($sp == $ssp) || ($c == 0)) {
                                $total += $row["total_storypoint"];
                                $sp = $ssp;
                            } else {
                        ?>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                    <td><b><?php echo $total; ?></b></td>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                            <?php
                                $total = 0;
                                $total += $row["total_storypoint"];
                                $sp = $ssp;
                            }
                            ?>
                            <tr>
                                <th scope="row"><?php echo ++$c; ?> </th>
                                <td><?php echo $row["sprint_name"]; ?></td>
                                <td><?php echo $row["issue_key"]; ?></td>
                                <td><?php echo $row["total_storypoint"]; ?></td>
                                <td><?php echo $status; ?></td>
                                <td>
                                    <?php if (isset($_GET['action'], $_GET['id']) && ($_GET['action'] == 'filter') && ($_GET['id'] == $row["sprint_report_id"])) { ?>
                                        <form method="POST" action="developer_home.php?action=comment" name="comment-sheet">
                                            <textarea name="developer_comments" id="developer_comments" cols="30" rows="10"><?php echo $row["developer_comments"]; ?></textarea>
                                            <input type="hidden" name="sprint_report_id" value="<?php echo $row["sprint_report_id"]; ?>">
                                        <?php } else { ?>
                                            <?php echo $row["developer_comments"]; ?>
                                        <?php } ?>
                                </td>
                                <td>
                                    <?php if (isset($_GET['action'], $_GET['id']) && ($_GET['action'] == 'filter') && ($_GET['id'] == $row["sprint_report_id"])) { ?>
                                        <input class="btn" type="submit" value="Add Comment" name="submit">
                                        </form>
                                    <?php } else { ?>
                                        <a href="developer_home.php?action=filter&id=<?php echo $row['sprint_report_id']; ?>"><i class="far fa-edit"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                            <td><b><?php echo $total; ?></b></td>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<?php include_once('includes/footer.php'); ?>