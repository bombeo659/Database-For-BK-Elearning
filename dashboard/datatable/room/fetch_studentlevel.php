<?php
require_once('../class-function.php');

// Create new connection by passing in your configuration array
$room = new DTFunction();
session_start();

$query = '';
$output = array();

$query .= "SELECT rm.room_ID, sec.section_Name ";
$query .= " FROM `room_student` rs 
LEFT JOIN `room` `rm` ON `rm`.`room_ID` = `rs`.`room_ID` 
LEFT JOIN `record_student_details` `rsd` ON `rsd`.`rsd_ID` = `rs`.`rsd_ID` 
LEFT JOIN `ref_section` `sec` ON `sec`.`section_ID` = `rm`.`section_ID` ";

if (isset($_SESSION['user_ID'])) {
    $user_ID = $_SESSION['user_ID'];
    $query .= ' WHERE rsd.user_ID = ' . $user_ID . ' AND';
} else {
    $query .= ' WHERE';
}

if (isset($_POST["search"]["value"])) {
    $query .= '(section_Name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR section_Name LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY room_ID ASC ';
}

if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $room->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach ($result as $row) {
    $sub_array = array();
    $sub_array[] = $row["room_ID"];
    $sub_array[] = $row["section_Name"];
    $sub_array[] = '
    <a  class="btn btn-secondary btn-sm"  href="room_announcement?room_ID=' . $row["room_ID"] . '">View</a>';
    $data[] = $sub_array;
}

$q = "SELECT * FROM `room`";
$filtered_rec = $room->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>     $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
