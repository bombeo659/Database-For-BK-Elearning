<?php
require_once('../class-function.php');

$room = new DTFunction();           // Create new connection by passing in your configuration array
session_start();
$query = '';
$output = array();

$query .= "SELECT rp.post_id, ua.user_id, rp.post_name, rp.post_description, rp.post_date, ua.lvl_id,
(case  
when (ua.lvl_id = 1) then (SELECT CONCAT(sd.sd_fname,' (',ul.lvl_Name,')') FROM student_details sd LEFT JOIN `students_has_account` `sha` ON `sha`.`sd_id` = `sd`.`sd_id` WHERE sha.user_id = ua.user_id)
when (ua.lvl_id = 2)  then (SELECT CONCAT(ind.ind_fname,' (',ul.lvl_Name,')') FROM instructor_details ind LEFT JOIN `instructors_has_account` `iha` ON `iha`.`ind_id` = `ind`.`ind_id` WHERE iha.user_id = ua.user_id)
when (ua.lvl_id = 3)  then (SELECT CONCAT(ad.ad_fname,' (',ul.lvl_Name,')') FROM admin_details ad LEFT JOIN `admins_has_account` `aha` ON `aha`.`ad_id` = `ad`.`ad_id` WHERE aha.user_id = ua.user_id)
end)  Posted_By ";

$query .= " FROM `post` rp
LEFT JOIN `user_account` `ua` ON `ua`.`user_id` = `rp`.`user_id`
LEFT JOIN `user_level` `ul` ON `ul`.`lvl_id` = `ua`.`lvl_id`";

if (isset($_REQUEST['room_ID'])) {
    $room_ID = $_REQUEST['room_ID'];
    $query .= ' WHERE rp.class_id = ' . $room_ID . ' AND';
} else {
    $query .= ' WHERE';
}
if (isset($_POST["search"]["value"])) {
    // $query .= ' (post_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' (post_name LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' OR post_date LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= 'ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= 'ORDER BY post_id ASC ';
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
    $sub_array = array();
    $sub_array[] = $i;
    $sub_array[] = $row["post_name"] . ' <span class="badge badge-info">' . $row["Posted_By"] . '<span>';
    $sub_array[] = $row["post_date"];

    if ($_SESSION['user_id'] === $row["user_id"] || $_SESSION["lvl_ID"] == 3) {
        $edit_by_user_who_posted = '
			<button type="button" class="btn btn-primary btn-sm mx-1 rounded edit"  user-id="' . $row["user_id"] . '" id="' . $row["post_id"] . '">Edit</button>

			';
        $delete_by_user_who_posted = '
			<button type="button" class="btn btn-danger btn-sm rounded delete"  id="' . $row["post_id"] . '">Delete</button>
			';
    } else {
        $edit_by_user_who_posted = '';
        $delete_by_user_who_posted = '';
    }
    $sub_array[] = '
		<div class="btn-group" role="group" aria-label="Basic example">
		  <button type="button" class="btn btn-secondary btn-sm rounded view"  id="' . $row["post_id"] . '">View</button>
		  ' . $edit_by_user_who_posted . '
		  ' . $delete_by_user_who_posted . '
		</div>';
    $i++;
    $data[] = $sub_array;
}

$q = "SELECT * FROM `post` `rp`";
if (isset($room_ID)) {
    $q .= '  WHERE `rp`.`class_id`= ' . $room_ID . ' ';
}
$filtered_rec = $room->get_total_all_records($q);
$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
