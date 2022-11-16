<?php
require_once('../class-function.php');
$student = new DTFunction();           // Create new connection by passing in your configuration array


$query = '';
$output = array();
$query .= " 
SELECT 
`rad`.`rad_ID`,
`rad`.`user_ID`,
`rad`.`rad_Img`,
`rad`.`rad_EmpID`,
`rad`.`rad_FName`,
`rad`.`rad_MName`,
`rad`.`rad_LName`,
`rs`.`sex_Name`,
`rm`.`marital_Name`,
`sf`.`suffix`
";
$query .= " FROM `record_admin_details` `rad`
LEFT JOIN `ref_marital` `rm` ON `rm`.`marital_ID` = `rad`.`marital_ID`
LEFT JOIN `ref_sex` `rs` ON `rs`.`sex_ID` = `rad`.`sex_ID`
LEFT JOIN `ref_suffixname` `sf` ON `sf`.`suffix_ID` = `rad`.`suffix_ID`";
if (isset($_POST["search"]["value"])) {
    $query .= ' WHERE rad_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rad_EmpID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rad_FName LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rad_MName LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR rad_LName LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR suffix LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR sex_Name LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY rad_FName ASC ';
}
if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $student->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach ($result as $row) {
    if ($row["suffix"] == "N/A") {
        $suffix = "";
    } else {
        $suffix = $row["suffix"];
    }

    if ($row["rad_MName"] == " " || $row["rad_MName"] == NULL || empty($row["rad_MName"])) {
        $mname = " ";
    } else {
        $mname = $row["rad_MName"] . '. ';
    }

    if (empty($row["user_ID"])) {
        $reg = "<span class='badge badge-danger'>Unregistered</span>";
        $acreg = "UN";
        $btnrg = '
		<button type="button" class="btn btn-success btn-sm gen_account" id="' . $row["rad_ID"] . '">
            <i class="icon-key" style="font-size: 20px;"></i>
        </button>';
    } else {
        $reg = "<span class='badge badge-success'>Registered</span>";
        $acreg = "RG";
        $btnrg = '';
    }
    $sub_array = array();


    $sub_array[] = $row["rad_ID"];
    $sub_array[] =  $row["rad_EmpID"];
    $sub_array[] =  $row["rad_FName"] . ' ' . $mname . $row["rad_LName"] . ' ' . $suffix;
    $sub_array[] =  $row["sex_Name"];
    $sub_array[] =  $row["marital_Name"];
    $sub_array[] =  $reg;
    $sub_array[] = '
    <div class="" role="group" aria-label="Basic example" >
        <button type="button" class="btn btn-info btn-sm view" id="' . $row["rad_ID"] . '">View</button>
        <button type="button" class="btn btn-primary btn-sm edit" acreg="' . $acreg . '" id="' . $row["rad_ID"] . '">Edit</button>
        ' . $btnrg . '
    </div>
    ';
    $data[] = $sub_array;
}

$q = "SELECT * FROM `record_admin_details`";
$filtered_rec = $student->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
