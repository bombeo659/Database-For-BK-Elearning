<?php
require_once('../class.function.php');
$section = new DTFunction(); 
if(isset($_POST["operation"]))
{

	if($_POST["operation"] == "submit_section")
	{	
		try
		{
			$section_title = $_POST["section_title"];

			$sql = "INSERT INTO `ref_section` 
			(`section_ID`, `section_Name`) 
			VALUES 
			(NULL, :section_title);";
				$statement = $section->runQuery($sql);
					
				$result = $statement->execute(
				array(

						':section_title'		=>	$section_title ,
					)
				);
				if(!empty($result))
				{
					echo 'Successfully Added';
				}

		}
		catch (PDOException $e)
		{
		    echo "There is some problem in connection: " . $e->getMessage();
		}
		
	}

	if($_POST["operation"] == "section_edit")
	{
		
		

		$section_title = $_POST["section_title"];

		$sql = "UPDATE `ref_section` SET `section_Name` = :section_title WHERE `ref_section`.`section_ID` =  :section_ID;";
		$statement = $section->runQuery($sql);
			
		$result = $statement->execute(
		array(
				':section_ID'	=>	$_POST["section_ID"],
				':section_title'		=>	$section_title ,
			)
		);
		if(!empty($result))
		{
			echo 'Successfully Updated';
		}
	
	}

	if($_POST["operation"] == "delete_section")
	{
		$statement = $section->runQuery(
			"DELETE FROM `ref_section` WHERE `section_ID` = :section_ID"
		);
		$result = $statement->execute(
			array(
				':section_ID'	=>	$_POST["section_ID"]
			)
		);
		
		if(!empty($result))
		{
			echo 'Successfully Deleted';
		}
		
	
	}
}
?>

