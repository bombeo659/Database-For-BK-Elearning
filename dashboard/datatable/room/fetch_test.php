<?php
require_once('../class.function.php');
$account = new DTFunction();  		 // Create new connection by passing in your configuration array


$query = '';
$output = array();
$query .= "SELECT 
*
";
$query .= " FROM `room_test` `rt`
LEFT JOIN `ref_status` `sta` ON `sta`.`status_ID` = `rt`.`status_ID`
LEFT JOIN `ref_test_type` `rtt` ON `rtt`.`tstt_ID` = `rt`.`tstt_ID`";
if(isset($_POST["search"]["value"]))
{
 $query .= 'WHERE test_ID LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR test_Name LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR tstt_Name LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR status_Name LIKE "%'.$_POST["search"]["value"].'%" ';
}


if(isset($_POST["order"]))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY test_ID DESC ';
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
$i = 1;
foreach($result as $row)
{
	
		$sub_array = array();
	
		if ($row["status_Name"] === "Disable"){
			$zxc = '<div class="btn btn-sm btn-danger" style="min-width:65;">Disable</div>';
		}
		else{
			$zxc = '<div class="btn btn-sm btn-success" style="min-width:65;">Enable</div>';
		}
		$sub_array[] = $i;
		$sub_array[] = $row["test_Name"];
		$sub_array[] = $row["test_Added"];
		$sub_array[] = $row["test_Expired"];
		$sub_array[] = $row["test_Timer"];
		$sub_array[] = $row["tstt_Name"];

		$sub_array[] = $zxc;

		$sub_array[] = '
		<div class="btn-group">
		  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    Action
		  </button>
		  <div class="dropdown-menu">
		    <a class="dropdown-item view"  id="'.$row["test_ID"].'">View</a>
		    <a class="dropdown-item edit"  id="'.$row["test_ID"].'">Edit</a>
		    <a class="dropdown-item take"  id="'.$row["test_ID"].'">Take</a>
		     <div class="dropdown-divider"></div>
		    <a class="dropdown-item delete" id="'.$row["test_ID"].'">Delete</a>
		  </div>
		</div>';
		 $i++;
	$data[] = $sub_array;
}

$q = "SELECT * FROM `room`";
$filtered_rec = $account->get_total_all_records($q);

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"		=> 	$filtered_rows,
	"recordsFiltered"	=>	$filtered_rec,
	"data"				=>	$data
);
echo json_encode($output);



?>



        
