<?php
require_once('../class-function.php');
$student = new DTFunction();

if (isset($_POST['action'])) {
    $output = array();
    $stmt = $student->runQuery("SELECT 
    `sd`.`sd_id`,
    `sd`.`sd_img`,
    `sd`.`sd_studnum`,
    `sd`.`sd_fname`,
    `sd`.`sd_mname`,
    `sd`.`sd_lname`,
    `sd`.`sd_bday`,
    `sd`.`sd_gender`,
    `sd`.`sd_address`,
    `sd`.`sd_email`,
    `sha`.`user_id`
    FROM `student_details` `sd`
    LEFT JOIN `students_has_account` `sha` ON `sha`.`sd_id` = `sd`.`sd_id`
    WHERE sd.sd_id =  '" . $_POST["student_id"] . "' LIMIT 1");
    
    $stmt->execute();
    $result = $stmt->fetchAll();

    foreach ($result as $row) {
        if (!empty($row['sd_img'])) {
            $s_img = 'data:image/jpeg;base64,' . base64_encode($row['sd_img']);
        } else {
            $s_img = "../assets/img/users/default.jpg";
        }

        $output["sd_id"] = $row["sd_id"];
        $output["student_img"] = $s_img;
        $output["student_lrn"] = $row["sd_studnum"];
        $output["student_fname"] = $row["sd_fname"];
        $output["student_mname"] = $row["sd_mname"];
        $output["student_lname"] = $row["sd_lname"];
        $output["student_bday"] = $row["sd_bday"];
        $output["student_sex"] = $row["sd_gender"];
        $output["student_email"] = $row["sd_email"];
        $output["student_address"] = $row["sd_address"];
    }

    echo json_encode($output);
}
