<?php
require_once('../class-function.php');
$student = new DTFunction();           // Create new connection by passing in your configuration array


$query = '';
$output = array();
$query .= "SELECT * ";
$query .= " FROM `user_account` `ua`
LEFT JOIN `user_level` `ul` ON `ul`.`lvl_ID` = `ua`.`lvl_ID`";

if (isset($_POST["search"]["value"])) {
    $query .= ' WHERE user_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR user_Name LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY ua.lvl_ID DESC ';
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

    if ($row["lvl_Name"] == "Instructor") {
        $lvlname = "Teacher";
    } else {
        $lvlname = $row["lvl_Name"];
    }

    $sub_array[] = $i;
    $sub_array[] = $row["user_ID"];
    $sub_array[] =  $lvlname;
    $sub_array[] =  $row["user_Name"];
    $sub_array[] =  $row["user_Registered"];
    $sub_array[] = '
    <div class="">
        <button type="button" class="btn btn-secondary btn-sm change" id="' . $row["user_ID"] . '" >
            Change Password
        </button>
    </div>';
            // <button class="btn btn-danger btn-sm delete_account" id="'.$row["user_ID"].'">Delete</button>
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
