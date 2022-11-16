<?php
require_once('../class-function.php');
$account = new DTFunction();           // Create new connection by passing in your configuration array
session_start();

$query = '';
$output = array();
$query .= "SELECT `crt`.*,`rs`.`status_Name`,`rtt`.`tstt_Name`";
$query .= " FROM `room_test`  `crt`
LEFT JOIN `ref_status`  `rs` ON `rs`.`status_ID` = `crt`.`status_ID`
LEFT JOIN `ref_test_type`  `rtt` ON `rtt`.`tstt_ID` = `crt`.`tstt_ID`";

if ($account->student_level()) {
    $cxza = " AND rs.status_ID = 1 ";
} else {
    $cxza = "";
}

if (isset($_REQUEST['room_ID'])) {
    $room_ID = $_REQUEST['room_ID'];
    $query .= ' WHERE crt.room_ID = ' . $room_ID . ' ' . $cxza . 'AND';
} else {
    $query .= ' WHERE';
}

if (isset($_POST["search"]["value"])) {
    $query .= ' (test_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR room_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR test_Name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR test_Added LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR test_Expired LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY test_ID ASC ';
}
if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $account->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach ($result as $row) {
    if ($row["status_Name"] == "Enable") {
        $span = "<span class='badge badge-success'>" . $row["status_Name"] . "</span>";
    } else {
        $span = "<span class='badge badge-danger'>" . $row["status_Name"] . "</span>";
    }
    $sub_array = array();
    $sub_array[] = $row["test_ID"];
    // $sub_array[] = $row["room_ID"];
    $sub_array[] = $row["test_Name"];
    $sub_array[] = $row["tstt_Name"];
    $sub_array[] = $row["test_Added"];
    $sub_array[] = $row["test_Expired"];
    if ($row["test_Timer"] > 1) {
        $m = " Mins";
    } else {
        $m = " Min";
    }
    $sub_array[] = $row["test_Timer"] . $m;
    $sub_array[] = $span;

    if ($account->student_level()) {
        $btnx = '
        <button class="btn btn-success btn-sm rounded mr-1 studview_score" id="' . $row["test_ID"] . '" >View Scores</button>
        <a class="btn btn-secondary btn-sm rounded" href="take?test_ID=' . $row["test_ID"] . '&room_ID=' . $room_ID . '" target="_BLANK">Take Test</a>
        ';
    }
    if ($account->admin_level() || $account->instructor_level()) {
        $btnx = '
            <a class="btn btn-secondary btn-sm rounded" href="questionaire?test_ID=' . $row["test_ID"] . '&type=' . $row["tstt_ID"] . '" target="_BLANK">View Q/A</a>
            <button type="button" class="btn btn-info btn-sm rounded mx-1 view_score" id="' . $row["test_ID"] . '">View Scores</button>
            <a  class="btn btn-secondary btn-sm rounded" href="take?test_ID=' . $row["test_ID"] . '&room_ID=' . $room_ID . '" target="_BLANK">Take Test</a>
            <button type="button" class="btn btn-primary btn-sm rounded mx-1 edit" id="' . $row["test_ID"] . '">Edit</button>
            <button type="button" class="btn btn-danger btn-sm rounded delete" id="' . $row["test_ID"] . '">Delete</button>
        ';
    }

    $sub_array[] = '
        <div class="btn-group" role="group" aria-label="Basic example">
	        ' . $btnx . '
        </div>
    ';
    $data[] = $sub_array;
}

$q = "SELECT * FROM `room_test`";
$filtered_rec = $account->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
