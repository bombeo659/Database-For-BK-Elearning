<?php
require_once("../class-user.php");
$auth_user = new USER();
$page_level = 2;
$auth_user->check_accesslevel($page_level);
$query = '';
$query .= "SELECT 
rsd.rsd_StudNum,
rsd.rsd_FName,
rsd.rsd_MName,
rsd.rsd_LName,
rsd.rsd_Email,
sx.sex_Name
";
$query .= " FROM `room_student` `rs`
LEFT JOIN `record_student_details` `rsd` ON `rsd`.`rsd_ID` = `rs`.`rsd_ID`
LEFT JOIN `ref_suffixname` `sn` ON `sn`.`suffix_ID`  = `rsd`.`suffix_ID`
LEFT JOIN `ref_sex` `sx` ON `sx`.`sex_ID` = `rsd`.`sex_ID`";

if (isset($_REQUEST['room_ID'])) {
    $room_ID = $_REQUEST['room_ID'];
    $query .= '  WHERE `rs`.`room_ID` = ' . $room_ID . ' ';
}
$query .= ' ORDER BY rsd.rsd_FName ASC ';

$statement = $auth_user->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$filtered_rows = $statement->rowCount();

if ($filtered_rows > 0) {
    $delimiter = ",";
    $filename = "members-data_" . date('Y-m-d') . ".csv";

    // Create a file pointer 
    $f = fopen('php://memory', 'w');

    // Set column headers 
    $fields = array('ID', 'FIRST NAME', 'MID NAME', 'LAST NAME', 'EMAIL', 'GENDER');
    fputcsv($f, $fields, $delimiter);

    // Output each row of the data, format line as csv and write to file pointer 
    foreach ($result as $row) {
        
        $lineData = array($row['rsd_StudNum'], $row['rsd_FName'], $row['rsd_MName'], $row['rsd_LName'], 
        $row['rsd_Email'], $row['sex_Name']);
        fputcsv($f, $lineData, $delimiter);
    }

    // Move back to beginning of file 
    fseek($f, 0);

    // Set headers to download file rather than displayed 
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    //output all remaining data on a file pointer 
    fpassthru($f);
}
exit;
