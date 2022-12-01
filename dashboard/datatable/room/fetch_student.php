<?php
require_once('../class-function.php');
$class = new DTFunction();           // Create new connection by passing in your configuration array

$query = '';
$output = array();
$query .= "
SELECT 
`sd`.`sd_id`,
`sd`.`sd_img`,
`sd`.`sd_studnum`,
`sd`.`sd_fname`,
`sd`.`sd_mname`,
`sd`.`sd_lname`,
`sd`.`sd_gender`,
`sd`.`sd_email`,
`cs`.`class_id`
";
$query .= " FROM `class_student` `cs`
JOIN `student_details` `sd` ON `cs`.`sd_id` = `sd`.`sd_id`";


if (isset($_REQUEST['room_ID'])) {
    $class_ID = $_REQUEST['room_ID'];
    $query .= ' WHERE cs.class_id = ' . $class_ID . ' AND';
} else {
    $query .= ' WHERE';
}

if (isset($_POST["search"]["value"])) {
    // $query .= ' (res_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' (sd_studnum LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sd_fname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sd_mname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sd_lname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sd_gender LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY sd_fname ASC ';
}
if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $class->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
$i = 1;

foreach ($result as $row) {

    if ($row["sd_mname"] == " " || $row["sd_mname"] == NULL || empty($row["sd_mname"])) {
        $mname = "";
    } else {
        $mname = $row["sd_mname"];
    }

    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $row["sd_id"];
    $sub_array[] =  $row["sd_studnum"];
    $sub_array[] =  $row["sd_fname"] . ' ' . $row["sd_lname"] . ' ' .$mname;
    $sub_array[] =  $row["sd_gender"];
    $sub_array[] =  $row["sd_email"];
    $sub_array[] = '
		<div class="btn-group" role="group" aria-label="Basic example">
		  <button type="button" class="btn btn-danger btn-sm delete" id="' . $row["sd_id"] . '">Delete</button>
		</div>
		';
    $i++;
    $data[] = $sub_array;
}

$q = "SELECT * FROM `class_student` rm";
if (isset($class_ID)) {
    $q .= '  WHERE `rm`.`class_id` = ' . $class_ID . ' ';
}
$filtered_rec = $class->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);

echo json_encode($output);