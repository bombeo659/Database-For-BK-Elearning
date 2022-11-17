<?php
require_once('../class-function.php');
$student = new DTFunction();           // Create new connection by passing in your configuration array

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
`sha`.`user_id`
";
$query .= " FROM `student_details` `sd`
LEFT JOIN `students_has_account` `sha` ON `sha`.`sd_id` = `sd`.`sd_id`";

$query .= ' WHERE ';

if (isset($_POST["search"]["value"])) {
    // $query .= ' sd_id LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' sd_studnum LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sd_fname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sd_mname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sd_lname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sd_gender LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY sd_fname ASC ';
}

if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $student->runQuery($query);
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

    if (empty($row["user_id"])) {
        $reg = "<span class='badge badge-danger'>Unregistered</span>";
        $acreg = "UN";
        $btnrg = '
		 <button type="button" class="btn btn-success btn-sm gen_account" id="' . $row["sd_id"] . '"><i class="icon-key" style="font-size: 20px;"></i></button>';
    } else {
        $reg = "<span class='badge badge-success'>Registered</span>";
        $acreg = "RG";
        $btnrg = '';
    }
    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $row["sd_id"];
    $sub_array[] =  $row["sd_studnum"];
    $sub_array[] =  $row["sd_fname"] . ' ' . $row["sd_lname"] . ' ' .$mname;
    $sub_array[] =  $row["sd_gender"];
    $sub_array[] =  $reg;

    $sub_array[] = '
    <div class="" role="group" aria-label="Basic example" >
        <button type="button" class="btn btn-info btn-sm view" id="' . $row["sd_id"] . '">View</button>
        <button type="button" class="btn btn-primary btn-sm edit" acreg="' . $acreg . '"  id="' . $row["sd_id"] . '">Edit</button>
        <button type="button" class="btn btn-danger btn-sm delete" id="' . $row["sd_id"] . '">Delete</button>
        ' . $btnrg . '
    </div>
    ';
    $data[] = $sub_array;
    $i++;
}

$q = "SELECT * FROM `student_details`";
$filtered_rec = $student->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>     $filtered_rows,
    "recordsFiltered"    =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
