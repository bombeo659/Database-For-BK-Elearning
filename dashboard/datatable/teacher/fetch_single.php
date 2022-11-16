<?php
require_once('../class-function.php');
$teacher = new DTFunction(); 

if (isset($_POST['action'])) {
	
	$output = array();
	$stmt = $teacher->runQuery("SELECT 
`rid`.`rid_ID`,
`rid`.`rid_Img`,
`rid`.`rid_EmpID`,
`rid`.`rid_FName`,
`rid`.`rid_MName`,
`rid`.`rid_LName`,
`rid`.`rid_Bday`,
`rs`.`sex_ID`,
`rm`.`marital_ID`,
`sf`.`suffix_ID`,
`rid`.`rid_Address`,
`rid`.`rid_Email`
FROM `record_instructor_details` `rid`
LEFT JOIN `ref_marital` `rm` ON `rm`.`marital_ID` = `rid`.`marital_ID`
LEFT JOIN `ref_sex` `rs` ON `rs`.`sex_ID` = `rid`.`sex_ID`
LEFT JOIN `ref_suffixname` `sf` ON `sf`.`suffix_ID` = `rid`.`suffix_ID`
WHERE rid.rid_ID =  '".$_POST["teacher_ID"]."' 
			LIMIT 1");
	$stmt->execute();
	$result = $stmt->fetchAll();
	foreach($result as $row)
	{

		if (!empty($row['rid_Img'])) {
		 $s_img = 'data:image/jpeg;base64,'.base64_encode($row['rid_Img']);
		}
		else{
		  $s_img = "../assets/img/users/default.jpg";
		}
		
		$output["teacher_ID"] = $row["rid_ID"];
		$output["teacher_img"] = $s_img;
		$output["teacher_EmpID"] = $row["rid_EmpID"];
		$output["teacher_fname"] = $row["rid_FName"];
		$output["teacher_mname"] = $row["rid_MName"];
		$output["teacher_lname"] = $row["rid_LName"];
		$output["teacher_bday"] = $row["rid_Bday"];
		$output["teacher_suffix"] = $row["suffix_ID"];
		$output["teacher_sex"] = $row["sex_ID"];
		$output["teacher_marital"] = $row["marital_ID"];
		$output["teacher_email"] = $row["rid_Email"];
		$output["teacher_address"] = $row["rid_Address"];
		
	
	}
	
	echo json_encode($output);
	
}









 

?>