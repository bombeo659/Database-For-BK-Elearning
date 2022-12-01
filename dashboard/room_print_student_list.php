<?php
require_once("../class-user.php");
$auth_user = new USER();
$page_level = 2;
$auth_user->check_accesslevel($page_level);
$query = '';
$query .= "SELECT 
`sd`.`sd_id`,
`sd`.`sd_img`,
`sd`.`sd_studnum`,
`sd`.`sd_fname`,
`sd`.`sd_mname`,
`sd`.`sd_lname`,
`sd`.`sd_gender`,
`sd`.`sd_email`,
`cs`.`class_id`,
`cla`.`class_name`,
`sub`.`subject_name`
";
$query .= " FROM `class_student` `cs`
LEFT JOIN `student_details` `sd` ON `cs`.`sd_id` = `sd`.`sd_id`
Left join `class` `cla` on `cla`.`class_id` = `cs`.`class_id`
left join `subject` `sub` on `cla`.`subject_id` = `sub`.`subject_id`
";

if (isset($_REQUEST['room_ID'])) {
    $room_ID = $_REQUEST['room_ID'];
    $query .= '  WHERE `cs`.`class_id` = ' . $room_ID . ' ';
}

$query .= ' ORDER BY `sd`.`sd_fname` ASC ';

$statement = $auth_user->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$filtered_rows = $statement->rowCount();

if ($filtered_rows > 0) {
    $delimiter = ",";
    

    // Create a file pointer 
    $f = fopen('php://memory', 'w');

    // Set column headers 
    $fields = array('ID', 'FIRST NAME', 'MID NAME', 'LAST NAME', 'EMAIL', 'GENDER');
    fputcsv($f, $fields, $delimiter);

    // Output each row of the data, format line as csv and write to file pointer 
    foreach ($result as $row) {
        $filename = "Members-of-" . $row["class_name"] . '-' . $row["subject_name"] . ".csv";
        $lineData = array($row['sd_studnum'], $row['sd_fname'], $row['sd_mname'], $row['sd_lname'], 
        $row['sd_email'], $row['sd_gender']);
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
