<?php
require_once('../class-function.php');
$room = new DTFunction();           // Create new connection by passing in your configuration array

session_start();
$query = '';
$output = array();
$query .= "SELECT 
crc.*,
ua.user_ID,
ua.lvl_ID,
ua.user_Img,
(case  
when (ua.lvl_ID = 1) then (SELECT CONCAT(rsd.rsd_FName,' ',rsd.rsd_MName,'. ',rsd.rsd_LName,' (',ul.lvl_Name,')') FROM record_student_details rsd WHERE rsd.user_ID = ua.user_ID)
when (ua.lvl_ID = 2)  then (SELECT CONCAT(rid.rid_FName,' ',rid.rid_MName,'. ',rid.rid_LName,' (',ul.lvl_Name,')') FROM record_instructor_details rid WHERE rid.user_ID = ua.user_ID)
when (ua.lvl_ID = 3)  then (SELECT CONCAT(rad.rad_FName,' ',rad.rad_MName,'. ',rad.rad_LName,' (',ul.lvl_Name,')') FROM record_admin_details rad WHERE rad.user_ID = ua.user_ID)
end) Posted_By 
";

$query .= " FROM `room_comment` `crc`
LEFT JOIN `user_account` `ua` ON `ua`.`user_ID` = `crc`.`user_ID`
LEFT JOIN `user_level` `ul` ON `ul`.`lvl_ID` = `ua`.`lvl_ID`
";

if (isset($_REQUEST['post_ID'])) {
    $post_ID = $_REQUEST['post_ID'];
    $query .= '  WHERE crc.post_ID =  ' . $post_ID . ' AND';
} else {
    $query .= ' WHERE';
}

if (isset($_POST["search"]["value"])) {
    $query .= ' (comment_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR comment_content LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY comment_ID ASC ';
}

if ($_POST["length"] != -1) {
    $query .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $room->runQuery($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
$i = 1;
foreach ($result as $row) {
    if (!empty($row['user_Img'])) {
        $s_img = 'data:image/jpeg;base64,' . base64_encode($row['user_Img']);
    } else {
        $s_img = "../assets/img/users/default.jpg";
    }
    $sub_array = array();

    if ($_SESSION['user_ID'] === $row["user_ID"] || ($_SESSION["lvl_ID"] != 1 && $row["lvl_ID"] != 3)) {
        $delete_by_user_who_posted = '
        <button type="button" class="close delete_comment" id="' . $row['comment_ID'] . '" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>';
    } else {
        $delete_by_user_who_posted = '';
    }

    $sub_array[] = '
    <li class="media">
	    <img class="mr-3 rounded-circle" src="' . $s_img . '" alt="Generic placeholder image" style="max-width:48px; max-height:48px; border:1px solid;">
		<div class="media-body">
            ' . $delete_by_user_who_posted . '
	        <h5 class="mt-0 mb-1" style="font-size: 1.00rem;">' . $row["Posted_By"] . ' </h5>
            ' . $row["comment_content"] . '
        </div>
	</li>';
    $i++;
    $data[] = $sub_array;
}


$q = "SELECT * FROM `room_comment` `crc`";
if (isset($post_ID)) {
    $q .= ' WHERE `crc`.`post_ID` = ' . $post_ID . ' ';
}
$filtered_rec = $room->get_total_all_records($q);
$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
