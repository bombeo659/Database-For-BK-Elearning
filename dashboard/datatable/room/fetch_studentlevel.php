<?php
require_once('../class-function.php');

// Create new connection by passing in your configuration array
$room = new DTFunction();
session_start();

$query = '';
$output = array();

$query .= "SELECT
`cla`.`class_id`, `cla`.`status_id`, 
CONCAT(`cla`.`class_name`,' - ',`sub`.`subject_name`) classname,
CONCAT(`ind`.`ind_fname`,' ', `ind`.`ind_lname`,' ', `ind`.`ind_mname`) class_adviser,
CONCAT(YEAR(`sem`.`sem_start`),' - ',YEAR(`sem`.`sem_end`)) semyear
from `class_student` `cs`
Left join `class` `cla` on `cla`.`class_id` = `cs`.`class_id`
LEFT JOIN `instructor_details` `ind` ON `ind`.`ind_id` = `cla`.`ind_id`
left join `subject` `sub` on `cla`.`subject_id` = `sub`.`subject_id` 
LEFT JOIN `semester` `sem` ON `sem`.`sem_id` = `sub`.`sem_id`";

// where `sd_id` in (select `sd_id` from `students_has_account` where `user_id` = 2);

if (isset($_SESSION['user_id'])) {
    $user_ID = $_SESSION['user_id'];
    $query .= ' where `sd_id` in (select `sd_id` from `students_has_account` where `user_id` = ' . $user_ID . ') AND';
} else {
    $query .= ' WHERE';
}

if (isset($_POST["search"]["value"])) {
    $query .= '(class_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR subject_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR ind_lname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR sem_start LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR sem_end LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR ind_fname LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY class_id ASC ';
}

if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $room->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
$i = 1;
foreach ($result as $row) {
    $stat_ID = $row["status_id"];
    if ($stat_ID == "1") {
        $stat = "<span class='badge badge-success'>Activate</span>";
    } else {
        $stat = "<span class='badge badge-danger'>Deactivate</span>";
    }
    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $row["class_id"];
    $sub_array[] = $row["classname"];
    $sub_array[] = $row["class_adviser"];
    $sub_array[] = $row["semyear"];
    $sub_array[] = $stat;
    $sub_array[] = '
    <a  class="btn btn-secondary btn-sm"  href="room_announcement?room_ID=' . $row["class_id"] . '">View</a>';
    $data[] = $sub_array;
    $i++;
}

$q = "SELECT * from `class_student` `cs` where `sd_id` in (select `sd_id` from `students_has_account` where `user_id` = " . $user_ID .");";
$filtered_rec = $room->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>     $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
