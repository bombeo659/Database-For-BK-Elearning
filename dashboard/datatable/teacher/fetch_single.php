<?php
require_once('../class-function.php');
$teacher = new DTFunction();

if (isset($_POST['action'])) {
    $output = array();
    $stmt = $teacher->runQuery("SELECT 
    `ind`.`ind_id`,
    `ind`.`ind_img`,
    `ind`.`ind_empid`,
    `ind`.`ind_fname`,
    `ind`.`ind_mname`,
    `ind`.`ind_lname`,
    `ind`.`ind_gender`,
    `ind`.`ind_bday`,
    `ind`.`ind_address`,
    `ind`.`ind_email`,
    `iha`.`user_id`
    FROM `instructor_details` `ind`
    LEFT JOIN `instructors_has_account` `iha` ON `iha`.`ind_id` = `ind`.`ind_id`
    WHERE ind.ind_id =  '" . $_POST["teacher_ID"] . "' LIMIT 1");

    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row) {

        if (!empty($row['ind_img'])) {
            $s_img = 'data:image/jpeg;base64,' . base64_encode($row['ind_img']);
        } else {
            $s_img = "../assets/img/users/default.jpg";
        }

        $output["teacher_ID"] = $row["ind_id"];
        $output["teacher_img"] = $s_img;
        $output["teacher_EmpID"] = $row["ind_empid"];
        $output["teacher_fname"] = $row["ind_fname"];
        $output["teacher_mname"] = $row["ind_mname"];
        $output["teacher_lname"] = $row["ind_lname"];
        $output["teacher_bday"] = $row["ind_bday"];
        // $output["teacher_suffix"] = $row["suffix_ID"];
        $output["teacher_sex"] = $row["ind_gender"];
        // $output["teacher_marital"] = $row["marital_ID"];
        $output["teacher_email"] = $row["ind_email"];
        $output["teacher_address"] = $row["ind_address"];
    }
    echo json_encode($output);
}
