<?php
require_once('../class-function.php');
$student = new DTFunction();           // Create new connection by passing in your configuration array

$query = '';
$output = array();
$query .= " 
SELECT 
`rsd`.`rsd_ID`,
`rsd`.`user_ID`,
`rsd`.`rsd_Img`,
`rsd`.`rsd_StudNum`,
`rsd`.`rsd_FName`,
`rsd`.`rsd_MName`,
`rsd`.`rsd_LName`,
`rs`.`sex_Name`,
`rm`.`marital_Name`,
`sf`.`suffix`
";
$query .= " FROM `record_student_details` `rsd`
LEFT JOIN `ref_marital` `rm` ON `rm`.`marital_ID` = `rsd`.`marital_ID`
LEFT JOIN `ref_sex` `rs` ON `rs`.`sex_ID` = `rsd`.`sex_ID`
LEFT JOIN `ref_suffixname` `sf` ON `sf`.`suffix_ID` = `rsd`.`suffix_ID`";

if (isset($_POST["search"]["value"])) {
    $query .= ' WHERE rsd_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rsd_StudNum LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rsd_FName LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rsd_MName LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rsd_LName LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR suffix LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sex_Name LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY rsd_FName ASC ';
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
    if ($row["suffix"] == "N/A") {
        $suffix = "";
    } else {
        $suffix = $row["suffix"];
    }

    if ($row["rsd_MName"] == " " || $row["rsd_MName"] == NULL || empty($row["rsd_MName"])) {
        $mname = " ";
    } else {
        $mname = substr($row["rsd_MName"], 0, 1) . '. ';
    }

    if (empty($row["user_ID"])) {
        $reg = "<span class='badge badge-danger'>Unregistered</span>";
        $acreg = "UN";
        $btnrg = '
		 <button type="button" class="btn btn-success btn-sm gen_account" id="' . $row["rsd_ID"] . '"><i class="icon-key" style="font-size: 20px;"></i></button>';
    } else {
        $reg = "<span class='badge badge-success'>Registered</span>";
        $acreg = "RG";
        $btnrg = '';
    }
    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $row["rsd_ID"];
    $sub_array[] =  $row["rsd_StudNum"];
    $sub_array[] =  $row["rsd_FName"] . ' ' . $mname . $row["rsd_LName"] . ' ' . $suffix;
    $sub_array[] =  $row["sex_Name"];
    $sub_array[] =  $row["marital_Name"];
    $sub_array[] =  $reg;

    $sub_array[] = '
    <div class="" role="group" aria-label="Basic example" >
        <button type="button" class="btn btn-info btn-sm view" id="' . $row["rsd_ID"] . '">View</button>
        <button type="button" class="btn btn-primary btn-sm edit" acreg="' . $acreg . '"  id="' . $row["rsd_ID"] . '">Edit</button>
        <button type="button" class="btn btn-danger btn-sm delete" id="' . $row["rsd_ID"] . '">Delete</button>
        ' . $btnrg . '
    </div>
    ';
    $data[] = $sub_array;
    $i++;
}

$q = "SELECT * FROM `record_student_details`";
$filtered_rec = $student->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>     $filtered_rows,
    "recordsFiltered"    =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
