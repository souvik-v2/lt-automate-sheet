<?php
session_start();
error_reporting(1);

require('includes/db.php');
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loginuser']['loggedin'])) {
    tep_redirect('index.php');
}

//PHP Office
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if(isset($_GET['project_id'])) {
    // Excel file name for download 
    $fileName = "LT-SimplifiedMetrics-" . time(); 

    $project_sql = tep_db_query("SELECT `project_id`, `project_name`, `delivery_manager`, `project_manager`, `client_poc`, `client_feedback`, `team_allocation`, `offshore_team_allocated`, `offshore_team_billable`, `onsite_team_allocated`, `onsite_team_billable`, `status_date`, `overall_status`, `is_sprint` FROM project WHERE project_id ='" . tep_db_input($_GET['project_id']) . "'");

    


    if(tep_db_num_rows($project_sql) > 0) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // project dashboard settings
        $sheet->mergeCells('B3:Q6');
        
        foreach (range('A', 'Z') as $column){
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $sheet->getColumnDimension('A'.$column)->setAutoSize(true);
        }   

        $sheet->getStyle('A:AZ')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A:AZ')->getAlignment()->setVertical('center');
        $sheet->getStyle('C7:C14')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('L7:L14')->getAlignment()->setHorizontal('left');
        $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
        $sheet->getStyle('B3')->getFont()->getColor()->setARGB('3f51b5');
        $sheet->getStyle("C7:C14")->getFont()->setSize(11);
        $sheet->getStyle("L7:L9")->getFont()->setSize(11);
        $sheet->getStyle("B3")->getFont()->setSize(14);
        $sheet->getStyle('C7:C14')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3f51b5');
        $sheet->getStyle('L7:L9')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('3f51b5');
        $sheet->getStyle('C7:C14')->getFont()->getColor()->setARGB('ffffffff');
        $sheet->getStyle('L7:L9')->getFont()->getColor()->setARGB('ffffffff');
        $sheet->getStyle('B2')->getFill()->getStartColor()->setARGB('FFFF0000');
        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        
        $sheet->getStyle('B3:Q16')->applyFromArray($styleArray);        
        
        //SPRINT DASHBOARD SETTING
        $sheet->getStyle("T1:AH1")->getFont()->setSize(11);
        $sheet->getStyle('T')->getAlignment()->setHorizontal('left');
        $styleArray2 = [
            'borders' => [
                'inside' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ]
            ]
         ];
  
        $sheet->getStyle('T1:AH100')->applyFromArray($styleArray2);
        $sheet->getStyle('AJ12:AL17')->applyFromArray($styleArray2);

        //project dashboard title
        $sheet->setCellValue('B3', 'PROJECT HEALTH DASHBOARD');
        $sheet->setCellValue('C7', 'Project Name');
        $sheet->setCellValue('C8', 'Delivery Manager');
        $sheet->setCellValue('C9', 'Project Manager/Lead');
        $sheet->setCellValue('C10', 'Client Poc');
        $sheet->setCellValue('C11', 'Client Feedback');
        $sheet->setCellValue('C12', 'Team Allocation');
        $sheet->setCellValue('C13', 'Offshore Team');
        $sheet->setCellValue('D13', 'Allocated');
        $sheet->setCellValue('G13', 'Billable');
        $sheet->setCellValue('C14', 'Onsite Team');
        $sheet->setCellValue('D14', 'Allocated');
        $sheet->setCellValue('L7', 'Status Date');
        $sheet->setCellValue('L9', 'Overall Status');
        
        $project_data = tep_db_fetch_array($project_sql);
        //project dashboard value
        $sheet->setCellValue('D7', $project_data['project_name']);
        $sheet->setCellValue('D8', $project_data['delivery_manager']);
        $sheet->setCellValue('D9', $project_data['project_manager']);
        $sheet->setCellValue('D10', $project_data['client_poc']);
        $sheet->setCellValue('D11', $project_data['client_feedback']);
        $sheet->setCellValue('D12', $project_data['team_allocation']);
        $sheet->setCellValue('F13', $project_data['offshore_team_allocated']);
        $sheet->setCellValue('I13', $project_data['offshore_team_billable']);
        $sheet->setCellValue('F14', $project_data['onsite_team_allocated']);
        $sheet->setCellValue('N7', date("d-M-Y", strtotime($project_data['status_date'])));
        $sheet->setCellValue('N9', ucfirst($project_data['overall_status']));

        //sprint dashboard title
        $sheet->setCellValue('T1', 'Week Starting On');
        $sheet->setCellValue('U1', 'Planned Story Points');
        $sheet->setCellValue('V1', 'Actual Delivered');
        $sheet->setCellValue('W1', 'V2 Delivered');
        $sheet->setCellValue('X1', 'LT Delivered');
        $sheet->setCellValue('Y1', 'V2 Reopen');
        $sheet->setCellValue('Z1', 'LT Reopen');
        $sheet->setCellValue('AA1', 'V2 Carryover');
        $sheet->setCellValue('AB1', 'LT Carryover');
        $sheet->setCellValue('AC1', 'QA Passed');
        $sheet->setCellValue('AD1', 'V2 Reopen Percentage');
        $sheet->setCellValue('AE1', 'LT Reopen Percentage');
        $sheet->setCellValue('AF1', 'V2 Carryover Percentage');
        $sheet->setCellValue('AG1', 'LT Carryover Percentage');
        $sheet->setCellValue('AH1', 'Planned Vs Completed ratio');

        
        $sheet->setCellValue('AJ2', 'Row Labels');
        $sheet->setCellValue('AK2', 'Sum of V2 Delivered');
        $sheet->setCellValue('AL2', 'Sum of LT Delivered');

        //sprint short table
        $sheet->setCellValue('AJ12', 'Sprint');
        $sheet->setCellValue('AK12', 'V2 Delivered');
        $sheet->setCellValue('AL12', 'LT Delivered');

        // sprint data
        //$flag = ($project_data['is_sprint'] == 1 ? " order by s.sprint_id desc LIMIT 0, 8" : " order by s.sprint_id desc LIMIT 0, 6");

        $sprint_sql = tep_db_query("SELECT s.`sprint_name`, s.`planned_story_point`, s.`actual_delivered`, s.`v2_delivered`, s.`lt_delivered`, s.`rework`, `lt_reoponed_sp`, `v2_carryover`, `lt_carryover`, `qa_passed`, `v2_reopen_percentage`, s.`lt_reopen_percentage`, s.`v2_carryover_percentage`, s.`lt_carryover_percentage`, s.`planned_vs_completed_ratio` FROM project p, sprint_data s WHERE p.project_id = s.project_id AND p.project_id = '" . tep_db_input($_GET['project_id']) . "'");

        //SPRINT VALUE
        $counter = 2;
        $c=13;
        $i=3;
        $v2_delivered = 0;
        $lt_delivered = 0;
        $trow = tep_db_num_rows($sprint_sql);
        foreach($sprint_sql as $sprint_data) {

            $sheet->setCellValue('AJ'.$i, $sprint_data['sprint_name']);
            $sheet->setCellValue('AK'.$i, $sprint_data['v2_delivered']);
            $sheet->setCellValue('AL'.$i, $sprint_data['lt_delivered']);
            $i++;
            $trow--;
            $v2_delivered += $sprint_data['v2_delivered'];
            $lt_delivered += $sprint_data['lt_delivered'];
            if($trow == 0) {
                $n=$i++;
                $sheet->setCellValue('AJ'.$n, 'Grand Total');
                $sheet->setCellValue('AK'.$n, $v2_delivered);
                $sheet->setCellValue('AL'.$n, $lt_delivered);
                $sheet->getStyle("AJ".$n.':AL'.$n)->getFont()->setBold(true);
            }
            //sprint short table
            $sheet->setCellValue('AJ'.$c, $sprint_data['sprint_name']);
            $sheet->setCellValue('AK'.$c, $sprint_data['v2_delivered']);
            $sheet->setCellValue('AL'.$c, $sprint_data['lt_delivered']);
            $c++;

            $sheet->setCellValue('T'.$counter, $sprint_data['sprint_name']);
            $sheet->setCellValue('U'.$counter, $sprint_data['planned_story_point']);
            $sheet->setCellValue('V'.$counter, $sprint_data['actual_delivered']);
            $sheet->setCellValue('W'.$counter, $sprint_data['v2_delivered']);
            $sheet->setCellValue('X'.$counter, $sprint_data['lt_delivered']);
            $sheet->setCellValue('Y'.$counter, $sprint_data['rework']);
            $sheet->setCellValue('Z'.$counter, $sprint_data['lt_reoponed_sp']);
            $sheet->setCellValue('AA'.$counter, $sprint_data['v2_carryover']);
            $sheet->setCellValue('AB'.$counter, $sprint_data['lt_carryover']);
            $sheet->setCellValue('AC'.$counter, $sprint_data['qa_passed']);
            $sheet->setCellValue('AD'.$counter, $sprint_data['v2_reopen_percentage'].'%');
            $sheet->setCellValue('AE'.$counter, $sprint_data['lt_reopen_percentage'].'%');
            $sheet->setCellValue('AF'.$counter, $sprint_data['v2_carryover_percentage'].'%');
            $sheet->setCellValue('AG'.$counter, $sprint_data['lt_carryover_percentage'].'%');
            $sheet->setCellValue('AH'.$counter, $sprint_data['planned_vs_completed_ratio'].'%');
    
            $counter++;
        }
        //chart
        
        

        //write in excel and save in root folder
        $writer = new Xlsx($spreadsheet);
        $writer->save('download/'.$fileName.'.xlsx');
        //download file in locally
        echo $fileName;
    }
}


//echo phpinfo();
?>
