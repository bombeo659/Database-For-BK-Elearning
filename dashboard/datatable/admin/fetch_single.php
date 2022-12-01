<?php
require_once('../class-function.php');
$student = new DTFunction();

if (isset($_POST['action'])) {
    $output = array();
    $stmt = $student->runQuery("
    SELECT 
    `ad`.`ad_id`,
    `ad`.`ad_img`,
    `ad`.`ad_empid`,
    `ad`.`ad_fname`,
    `ad`.`ad_mname`,
    `ad`.`ad_lname`,
    `ad`.`ad_gender`,
    `ad`.`ad_email`,
    `ad`.`ad_bday`,
    `ad`.`ad_address`,
    `aha`.`user_id`
    FROM `admin_details` `ad`
    LEFT JOIN `admins_has_account` `aha` ON `aha`.`ad_id` = `ad`.`ad_id`
    WHERE `ad`.`ad_id` =  '" . $_POST["admin_ID"] . "' LIMIT 1");
    
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row) {

        if (!empty($row['ad_img'])) {
            $s_img = 'data:image/jpeg;base64,' . base64_encode($row['ad_img']);
        } else {
            $s_img = "../assets/img/users/default.jpg";
        }
        $output["rad_ID"] = $row["ad_id"];
        $output["admin_img"] = $s_img;
        $output["admin_EmpID"] = $row["ad_empid"];
        $output["admin_fname"] = $row["ad_fname"];
        $output["admin_mname"] = $row["ad_mname"];
        $output["admin_lname"] = $row["ad_lname"];
        $output["admin_bday"] = $row["ad_bday"];
        // $output["admin_suffix"] = $row["suffix_ID"];
        $output["admin_sex"] = $row["ad_gender"];
        // $output["admin_marital"] = $row["marital_ID"];
        $output["admin_email"] = $row["ad_email"];
        $output["admin_address"] = $row["ad_address"];
    }
    echo json_encode($output);
}
