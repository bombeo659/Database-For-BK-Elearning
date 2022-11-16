<?php
require_once('../class-function.php');
$room = new DTFunction();

if (isset($_POST['action'])) {
    $output = array();
    $stmt = $room->runQuery("SELECT * FROM `room_module` WHERE mod_ID  = '" . $_POST["module_ID"] . "' 
			LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row) {
        $output["module_ID"] = $row["mod_ID"];
        $output["mod_Title"] = $row["mod_Title"];
    }

    echo json_encode($output);
}
