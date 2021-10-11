<?php
session_start();

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.html');
}

// Filter the excel data 
function filterData(&$str){ 
    $str = preg_replace("/\t/", "\\t", $str); 
    $str = preg_replace("/\r?\n/", "\\n", $str); 
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
} 
 
// Excel file name for download 
$fileName = "LT-SimplifiedMetrics-" . date('Y-m-d') . ".xls"; 

// Column names 
$fields = array('Week Starting On', 'Planned story points', 'Actual delivered', 'V2', 'LT', 'Rework', 'Reopened SP (LT)', 'V2 Carryover',	'LT Carryover',	'QA Passed', 'V2 Reopen Percentage', 'LT Reopen Percentage', 'V2 Carryover Percentage', 'LT Carryover Percentage', 'Planned Vs Completed ratio'); 
 
// Display column names as first row 
$excelData = implode("\t", array_values($fields)) . "\n"; 
 
// Fetch records from database 
$query = tep_db_query("SELECT p.`project_name`, s.`sprint_name`, s.`planned_story_point`, s.`actual_delivered`, s.`v2_delivered`, s.`lt_delivered`, s.`rework`, `lt_reoponed_sp`, `v2_carryover`, `lt_carryover`, `qa_passed`, `v2_reopen_percentage`, s.`lt_reopen_percentage`, s.`v2_carryover_percentage`, s.`lt_carryover_percentage`, s.`planned_vs_completed_ratio` FROM project p, sprint_data s WHERE p.project_id = s.project_id AND p.project_id = '" . $_GET['project_id'] . "'"); 

if(tep_db_num_rows($query) > 0){ 
    // Output each row of the data 
    while($row = tep_db_fetch_array($query)) { 

        $lineData = array($row['sprint_name'], $row['planned_story_point'], $row['actual_delivered'], $row['v2_delivered'], $row['lt_delivered'], $row['rework'], $row['lt_reoponed_sp'], $row['v2_carryover'], $row['lt_carryover'], $row['qa_passed'], $row['v2_reopen_percentage'].'%', $row['lt_reopen_percentage'].'%', $row['v2_carryover_percentage'].'%', $row['lt_carryover_percentage'].'%', $row['planned_vs_completed_ratio'].'%'); 
        array_walk($lineData, 'filterData'); 
        $excelData .= implode("\t", array_values($lineData)) . "\n"; 
    }

}else{ 
    $excelData .= 'No records found...'. "\n"; 
} 
 
// Headers for download 
header("Content-Type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
 
// Render excel data 
echo $excelData; 
 
exit;
