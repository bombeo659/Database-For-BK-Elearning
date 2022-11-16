<?php
require_once('../class-function.php');
$student = new DTFunction();

if (isset($_POST['action'])) {

    $output = array();
    $stmt = $student->runQuery("SELECT 
`rsd`.`rsd_ID`,
`rsd`.`rsd_Img`,
`rsd`.`rsd_StudNum`,
`rsd`.`rsd_FName`,
`rsd`.`rsd_MName`,
`rsd`.`rsd_LName`,
`rsd`.`rsd_Bday`,
`rs`.`sex_ID`,
`rm`.`marital_ID`,
`sf`.`suffix_ID`,
`rsd`.`rsd_Address`,
`rsd`.`rsd_Email`
FROM `record_student_details` `rsd`
LEFT JOIN `ref_marital` `rm` ON `rm`.`marital_ID` = `rsd`.`marital_ID`
LEFT JOIN `ref_sex` `rs` ON `rs`.`sex_ID` = `rsd`.`sex_ID`
LEFT JOIN `ref_suffixname` `sf` ON `sf`.`suffix_ID` = `rsd`.`suffix_ID`
WHERE rsd.rsd_ID =  '" . $_POST["student_ID"] . "' 
			LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row) {

        if (!empty($row['rsd_Img'])) {
            $s_img = 'data:image/jpeg;base64,' . base64_encode($row['rsd_Img']);
        } else {
            $s_img = "../assets/img/users/default.jpg";
        }

        $output["rsd_ID"] = $row["rsd_ID"];
        $output["student_img"] = $s_img;
        $output["student_lrn"] = $row["rsd_StudNum"];
        $output["student_fname"] = $row["rsd_FName"];
        $output["student_mname"] = $row["rsd_MName"];
        $output["student_lname"] = $row["rsd_LName"];
        $output["student_bday"] = $row["rsd_Bday"];
        $output["student_suffix"] = $row["suffix_ID"];
        $output["student_sex"] = $row["sex_ID"];
        $output["student_marital"] = $row["marital_ID"];
        $output["student_email"] = $row["rsd_Email"];
        $output["student_address"] = $row["rsd_Address"];
    }

    echo json_encode($output);
}
