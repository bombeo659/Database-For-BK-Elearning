<?php
require_once('../class-function.php');
$room = new DTFunction();

if (isset($_POST['operation'])) {
    $output = array();
    $stmt = $room->runQuery("SELECT * FROM `post` WHERE post_id  = '" . $_POST["post_ID"] . "' LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row) {
        $output["post_ID"] = $row["post_id"];
        $output["post_Name"] = $row["post_name"];
        $output["post_Description"] = $row["post_description"];
    }
    echo json_encode($output);
}
