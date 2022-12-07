<?php
require_once('../class-function.php');
$room = new DTFunction();           // Create new connection by passing in your configuration array

if (isset($_REQUEST['test_ID'])) {
    $xtest_ID = $_REQUEST['test_ID'];
}

$query = '';
$output = array();
$query .= "SELECT 
rs.class_id,
rs.sd_id,
rsd.sd_studnum,
rsd.sd_fname,
rsd.sd_mname,
rsd.sd_lname,
rsd.sd_gender,
rsd.sd_email,
sha.user_ID,
IF (rts.score is NULL,'0',rts.score) score,
(SELECT count(test_id) FROM test_question WHERE test_id = $xtest_ID) total_question
";

$query .= "  FROM `class_student` `rs`
Left join `students_has_account` `sha` on `sha`.`sd_id` = `rs`.`sd_id`
LEFT JOIN `student_details` `rsd` ON `rsd`.`sd_id` = `sha`.`sd_id`
LEFT JOIN `test_score` `rts` ON `rts`.`user_id` = `sha`.`user_id`";

if (isset($_REQUEST['room_ID'])) {
    $room_ID = $_REQUEST['room_ID'];
    $query .= ' WHERE rs.class_id  = ' . $room_ID . ' AND';
} else {
    $query .= ' WHERE';
}

if (isset($_POST["search"]["value"])) {
    // $query .= ' (res_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' ( sd_studnum LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sd_fname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sd_mname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sd_lname LIKE "%' . $_POST["search"]["value"] . '%" )  ';
    // $query .= ' OR sd_gender LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY rsd.sd_fname ASC ';
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
    $sub_array = array();

    $sub_array[] = $i;
    $sub_array[] = $row["sd_studnum"];
    $sub_array[] =  $row["sd_fname"] . ' ' . $row["sd_mname"] .' ' . $row["sd_lname"];
    $sub_array[] = $row["score"] . "/" . $row["total_question"];

    $i++;
    $data[] = $sub_array;
}

$q = "SELECT * FROM `test` WHERE class_id = " . $room_ID . ";";
$filtered_rec = $room->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
