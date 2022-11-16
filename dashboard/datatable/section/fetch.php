<?php
require_once('../class.function.php');
$section = new DTFunction();  		 // Create new connection by passing in your configuration array


$query = '';
$output = array();
$query .= "SELECT 
* ";
$query .= "FROM `ref_section` ";
if(isset($_POST["search"]["value"]))
{
 $query .= 'WHERE section_ID LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR section_Name LIKE "%'.$_POST["search"]["value"].'%" ';
}


if(isset($_POST["order"]))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY section_ID DESC ';
}
if($_POST["length"] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$statement = $section->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach($result as $row)
{
	

	$sub_array = array();
	
		
		$sub_array[] = $row["section_ID"];
		$sub_array[] =  $row["section_Name"];
		$sub_array[] = '

		<div class="btn-group" role="group" aria-label="Basic example">
		  <button type="button view" class="btn btn-primary btn-sm room" id="'.$row["section_ID"].'" data-toggle="modal" data-target="#room">Room</button>
		  
		  <button type="button edit" class="btn btn-info btn-sm edit" id="'.$row["section_ID"].'">Edit</button>
		</div>
		';
		// <button type="button view" class="btn btn-info btn-sm view" id="'.$row["section_ID"].'">View</button>
		// <div class="dropdown-divider"></div>
		// <a class="dropdown-item delete" id="'.$row["section_ID"].'">Delete</a>
	$data[] = $sub_array;
}

$q = "SELECT * FROM `ref_section`";
$filtered_rec = $section->get_total_all_records($q);

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"		=> 	$filtered_rows,
	"recordsFiltered"	=>	$filtered_rec,
	"data"				=>	$data
);
echo json_encode($output);



?>



        
