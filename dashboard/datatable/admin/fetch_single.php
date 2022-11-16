<?php
require_once('../class-function.php');
$student = new DTFunction();

if (isset($_POST['action'])) {
    $output = array();
    $stmt = $student->runQuery("
        SELECT 
        `rad`.`rad_ID`,
        `rad`.`rad_Img`,
        `rad`.`rad_EmpID`,
        `rad`.`rad_FName`,
        `rad`.`rad_MName`,
        `rad`.`rad_LName`,
        `rad`.`rad_Bday`,
        `rs`.`sex_ID`,
        `rm`.`marital_ID`,
        `sf`.`suffix_ID`,
        `rad`.`rad_Address`,
        `rad`.`rad_Email`
        FROM `record_admin_details` `rad`
        LEFT JOIN `ref_marital` `rm` ON `rm`.`marital_ID` = `rad`.`marital_ID`
        LEFT JOIN `ref_sex` `rs` ON `rs`.`sex_ID` = `rad`.`sex_ID`
        LEFT JOIN `ref_suffixname` `sf` ON `sf`.`suffix_ID` = `rad`.`suffix_ID`
        WHERE `rad`.`rad_ID` =  '" . $_POST["admin_ID"] . "' LIMIT 1");
    
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row) {

        if (!empty($row['rad_Img'])) {
            $s_img = 'data:image/jpeg;base64,' . base64_encode($row['rad_Img']);
        } else {
            $s_img = "../assets/img/users/default.jpg";
        }
        $output["rad_ID"] = $row["rad_ID"];
        $output["admin_img"] = $s_img;
        $output["admin_EmpID"] = $row["rad_EmpID"];
        $output["admin_fname"] = $row["rad_FName"];
        $output["admin_mname"] = $row["rad_MName"];
        $output["admin_lname"] = $row["rad_LName"];
        $output["admin_bday"] = $row["rad_Bday"];
        $output["admin_suffix"] = $row["suffix_ID"];
        $output["admin_sex"] = $row["sex_ID"];
        $output["admin_marital"] = $row["marital_ID"];
        $output["admin_email"] = $row["rad_Email"];
        $output["admin_address"] = $row["rad_Address"];
    }
    echo json_encode($output);
}
