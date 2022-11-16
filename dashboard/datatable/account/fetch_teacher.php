<?php
require_once('../class.function.php');
$account = new DTFunction();  		 // Create new connection by passing in your configuration array


$query = '';
$output = array();
$query .= " 
SELECT 
`rid`.`rid_ID`,
`rid`.`rid_Img`,
`rid`.`rid_EmpID`,
`rid`.`rid_FName`,
`rid`.`rid_MName`,
`rid`.`rid_LName`,
`rid`.`user_ID`,
`rs`.`sex_Name`,
`rm`.`marital_Name`,
`sf`.`suffix`
";
$query .= "FROM `record_instructor_details` `rid`
LEFT JOIN `ref_marital` `rm` ON `rm`.`marital_ID` = `rid`.`marital_ID`
LEFT JOIN `ref_sex` `rs` ON `rs`.`sex_ID` = `rid`.`sex_ID`
LEFT JOIN `ref_suffixname` `sf` ON `sf`.`suffix_ID` = `rid`.`suffix_ID`";

$query .= '  WHERE user_ID IS NULL AND';

if(isset($_POST["search"]["value"]))
{
 $query .= '(rid_ID LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR rid_EmpID LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR rid_FName LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR rid_MName LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR rid_LName LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR suffix LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR sex_Name LIKE "%'.$_POST["search"]["value"].'%" )';
}



if(isset($_POST["order"]))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY rid_ID DESC ';
}
if($_POST["length"] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$statement = $account->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach($result as $row)
{
	

	if($row["suffix"] =="N/A")
	{
		$suffix = "";
	}
	else
	{
		$suffix = $row["suffix"];
	}

	if($row["rid_MName"] ==" " || $row["rid_MName"] == NULL || empty($row["rid_MName"]) )
	{
		$mname = " ";
	}
	else
	{
		$mname = $row["rid_MName"].'. ';
	}

	if(empty($row["user_ID"]))
	{
		$reg = "<span class='badge badge-danger'>Unregistered</span>";
		$acreg = "UN";
		$btnrg = '<a class="dropdown-item gen_account"  id="'.$row["rid_ID"].'">Generate Account</a>';
	}
	else
	{
		$reg = "<span class='badge badge-success'>Registered</span>";
		$acreg = "RG";
		$btnrg = '';
	}
	$sub_array = array();
	
		
		$sub_array[] = $row["rid_ID"];
		$sub_array[] =  $row["rid_EmpID"];
		$sub_array[] =  ucwords(strtolower($row["rid_FName"].' '.$mname.$row["rid_LName"].' '.$suffix));
		$sub_array[] =  ucwords(strtolower($row["sex_Name"]));
		$sub_array[] =  ucwords(strtolower($row["marital_Name"]));
		$sub_array[] =  $reg;
		$sub_array[] = '
		<div class="btn-group">
		  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    Action
		  </button>
		  <div class="dropdown-menu">
		    <a class="dropdown-item view"  id="'.$row["rid_ID"].'">View</a>
		    <a class="dropdown-item edit"  acreg="'.$acreg.'"  id="'.$row["rid_ID"].'">Edit</a>
		    '.$btnrg.'
		  </div>
		</div>';
		// <div class="dropdown-divider"></div>
		// <a class="dropdown-item delete" id="'.$row["rid_ID"].'">Delete</a>
	$data[] = $sub_array;
}

$q = "SELECT * FROM `record_instructor_details`";
$filtered_rec = $account->get_total_all_records($q);

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"		=> 	$filtered_rows,
	"recordsFiltered"	=>	$filtered_rec,
	"data"				=>	$data
);
echo json_encode($output);



?>



        
