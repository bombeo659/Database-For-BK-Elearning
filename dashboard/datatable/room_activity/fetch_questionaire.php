<?php
require_once('../class-function.php');
$account = new DTFunction();           // Create new connection by passing in your configuration array
// error_reporting(0);

$query = '';
$output = array();
$query .= "SELECT crtq.*,
(SELECT GROUP_CONCAT(crtc.choice_id)  FROM `question_choices` `crtc` WHERE crtc.question_id = crtq.question_id) choice_ID,
(SELECT GROUP_CONCAT(crtc.is_correct)  FROM `question_choices` `crtc` WHERE crtc.question_id = crtq.question_id) is_correct,
(SELECT GROUP_CONCAT(crtc.choice)  FROM `question_choices` `crtc` WHERE crtc.question_id = crtq.question_id) choice";

$query .= " FROM `test_question` `crtq`";

if (isset($_REQUEST['test_ID']) || isset($_REQUEST['type'])) {
    $test_ID = $_REQUEST['test_ID'];
    $test_type = $_REQUEST['type'];
    $query .= ' WHERE crtq.test_id = ' . $test_ID . ' AND crtq.question_type = ' . $test_type . ' AND ';
} else {
    $query .= ' WHERE';
}
if (isset($_POST["search"]["value"])) {
    // $query .= ' (question_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    // $query .= ' ( test_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' ( question LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY question_id ASC ';
}
if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $account->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();

function cbrc($break_d)
{
    if ($break_d == 1) {
        $color = "green";
    } else {
        $color = "red";
    }
    return $color;
}

$num = 1;

foreach ($result as $row) {
    $break_c = explode(",", $row["choice"]);
    $break_d = explode(",", $row["is_correct"]);

    $sub_array = array();
    if (isset($test_type)) {
        if ($test_type == 1) {
            $xas0 = 'edit_question';
            $xas1 = 'delete_question';
            $xas2 = '
			<div class="p-2 bd-highlight" style="color:' . cbrc($break_d[0]) . '"> A. ' . $break_c[0] . '</div>
			<div class="p-2 bd-highlight" style="color:' . cbrc($break_d[1]) . '"> B. ' . $break_c[1] . '</div>
			<div class="p-2 bd-highlight" style="color:' . cbrc($break_d[2]) . '"> C. ' . $break_c[2] . '</div>
			<div class="p-2 bd-highlight" style="color:' . cbrc($break_d[3]) . '"> D. ' . $break_c[3] . '</div>';
        }
        if ($test_type == 2) {
            $xas0 = 'edit_tf';
            $xas1 = 'delete_tf';
            $xas2 = '
			<div class="p-2 bd-highlight" style="color:' . cbrc($break_d[0]) . '"> ' . $break_c[0] . '</div>
			<div class="p-2 bd-highlight" style="color:' . cbrc($break_d[1]) . '"> ' . $break_c[1] . '</div>';
        }
    }
    $sub_array[] = '
    <div class="btn-group float-right">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action </button>
        <div class="dropdown-menu">
            <a class="dropdown-item ' . $xas0 . '"  id="' . $row["question_id"] . '">Edit</a>
                <div class="dropdown-divider"></div>
            <a class="dropdown-item ' . $xas1 . '" id="' . $row["question_id"] . '">Delete</a>
        </div>
    </div>

    <div class="form-group col-md-4">
        <label >' . $num . '.) ' . $row["question"] . '</label>
        <div class="d-flex flex-column bd-highlight mb-3">
        ' . $xas2 . '
        </div>
    </div>
    ';
    $num++;
    $data[] = $sub_array;
}

$q = "SELECT * FROM `test`";
$filtered_rec = $account->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
