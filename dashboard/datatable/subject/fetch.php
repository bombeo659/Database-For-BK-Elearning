<?php
require_once('../class-function.php');
$subject = new DTFunction();           // Create new connection by passing in your configuration array


$query = '';
$output = array();
$query .= "SELECT *,
CONCAT(YEAR(sem.sem_start),' - ',YEAR(sem.sem_end)) semyear ";
$query .= "FROM `subject` `sub` 
LEFT JOIN `faculty` `fac` ON `fac`.`faculty_id` = `sub`.`faculty_id` 
LEFT JOIN `semester` `sem` ON `sem`.`sem_id` = `sub`.`sem_id`";

if (isset($_POST["search"]["value"])) {
    $query .= ' WHERE subject_id LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR subject_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sem_start LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sem_end LIKE "%' . $_POST["search"]["value"] . '%" ';
}

$query .= ' ORDER BY status_id ASC, sem_end DESC, fac.faculty_name ASC,  ';
if (isset($_POST["order"])) {
    $query .= $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' subject_name ASC ';
}


if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $subject->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
$i = 1;
foreach ($result as $row) {


    $sub_array = array();

    $stat_ID = $row["status_id"];
    if ($stat_ID == "1") {
        $stat = "<span class='badge badge-success'>Activate</span>";
    } else {
        $stat = "<span class='badge badge-danger'>Deactivate</span>";
    }


    $sub_array[] = $i;
    $sub_array[] = $row["subject_id"];
    $sub_array[] =  $row["subject_name"];
    $sub_array[] =  $row["faculty_name"];
    $sub_array[] = $row["semyear"];
    $sub_array[] = $stat;
    $sub_array[] = '
    <div>
        <button type="button view" class="btn btn-primary btn-sm room" id="' . $row["subject_id"] . '" data-toggle="modal" data-target="#room">View Room</button>
        <button type="button edit" class="btn btn-info btn-sm edit" id="' . $row["subject_id"] . '">Edit</button>
        <button type="button" class="btn btn-danger btn-sm delete" id="' . $row["subject_id"] . '">Delete</button>
    </div>
	';
    $data[] = $sub_array;
    $i++;
}

$q = "SELECT * FROM `subject`";
$filtered_rec = $subject->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);

echo json_encode($output);