<?php
require_once('../class.function.php');
$section = new DTFunction(); 

if (isset($_POST['action'])) {
	
	$output = array();
	$stmt = $section->runQuery("SELECT * FROM `room` WHERE section_ID = ".$_POST["section_ID"]."");
	$stmt->execute();
	$result = $stmt->fetchAll();
	$output["data"] = "";
	foreach($result as $row)
	{
		$output["data"] .= "<tr>";
		$output["data"] .= "<td>".$row["room_ID"]."</td>";
		$output["data"] .= "<td>".$row["rid_ID"]."</td>";
		$output["data"] .= "<td>".$row["section_ID"]."</td>";
		$output["data"] .= "<td>".$row["status_ID"]."</td>";
		$output["data"] .= "<td>".$row["sem_ID"]."</td>";
		$output["data"] .= "</tr>";

	
	}

	


	
	echo json_encode($output);
	
}









 

?>