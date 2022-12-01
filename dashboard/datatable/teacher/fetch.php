<?php
require_once('../class-function.php');

$teacher = new DTFunction();           // Create new connection by passing in your configuration array
$query = '';
$output = array();
$query .= "SELECT 
`ind`.`ind_id`,
`ind`.`ind_img`,
`ind`.`ind_empid`,
`ind`.`ind_fname`,
`ind`.`ind_mname`,
`ind`.`ind_lname`,
`ind`.`ind_gender`,
`ind`.`ind_bday`,
`ind`.`ind_address`,
`ind`.`ind_email`,
`iha`.`user_id`";
// -- `rm`.`marital_Name`,
// -- `sf`.`suffix` ";
$query .= " FROM `instructor_details` `ind`
LEFT JOIN `instructors_has_account` `iha` ON `iha`.`ind_id` = `ind`.`ind_id`";
// LEFT JOIN `ref_marital` `rm` ON `rm`.`marital_ID` = `ind`.`marital_ID`
// LEFT JOIN `ref_sex` `rs` ON `rs`.`sex_ID` = `ind`.`sex_ID`
// LEFT JOIN `ref_suffixname` `sf` ON `sf`.`suffix_ID` = `ind`.`suffix_ID`";

if (isset($_POST["search"]["value"])) {
    // $query .= ' WHERE ind_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' WHERE ind_empid LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR ind_fname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR ind_mname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR ind_lname LIKE "%' . $_POST["search"]["value"] . '%" ';
    // $query .= ' OR suffix LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR ind_gender LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY ind_fname ASC ';
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
    // if ($row["suffix"] == "N/A") {
    //     $suffix = "";
    // } else {
    //     $suffix = $row["suffix"];
    // }

    if ($row["ind_mname"] == " " || $row["ind_mname"] == NULL || empty($row["ind_mname"])) {
        $mname = "";
    } else {
        $mname = $row["ind_mname"];
    }

    if (empty($row["user_id"])) {
        $reg = "<span class='badge badge-danger'>Unregistered</span>";
        $acreg = "UN";

        $btnrg = '
		<button type="button" class="btn btn-success btn-sm gen_account" id="' . $row["ind_id"] . '"><i class="icon-key" style="font-size: 20px;"></i></button>';
    } else {
        $reg = "<span class='badge badge-success'>Registered</span>";
        $acreg = "RG";
        $btnrg = '';
    }
    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $row["ind_id"];
    $sub_array[] =  $row["ind_empid"];
    $sub_array[] =  $row["ind_fname"] . ' ' . $row["ind_lname"] . ' ' . $mname;
    $sub_array[] =  $row["ind_gender"];
    // $sub_array[] =  $row["marital_Name"];
    $sub_array[] =  $reg;
    $sub_array[] = '
    <div class="" role="group" aria-label="Basic example" >
        <button type="button" class="btn btn-info btn-sm view" id="' . $row["ind_id"] . '">View</button>
        <button type="button" class="btn btn-primary btn-sm edit" acreg="' . $acreg . '"  id="' . $row["ind_id"] . '">Edit</button>
        <button type="button" class="btn btn-danger btn-sm delete" id="' . $row["ind_id"] . '">Delete</button>
        ' . $btnrg . '
    </div>';

    $data[] = $sub_array;
    $i++;
}

$q = "SELECT * FROM `instructor_details`";
$filtered_rec = $teacher->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
