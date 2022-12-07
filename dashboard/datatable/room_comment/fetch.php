<?php
require_once('../class-function.php');
$room = new DTFunction();           // Create new connection by passing in your configuration array

session_start();
$query = '';
$output = array();
$query .= "SELECT 
crc.*,
ua.user_id,
ua.lvl_id,
ua.user_img,

(case  
when (ua.lvl_id = 1) then (SELECT CONCAT(sd.sd_fname,' ',sd.sd_lname,' ',sd.sd_mname,' (',ul.lvl_Name,')') FROM student_details sd LEFT JOIN `students_has_account` `sha` ON `sha`.`sd_id` = `sd`.`sd_id` WHERE sha.user_id = ua.user_id)
when (ua.lvl_id = 2)  then (SELECT CONCAT(ind.ind_fname,' ',ind.ind_lname,' ',ind.ind_mname,'  (',ul.lvl_Name,')') FROM instructor_details ind LEFT JOIN `instructors_has_account` `iha` ON `iha`.`ind_id` = `ind`.`ind_id` WHERE iha.user_id = ua.user_id)
when (ua.lvl_id = 3)  then (SELECT CONCAT(ad.ad_fname,' ',ad.ad_lname,' ',ad.ad_mname,' (',ul.lvl_Name,')') FROM admin_details ad LEFT JOIN `admins_has_account` `aha` ON `aha`.`ad_id` = `ad`.`ad_id` WHERE aha.user_id = ua.user_id)
end)  Posted_By ";



$query .= " FROM `post_comment` `crc`
LEFT JOIN `user_account` `ua` ON `ua`.`user_ID` = `crc`.`user_ID`
LEFT JOIN `user_level` `ul` ON `ul`.`lvl_ID` = `ua`.`lvl_ID`
";

if (isset($_REQUEST['post_ID'])) {
    $post_ID = $_REQUEST['post_ID'];
    $query .= '  WHERE crc.post_id =  ' . $post_ID . ' AND';
} else {
    $query .= ' WHERE';
}

if (isset($_POST["search"]["value"])) {
    // $query .= ' (comment_ID LIKE "%' . $_POST["search"]["value"] . '%" ';
    $query .= ' ( comment_content LIKE "%' . $_POST["search"]["value"] . '%" )';
}

if (isset($_POST["order"])) {
    $query .= ' ORDER BY ' . $_POST['order']['0']['column'] . ' ' . $_POST['order']['0']['dir'] . ' ';
} else {
    $query .= ' ORDER BY comment_id ASC ';
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
    if (!empty($row['user_img'])) {
        $s_img = 'data:image/jpeg;base64,' . base64_encode($row['user_img']);
    } else {
        $s_img = "../assets/img/users/default.jpg";
    }
    $sub_array = array();

    if ($_SESSION['user_id'] === $row["user_id"] || ($_SESSION["lvl_id"] != 1 && $row["lvl_id"] != 3)) {
        $delete_by_user_who_posted = '
        <button type="button" class="close delete_comment" id="' . $row['comment_id'] . '" aria-label="Close">
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


$q = "SELECT * FROM `post_comment` `crc`";
if (isset($post_ID)) {
    $q .= ' WHERE `crc`.`post_id` = ' . $post_ID . ' ';
}
$filtered_rec = $room->get_total_all_records($q);
$output = array(
    "draw"                =>    intval($_POST["draw"]),
    "recordsTotal"        =>    $filtered_rows,
    "recordsFiltered"     =>    $filtered_rec,
    "data"                =>    $data
);
echo json_encode($output);
