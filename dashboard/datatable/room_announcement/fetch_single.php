<?php
require_once('../class-function.php');
$room = new DTFunction();

if (isset($_POST['operation'])) {
    $output = array();
    $stmt = $room->runQuery("SELECT * FROM `room_post` WHERE post_ID  = '" . $_POST["post_ID"] . "' LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row) {
        $output["post_ID"] = $row["post_ID"];
        $output["post_Name"] = $row["post_Name"];
        $output["post_Description"] = $row["post_Description"];
    }
    echo json_encode($output);
}
