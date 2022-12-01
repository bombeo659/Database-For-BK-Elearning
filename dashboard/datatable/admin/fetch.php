<?php
require_once('../class-function.php');
$student = new DTFunction();           // Create new connection by passing in your configuration array


$query = '';
$output = array();
$query .= " 
SELECT 
`ad`.`ad_id`,
`ad`.`ad_img`,
`ad`.`ad_empid`,
`ad`.`ad_fname`,
`ad`.`ad_mname`,
`ad`.`ad_lname`,
`ad`.`ad_gender`,
`ad`.`ad_email`,
`ad`.`ad_bday`,
`ad`.`ad_address`,
`aha`.`user_id`
";
// `rm`.`marital_Name`,
// `sf`.`suffix`
$query .= " FROM `admin_details` `ad`
LEFT JOIN `admins_has_account` `aha` ON `aha`.`ad_id` = `ad`.`ad_id`";
// LEFT JOIN `ref_marital` `rm` ON `rm`.`marital_ID` = `ad`.`marital_ID`";
// LEFT JOIN `ref_sex` `rs` ON `rs`.`sex_ID` = `ad`.`sex_ID`
// LEFT JOIN `ref_suffixname` `sf` ON `sf`.`suffix_ID` = `ad`.`suffix_ID`

if (isset($_POST["search"]["value"])) {
    // $query .= ' WHERE ad_id LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' WHERE ad_empid LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR ad_fname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR ad_mname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR ad_lname LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR ad_gender LIKE "%' . $_POST["search"]["value"] . '%" ';
    // $query .= ' OR suffix LIKE "%' . $_POST["search"]["value"] . '%" ';
    // $query .= ' OR sex_Name LIKE "%' . $_POST["search"]["value"] . '%" ';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY ad_fname ASC ';
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
    // if ($row["suffix"] == "N/A") {
    //     $suffix = "";
    // } else {
    //     $suffix = $row["suffix"];
    // }

    if ($row["ad_mname"] == " " || $row["ad_mname"] == NULL || empty($row["ad_mname"])) {
        $mname = "";
    } else {
        $mname = $row["ad_mname"];
    }

    if (empty($row["user_id"])) {
        $reg = "<span class='badge badge-danger'>Unregistered</span>";
        $acreg = "UN";
        $btnrg = '
		<button type="button" class="btn btn-success btn-sm gen_account" id="' . $row["ad_id"] . '">
            <i class="icon-key" style="font-size: 20px;"></i>
        </button>';
    } else {
        $reg = "<span class='badge badge-success'>Registered</span>";
        $acreg = "RG";
        $btnrg = '';
    }
    $sub_array = array();


    $sub_array[] = $i;
    $sub_array[] = $row["ad_id"];
    $sub_array[] =  $row["ad_empid"];
    $sub_array[] =  $row["ad_fname"] . ' ' . $row["ad_lname"] . ' ' . $mname;
    $sub_array[] =  $row["ad_gender"];
    $sub_array[] =  $row["ad_email"];
    $sub_array[] =  $row["ad_bday"];
    $sub_array[] =  $row["ad_address"];
    // $sub_array[] =  $row["marital_Name"];
    $sub_array[] =  $reg;
    $sub_array[] = '
    <div class="" role="group" aria-label="Basic example" >
        <button type="button" class="btn btn-info btn-sm view" id="' . $row["ad_id"] . '">View</button>
        <button type="button" class="btn btn-primary btn-sm edit" acreg="' . $acreg . '" id="' . $row["ad_id"] . '">Edit</button>
        <button type="button" class="btn btn-danger btn-sm delete" id="' . $row["ad_id"] . '">Delete</button>
        ' . $btnrg . '
    </div>
    ';
    $data[] = $sub_array;
    $i++;
}

$q = "SELECT * FROM `admin_details`";
$filtered_rec = $student->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
