<?php
require_once('../class-function.php');
$room = new DTFunction();  		 // Create new connection by passing in your configuration array


$query = '';
$output = array();
$query .= "SELECT 
*
";
$query .= " FROM `room_module`";
if(isset($_POST["search"]["value"]))
{
 $query .= 'WHERE mod_ID LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR mod_Title LIKE "%'.$_POST["search"]["value"].'%" ';
}


if(isset($_POST["order"]))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY mod_ID DESC ';
}
if($_POST["length"] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$statement = $room->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
$i = 1;
foreach($result as $row)
{
	
		$sub_array = array();
	
		
		$sub_array[] = $i;
		$sub_array[] = $row["mod_Title"];
		
		$sub_array[] = '
		<div class="btn-group">
		  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		    Action
		  </button>
		  <div class="dropdown-menu">
		    <a class="dropdown-item view"  id="'.$row["mod_ID"].'">View</a>
		    <a class="dropdown-item edit"  id="'.$row["mod_ID"].'">Edit</a>
		     <div class="dropdown-divider"></div>
		    <a class="dropdown-item delete" id="'.$row["mod_ID"].'">Delete</a>
		  </div>
		</div>';
		 $i++;
	$data[] = $sub_array;
}

$q = "SELECT * FROM `room`";
$filtered_rec = $room->get_total_all_records($q);

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"		=> 	$filtered_rows,
	"recordsFiltered"	=>	$filtered_rec,
	"data"				=>	$data
);
echo json_encode($output);



?>



        
