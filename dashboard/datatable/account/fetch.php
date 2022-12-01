<?php
require_once('../class-function.php');
$student = new DTFunction();           // Create new connection by passing in your configuration array


$query = '';
$output = array();
$query .= "SELECT * ";
$query .= " FROM `user_account` `ua`
LEFT JOIN `user_level` `ul` ON `ul`.`lvl_id` = `ua`.`lvl_id`";

if (isset($_POST["search"]["value"])) {
    $query .= ' WHERE user_id LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR user_name LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY ua.lvl_id DESC ';
}

if ($_POST["length"] != -1) {
    $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $student->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
$i = 1;
foreach ($result as $row) {
    $sub_array = array();

    if ($row["lvl_name"] == "Instructor") {
        $lvlname = "Teacher";
    } else {
        $lvlname = $row["lvl_name"];
    }

    $sub_array[] = $i;
    $sub_array[] = $row["user_id"];
    $sub_array[] =  $lvlname;
    $sub_array[] =  $row["user_name"];
    $sub_array[] =  $row["user_registered"];
    $sub_array[] = '
    <div class="">
        <button type="button" class="btn btn-info btn-sm change" id="' . $row["user_id"] . '" >
            Change Password
        </button>
        <button class="btn btn-danger btn-sm delete_account" id="'.$row["user_id"].'">Delete</button>
    </div>';
    $data[] = $sub_array;
    $i++;
}

$q = "SELECT * FROM `user_account`";
$filtered_rec = $student->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
