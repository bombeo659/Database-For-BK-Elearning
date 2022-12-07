<?php
require_once('../class-function.php');
$account = new DTFunction();

if (isset($_POST['action'])) {
    $output = array();
    $stmt = $account->runQuery("SELECT * FROM `test`WHERE test_id =  '" . $_POST["test_ID"] . "' 
			LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row) {
        $output["test_ID"] = $row["test_id"];
        $output["test_Name"] = $row["test_name"];
        $output["test_Added"] = $row["test_added"];
        $output["test_Expired"] = date('Y-m-d\TH:i:s', strtotime($row["test_expired"]));
        $output["test_Timer"] = $row["test_timer"];
        $output["status_ID"] = $row["status_id"];
        $output["tstt_ID"] = $row["tt_id"];
    }
    echo json_encode($output);
}
