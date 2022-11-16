<?php 
session_start();
$test_ID = $_POST["test_ID"];

$output = array();
if(isset($_SESSION["timmer".$test_ID])){
	
	$output["remaining"] = $_SESSION["timmer".$test_ID] = $_SESSION["timmer".$test_ID]  - 1000;
	if($output["remaining"] < 0){
		unset($_SESSION["timmer".$test_ID]);
	}
}
else{

	$output["remaining"] = $_SESSION["timmer".$test_ID] = $_POST["timmerutc"];
}

	echo json_encode($output);

?>