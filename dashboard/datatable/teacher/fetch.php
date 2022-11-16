<?php
require_once('../class-function.php');

$teacher = new DTFunction();           // Create new connection by passing in your configuration array
$query = '';
$output = array();
$query .= "SELECT 
`rid`.`rid_ID`,
`rid`.`rid_Img`,
`rid`.`rid_EmpID`,
`rid`.`rid_FName`,
`rid`.`rid_MName`,
`rid`.`rid_LName`,
`rid`.`user_ID`,
`rs`.`sex_Name`,
`rm`.`marital_Name`,
`sf`.`suffix` ";
$query .= " FROM `record_instructor_details` `rid`
LEFT JOIN `ref_marital` `rm` ON `rm`.`marital_ID` = `rid`.`marital_ID`
LEFT JOIN `ref_sex` `rs` ON `rs`.`sex_ID` = `rid`.`sex_ID`
LEFT JOIN `ref_suffixname` `sf` ON `sf`.`suffix_ID` = `rid`.`suffix_ID`";

if (isset($_POST["search"]["value"])) {
    $query .= ' WHERE rid_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rid_EmpID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rid_FName LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rid_MName LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rid_LName LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR suffix LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sex_Name LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY rid_ID ASC ';
}

if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $teacher->runQuery($query);
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

    if ($row["rid_MName"] == " " || $row["rid_MName"] == NULL || empty($row["rid_MName"])) {
        $mname = " ";
    } else {
        $mname = $row["rid_MName"] . '. ';
    }

    if (empty($row["user_ID"])) {
        $reg = "<span class='badge badge-danger'>Unregistered</span>";
        $acreg = "UN";

        $btnrg = '
		<button type="button" class="btn btn-success btn-sm gen_account" id="' . $row["rid_ID"] . '"><i class="icon-key" style="font-size: 20px;"></i></button>';
    } else {
        $reg = "<span class='badge badge-success'>Registered</span>";
        $acreg = "RG";
        $btnrg = '';
    }
    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $row["rid_ID"];
    $sub_array[] =  $row["rid_EmpID"];
    $sub_array[] =  $row["rid_FName"] . ' ' . $mname . $row["rid_LName"] . ' ' . $suffix;
    $sub_array[] =  $row["sex_Name"];
    $sub_array[] =  $row["marital_Name"];
    $sub_array[] =  $reg;
    $sub_array[] = '
    <div class="" role="group" aria-label="Basic example" >
        <button type="button" class="btn btn-info btn-sm view" id="' . $row["rid_ID"] . '">View</button>
        <button type="button" class="btn btn-primary btn-sm edit" acreg="' . $acreg . '"  id="' . $row["rid_ID"] . '">Edit</button>
        <button type="button" class="btn btn-danger btn-sm delete" id="' . $row["rid_ID"] . '">Delete</button>
        ' . $btnrg . '
    </div>';

    $data[] = $sub_array;
    $i++;
}

$q = "SELECT * FROM `record_instructor_details`";
$filtered_rec = $teacher->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
