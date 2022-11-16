<?php
require_once('../class-function.php');
$room = new DTFunction();  		 // Create new connection by passing in your configuration array

session_start();
$query = '';
$output = array();
$query .= "SELECT 
rp.post_ID,
ua.user_ID,
rp.post_Name,
rp.post_Description,
rp.post_Date,
(case  
 when (ua.lvl_ID = 1) then (SELECT CONCAT(rsd.rsd_FName,' (',ul.lvl_Name,')') FROM record_student_details rsd WHERE rsd.user_ID = ua.user_ID)
when (ua.lvl_ID = 2)  then (SELECT CONCAT(rid.rid_FName,' (',ul.lvl_Name,')') FROM record_instructor_details rid WHERE rid.user_ID = ua.user_ID)
when (ua.lvl_ID = 3)  then (SELECT CONCAT(rad.rad_FName,' (',ul.lvl_Name,')') FROM record_admin_details rad WHERE rad.user_ID = ua.user_ID)
end)  Posted_By 

";
$query .= " FROM `room_post` rp
LEFT JOIN `user_account` `ua` ON `ua`.`user_ID` = `rp`.`user_ID`
LEFT JOIN `user_level` `ul` ON `ul`.`lvl_ID` = `ua`.`lvl_ID`";
if(isset($_POST["search"]["value"]))
{
 $query .= 'WHERE post_ID LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR post_Name LIKE "%'.$_POST["search"]["value"].'%" ';
    $query .= 'OR post_Date LIKE "%'.$_POST["search"]["value"].'%" ';
}


if(isset($_POST["order"]))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY post_ID DESC ';
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
		$sub_array[] = $row["post_Name"].' <span class="badge badge-info">'.$row["Posted_By"].'<span>';
		$sub_array[] = $row["post_Date"];
		
		if($_SESSION['user_ID'] === $row["user_ID"]){
			// <a class="dropdown-item edit" user-id="'.$row["user_ID"].'" id="'.$row["post_ID"].'">Edit</a>
			$edit_by_user_who_posted = '
			<button type="button" class="btn btn-secondary edit" user-id="'.$row["user_ID"].'" id="'.$row["post_ID"].'">Edit</button>
			';
		}
		else{
			$edit_by_user_who_posted='';
		}
		$sub_array[] = '
		<div class="btn-group" role="group" aria-label="Basic example">
		  <button type="button" class="btn btn-secondary view"  id="'.$row["post_ID"].'">View</button>
		  '.$edit_by_user_who_posted.'
		  <button type="button" class="btn btn-secondary delete" id="'.$row["post_ID"].'">Delete</button>
		</div>';
		// <div class="btn-group">
		//   <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		//     Action
		//   </button>
		//   <div class="dropdown-menu">
		//     <a class="dropdown-item view"  id="'.$row["post_ID"].'">View</a>
		// 	'.$edit_by_user_who_posted.'
		//     <div class="dropdown-divider"></div>
		//     <a class="dropdown-item delete" id="'.$row["post_ID"].'">Delete</a>
		//   </div>
		// </div>
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



        
