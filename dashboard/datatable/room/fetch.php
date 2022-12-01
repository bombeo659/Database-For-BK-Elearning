<?php
require_once('../class-function.php');
$class = new DTFunction();  // Create new connection by passing in your configuration array

$query = '';
$output = array();

$query .= "SELECT 
cla.class_id,
cla.class_name,
ind.ind_fname,
ind.ind_mname,
ind.ind_lname,
CONCAT(ind.ind_fname,' ', ind.ind_lname,' ', ind.ind_mname) class_adviser,

stat.status_id,
stat.status ";

$query .= " FROM `class` `cla`
LEFT JOIN `instructor_details` `ind` ON `ind`.`ind_id` = `cla`.`ind_id`

LEFT JOIN `status` `stat` ON `stat`.`status_id` = `cla`.`status_id`";

if (isset($_REQUEST['sub_id'])) {
    $sub_id = $_REQUEST['sub_id'];
    $query .= ' WHERE cla.subject_id = ' . $sub_id . ' AND';
} else {
    $query .= ' WHERE ';
}

if (isset($_POST["search"]["value"])) {
    $query .= ' (ind_fname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR ind_mname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR ind_mname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR ind_lname LIKE "%' . $_POST["search"]["value"] . '%" )';
    // $query .= ' OR sem_start LIKE "%' . $_POST["search"]["value"] . '%" ';
    // $query .= ' OR sem_end LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY ind.ind_fname ASC ';
}

if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
// echo $query;
$statement = $class->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
$i = 1;

foreach ($result as $row) {

    if ($row["ind_mname"] == " " || $row["ind_mname"] == NULL || empty($row["ind_mname"])) {
        $mname = " ";
    } else {
        $mname = $row["ind_mname"];
    }
    $stat_ID = $row["status_id"];
    if ($stat_ID == "1") {
        $stat = "<span class='badge badge-success'>Activate</span>";
    } else {
        $stat = "<span class='badge badge-danger'>Deactivate</span>";
    }
    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $row["class_id"];
    $sub_array[] = $row["class_name"];
    $sub_array[] = $row["ind_fname"] . ' ' . $row["ind_lname"] . ' ' . $mname;
    // $sub_array[] = $row["semyear"];
    $sub_array[] = $stat;

    $sub_array[] = '
    <div class="" aria-label="Basic example">
        <a type="button" class="btn btn-secondary btn-sm  " href="room_announcement?room_ID=' . $row["class_id"] . '">View</a>
        <button type="button" class="btn btn-primary btn-sm  edit-room" id="'.$row["class_id"].'">Edit</button>
        <button type="button" class="btn btn-danger btn-sm  delete-room" id="'.$row["class_id"].'">Delete</button>
    </div>';

    $i++;
    $data[] = $sub_array;
}

$q = "SELECT * FROM `class`";
if (isset($_REQUEST['sub_id'])) {
    $sub_id = $_REQUEST['sub_id'];
    $q .= ' WHERE subject_id = ' . $sub_id;
}
$filtered_rec = $class->get_total_all_records($q);
$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
