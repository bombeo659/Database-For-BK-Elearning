<?php
require_once('../class-function.php');
$account = new DTFunction();           // Create new connection by passing in your configuration array
session_start();

$query = '';
$output = array();
$query .= "SELECT `crt`.*,`rs`.`status`,`rtt`.`tt_name`";
$query .= " FROM `test`  `crt`
LEFT JOIN `status`  `rs` ON `rs`.`status_id` = `crt`.`status_id`
LEFT JOIN `test_type`  `rtt` ON `rtt`.`tt_id` = `crt`.`tt_id`";

if ($account->student_level()) {
    $cxza = " AND rs.status_id = 1 ";
} else {
    $cxza = "";
}

if (isset($_REQUEST['room_ID'])) {
    $room_ID = $_REQUEST['room_ID'];
    $query .= ' WHERE crt.class_id = ' . $room_ID . ' ' . $cxza . 'AND';
} else {
    $query .= ' WHERE';
}

if (isset($_POST["search"]["value"])) {
    // $query .= ' (test_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' ( class_id LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR test_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR test_added LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR test_expired LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY test_id ASC ';
}
if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $account->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
$i=1;
foreach ($result as $row) {
    if ($row["status"] == "Enable") {
        $span = "<span class='badge badge-success'>" . $row["status"] . "</span>";
    } else {
        $span = "<span class='badge badge-danger'>" . $row["status"] . "</span>";
    }
    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $row["test_id"];
    // $sub_array[] = $row["room_ID"];
    $sub_array[] = $row["test_name"];
    $sub_array[] = $row["tt_name"];
    $sub_array[] = $row["test_added"];
    $sub_array[] = $row["test_expired"];
    if ($row["test_timer"] > 1) {
        $m = " Mins";
    } else {
        $m = " Min";
    }
    $sub_array[] = $row["test_timer"] . $m;
    $sub_array[] = $span;

    if ($account->student_level()) {
        $btnx = '
        <button class="btn btn-success btn-sm rounded mr-1 studview_score" id="' . $row["test_id"] . '" >View Scores</button>
        <a class="btn btn-secondary btn-sm rounded" href="take?test_ID=' . $row["test_id"] . '&room_ID=' . $room_ID . '" target="_BLANK">Take Test</a>
        ';
    }
    if ($account->admin_level() || $account->instructor_level()) {
        $btnx = '
            <a class="btn btn-secondary btn-sm rounded" href="questionaire?test_ID=' . $row["test_id"] . '&type=' . $row["tt_id"] . '" target="_BLANK">View Q/A</a>
            <button type="button" class="btn btn-info btn-sm rounded mx-1 view_score" id="' . $row["test_id"] . '">View Scores</button>
            <a  class="btn btn-secondary btn-sm rounded" href="take?test_ID=' . $row["test_id"] . '&room_ID=' . $room_ID . '" target="_BLANK">Take Test</a>
            <button type="button" class="btn btn-primary btn-sm rounded mx-1 edit" id="' . $row["test_id"] . '">Edit</button>
            <button type="button" class="btn btn-danger btn-sm rounded delete" id="' . $row["test_id"] . '">Delete</button>
        ';
    }

    $sub_array[] = '
        <div class="btn-group" role="group" aria-label="Basic example">
	        ' . $btnx . '
        </div>
    ';
    $data[] = $sub_array;
}

$q = "SELECT * FROM `test` where class_id = ".$room_ID.";";
$filtered_rec = $account->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
