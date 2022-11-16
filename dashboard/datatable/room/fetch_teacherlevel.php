<?php
require_once('../class-function.php');

// Create new connection by passing in your configuration array
$room = new DTFunction();
session_start();

$query = '';
$output = array();

$query .= "SELECT 
rm.room_ID,
rid.rid_FName,
rid.rid_MName,
rid.rid_LName,
rsn.suffix,
CONCAT(rid.rid_FName,' ', rid.rid_MName,' ', rid.rid_LName,' ', rsn.suffix) room_adviser,
sec.section_Name,
CONCAT(YEAR(sem.sem_start),' - ',YEAR(sem.sem_end)) semyear ,
stat.status_Name";

$query .= " FROM `room` `rm`
LEFT JOIN `ref_section` `sec` ON `sec`.`section_ID` = `rm`.`section_ID`
LEFT JOIN `record_instructor_details` `rid` ON `rid`.`rid_ID` = `rm`.`rid_ID`
LEFT JOIN `ref_suffixname` `rsn` ON `rsn`.`suffix_ID` = `rid`.`suffix_ID`
LEFT JOIN `ref_semester` `sem` ON sem.sem_ID = `rm`.`sem_ID`
LEFT JOIN `ref_status` `stat` ON `stat`.`status_ID` = `rm`.`status_ID`";

if (isset($_SESSION['user_ID'])) {
    $user_ID = $_SESSION['user_ID'];
    $query .= ' WHERE rid.user_ID = ' . $user_ID . ' AND ';
} else {
    $query .= ' WHERE ';
}

if (isset($_POST["search"]["value"])) {
    $query .= ' (section_Name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR status_Name LIKE "%' . $_POST["search"]["value"] . '%" )';
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
    if ($row["suffix"] == "N/A") {
        $suffix = "";
    } else {
        $suffix = $row["suffix"];
    }

    if ($row["rid_MName"] == " " || $row["rid_MName"] == NULL || empty($row["rid_MName"])) {
        $mname = " ";
    } else {
        $mname = $row["rid_MName"] . '. ';
    }

    $sub_array = array();
    $sub_array[] = $row["room_ID"];
    $sub_array[] =  $row["rid_FName"] . ' ' . $mname . $row["rid_LName"] . ' ' . $suffix;
    $sub_array[] = $row["section_Name"];
    $sub_array[] = $row["semyear"];
    $sub_array[] = $row["status_Name"];
    $sub_array[] = '
    <a  class="btn btn-secondary btn-sm"  href="room_announcement?room_ID=' . $row["room_ID"] . '">View</a>';
    $data[] = $sub_array;
}

$q = "SELECT * FROM `room`";
$filtered_rec = $room->get_total_all_records($q);

$output = array(
    "draw"              =>  intval($_POST["draw"]),
    "recordsTotal"      =>  $filtered_rows,
    "recordsFiltered"   =>  $filtered_rec,
    "data"              =>  $data
);
echo json_encode($output);
