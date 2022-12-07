<?php
require_once('../class-function.php');
$acadyear = new DTFunction();

if (isset($_POST['action'])) {

    $output = array();
    $stmt = $acadyear->runQuery("SELECT * FROM `semester` WHERE sem_id  = '" . $_POST["semester_ID"] . "' 
			LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row) {
        $output["sem_ID"] = $row["sem_id"];
        $output["sem_start"] = $row["sem_start"];
        $output["sem_end"] = $row["sem_end"];
        $output["stat_ID"] = $row["status_id"];
    }

    echo json_encode($output);
}
