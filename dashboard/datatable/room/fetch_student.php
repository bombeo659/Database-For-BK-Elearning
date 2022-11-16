<?php
require_once('../class-function.php');
$room = new DTFunction();           // Create new connection by passing in your configuration array

$query = '';
$output = array();
$query .= "SELECT 
rs.res_ID,
rs.rsd_ID,
rsd.rsd_StudNum,
rsd.rsd_FName,
rsd.rsd_MName,
rsd.rsd_LName,
sn.suffix,
sx.sex_Name
";
$query .= " FROM `room_student` `rs`
LEFT JOIN `record_student_details` `rsd` ON `rsd`.`rsd_ID` = `rs`.`rsd_ID`
LEFT JOIN `ref_suffixname` `sn` ON `sn`.`suffix_ID`  = `rsd`.`suffix_ID`
LEFT JOIN `ref_sex` `sx` ON `sx`.`sex_ID` = `rsd`.`sex_ID`";


if (isset($_REQUEST['room_ID'])) {
    $room_ID = $_REQUEST['room_ID'];
    $query .= ' WHERE rs.room_ID = ' . $room_ID . ' AND';
} else {
    $query .= ' WHERE';
}

if (isset($_POST["search"]["value"])) {
    $query .= ' (res_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rsd_StudNum LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rsd_FName LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rsd_MName LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rsd_LName LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sex_Name LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY rsd.rsd_FName ASC ';
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
    if ($row["suffix"] == "N/A") {
        $suffix = "";
    } else {
        $suffix = $row["suffix"];
    }

    if ($row["rsd_MName"] == " " || $row["rsd_MName"] == NULL || empty($row["rsd_MName"])) {
        $mname = " ";
    } else {
        $mname = substr(ucfirst($row["rsd_MName"]), 0, 1) . '. ';
    }

    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $row["rsd_StudNum"];
    $sub_array[] = addslashes(ucwords(htmlspecialchars($row["rsd_FName"] . ', ' . $row["rsd_LName"] . ' ' . $mname . ' ' . $suffix)));
    $sub_array[] = $row["sex_Name"];
    $sub_array[] = '
		<div class="btn-group" role="group" aria-label="Basic example">
		  <button type="button" class="btn btn-danger btn-sm delete" id="' . $row["res_ID"] . '">Delete</button>
		</div>
		';
    $i++;
    $data[] = $sub_array;
}

$q = "SELECT * FROM `room_student` rm";
if (isset($room_ID)) {
    $q .= '  WHERE `rm`.`room_ID` = ' . $room_ID . ' ';
}
$filtered_rec = $room->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);

echo json_encode($output);