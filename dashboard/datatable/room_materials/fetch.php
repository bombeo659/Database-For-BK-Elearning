<?php
require_once('../class-function.php');
$account = new DTFunction();           // Create new connection by passing in your configuration array
session_start();

$query = '';
$output = array();
$query .= "SELECT *";
$query .= "FROM `attachment` ";

if (isset($_REQUEST['room_ID']) || isset($_REQUEST['mod_ID'])) {
    $room_ID = $_REQUEST['room_ID'];
    $mod_ID = $_REQUEST['mod_ID'];
    $query .= '  WHERE class_id = ' . $room_ID . ' AND module_id = ' . $mod_ID . ' AND';
} else {
    $query .= ' WHERE';
}
if (isset($_POST["search"]["value"])) {
    // $query .= '(attachment_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= '( attachment_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= 'OR attachment_mime LIKE "%' . $_POST["search"]["value"] . '%" )';
    // $query .= 'OR attachment_Date LIKE "%' . $_POST["search"]["value"] . '%" )';
}


if (isset($_POST["order"])) {
    $query .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY attachment_name ASC ';
}
if ($_POST["length"] != -1) {
    $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$statement = $account->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
$i = 1;
foreach ($result as $row) {

    $attachment_Name = json_decode($row["attachment_name"]);

    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $row["attachment_id"];
    $sub_array[] = $attachment_Name[0];
    $sub_array[] = $row["attachment_mime"];
    $dl = 'href="data:' . $row["attachment_mime"] . ';base64,' . base64_encode($row['attachment_data']) . '"';
    if ($account->student_level()) {
        $btnx = '<a class="btn btn-success btn-sm"  ' . $dl . ' download="">Download</a>';
    // <a class="btn btn-info btn-sm"  href="preview?attachment=' . $row["attachment_id"] . '" target="_BLANK">Preview</a>';
    } else {
        $btnx = '<a class="btn btn-success btn-sm"  ' . $dl . ' download="">Download</a>
        <button type="button" class="btn btn-danger btn-sm delete_material" id="' . $row["attachment_id"] . '">Delete</button>
        ';
        // <a class="btn btn-info btn-sm"  href="preview?attachment=' . $row["attachment_id"] . '" target="_BLANK">Preview</a>
    }
    $sub_array[] = '<div  aria-label="Basic example">' . $btnx . '</div>';
    $data[] = $sub_array;
    $i++;
}

$q = "SELECT * FROM `attachment`";
$filtered_rec = $account->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>     $filtered_rows,
    "recordsFiltered"    =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
