<?php
require_once('../class-function.php');
$acadyear = new DTFunction();           // Create new connection by passing in your configuration array

$query = '';
$output = array();
$query .= "SELECT *,
CONCAT(YEAR(sem_start),' - ',YEAR(sem_end)) semyear  ";
$query .= "FROM `semester` ";
if (isset($_POST["search"]["value"])) {
    $query .= ' WHERE sem_id LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR CONCAT(YEAR(sem_start)," - ",YEAR(sem_end)) LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY sem_id ASC ';
}
if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$statement = $acadyear->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
$i = 1;
foreach ($result as $row) {
    $sub_array = array();
    $stat_ID = $row["status_id"];
    if ($stat_ID == "1") {
        $stat = "<span class='badge badge-success'>Active</span>";
    } else {
        $stat = "<span class='badge badge-danger'>Deactivate</span>";
    }
    $sub_array[] = $i;
    $sub_array[] = $row["sem_id"];
    $sub_array[] =  $row["semyear"];
    $sub_array[] =  $stat;
    $sub_array[] = '
    <div class="" role="group" aria-label="Basic example">
        <button type="button " class="btn btn-info  btn-sm view" id="' . $row["sem_id"] . '">View</button>
        <button type="button " class="btn btn-primary btn-sm edit" id="' . $row["sem_id"] . '">Edit</button>
        <button type="button " class="btn btn-danger  btn-sm delete" id="' . $row["sem_id"] . '">Delete</button>
    </div>
    ';
    $data[] = $sub_array;
    $i++;
}

$q = "SELECT * FROM `semester`";
$filtered_rec = $acadyear->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>     $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
