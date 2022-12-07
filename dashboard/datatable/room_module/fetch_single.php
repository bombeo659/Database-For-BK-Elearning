<?php
require_once('../class-function.php');
$room = new DTFunction();

if (isset($_POST['action'])) {
    $output = array();
    $stmt = $room->runQuery("SELECT * FROM `module` WHERE module_id  = '" . $_POST["module_ID"] . "' 
			LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row) {
        $output["module_ID"] = $row["module_id"];
        $output["mod_Title"] = ucwords(strtolower($row["module_title"]));
    }

    echo json_encode($output);
}
