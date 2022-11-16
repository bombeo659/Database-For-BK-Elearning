<?php
require_once('../class-function.php');
$room = new DTFunction();           // Create new connection by passing in your configuration array
session_start();

$query = '';
$output = array();
$query .= "SELECT * ";
$query .= " FROM `room_module_topic` rmt";

if (isset($_REQUEST['mod_ID'])) {
    $mod_ID = $_REQUEST['mod_ID'];
    $query .= ' WHERE rmt.mod_ID = ' . $mod_ID . ' AND';
} else {
    $query .= ' WHERE';
}
if (isset($_POST["search"]["value"])) {
    $query .= ' (mtopic_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR mtopic_Title LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY mtopic_ID DESC ';
}

if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $room->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
$i = 1;

foreach ($result as $row) {
    $sub_array = array();
    $subtopic = $room->subtopic($row["mtopic_ID"]);
    if ($room->student_level()) {
        $btnx = '';
    } else {
        $btnx = '
        <div class="btn-group float-right">
            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Action
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item add_subtopic"  id="' . $row["mtopic_ID"] . '">Add Subtopic</a>
                <a class="dropdown-item edit_topic"  id="' . $row["mtopic_ID"] . '">Edit</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item delete_topic" id="' . $row["mtopic_ID"] . '">Delete</a>
            </div>
        </div>
        ';  
    }
    $sub_array[] = '
    <div class="card">
    <div class="card-header" id="heading' . $i . '">
        <h5 class="mb-0">
        <div >
            ' . $row["mtopic_Title"] . '
            ' . $btnx . '
        </div>
        </h5>
    </div>

    <div id="collapse' . $i . '" class="collapse show" aria-labelledby="heading' . $i . '" data-parent="#accordionExample' . $i . '">
        <div class="card-body">
        <ul class="list-group list-group-flush">
        ' . $room->subtopic($row["mtopic_ID"]) . '
            </ul>
        </div>
    </div>
    </div>
    ';
    $i++;
    $data[] = $sub_array;
}

$q = "SELECT * FROM `room`";
$filtered_rec = $room->get_total_all_records($q);

$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>     $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
