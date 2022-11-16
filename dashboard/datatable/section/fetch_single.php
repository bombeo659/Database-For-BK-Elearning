<?php
require_once('../class.function.php');
$section = new DTFunction(); 

if (isset($_POST['action'])) {
	
	$output = array();
	$stmt = $section->runQuery("SELECT * FROM `ref_section` WHERE section_ID  = '".$_POST["section_ID"]."' 
			LIMIT 1");
	$stmt->execute();
	$result = $stmt->fetchAll();


	foreach($result as $row)
	{

	
		
		$output["section_ID"] = $row["section_ID"];
		$output["section_Name"] = $row["section_Name"];
	
	}

	


	
	echo json_encode($output);
	
}









 

?>