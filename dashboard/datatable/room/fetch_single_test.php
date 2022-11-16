<?php
require_once('../class.function.php');
$account = new DTFunction(); 

if (isset($_POST['action'])) {
	
	$output = array();
	$stmt = $account->runQuery("SELECT * FROM `room_test` WHERE test_ID  = '".$_POST["test_ID"]."' 
			LIMIT 1");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $row)
	{
		
		$output["test_ID"] = $row["test_ID"];
		$output["test_Name"] = $row["test_Name"];
		$output["test_Added"] = $row["test_Added"];
		// $output["test_Expired"] = $row["test_Expired"];
	    $test_Expired = strtotime($row["test_Expired"]);
		$output["test_Expired"] = date('Y-m-d\TH:i:sP', $test_Expired);
		
		$output["test_Timer"] = $row["test_Timer"];
		$output["status_ID"] = $row["status_ID"];
		$output["tstt_ID"] = $row["tstt_ID"];
		
	
	}
	
	echo json_encode($output);
	
}









 

?>