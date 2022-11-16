<?php
require_once('../class-function.php');
$student = new DTFunction();

ini_set('display_errors', 1);
ini_set('error_reporting', E_ERROR);

if (isset($_POST["operation"])) {

    if ($_POST["operation"] == "submit_student") {
        $student_lrn = $_POST["student_lrn"];
        $student_fname = $_POST["student_fname"];
        $student_mname = $_POST["student_mname"];
        $student_lname = $_POST["student_lname"];
        $student_bday = $_POST["student_bday"];
        $student_suffix = $_POST["student_suffix"];
        $student_sex = $_POST["student_sex"];
        $student_marital = $_POST["student_marital"];
        $student_email = addslashes($_POST["student_email"]);
        $student_address = addslashes($_POST["student_address"]);

        if (isset($_FILES['student_img']['tmp_name'])) {
            $new_img = addslashes(file_get_contents($_FILES['student_img']['tmp_name']));
        } else {
            $new_img = '';
        }

        $stmt1 = $student->runQuery("SELECT rsd_StudNum FROM `record_student_details` WHERE rsd_StudNum = $student_lrn LIMIT 1");
        $stmt1->execute();
        $rs = $stmt1->fetchAll();
        if ($stmt1->rowCount() > 0) {
            echo "LRN Already Used";
        } else {
            try {
                $stmt = $student->runQuery("INSERT INTO `record_student_details` 
				(`rsd_ID`, `rsd_Img`, `user_ID`, `rsd_StudNum`, `rsd_FName`, `rsd_MName`, `rsd_LName`, `suffix_ID`, `sex_ID`, `marital_ID`, `rsd_Email`, `rsd_Bday`, `rsd_Address`) 
				VALUES (NULL, '$new_img', NULL, '$student_lrn', '$student_fname', '$student_mname', '$student_lname', $student_suffix',  '$student_sex', '$student_marital', '$student_email', '$student_bday', '$student_address');");

                $result = $stmt->execute();
                if (!empty($result)) {
                    echo  "Student Record Succesfully Updated";
                }
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        }
    }

    if ($_POST["operation"] == "student_update") {
        $student_ID = $_POST["student_ID"];
        $student_lrn = $_POST["student_lrn"];
        $student_fname = $_POST["student_fname"];
        $student_mname = $_POST["student_mname"];
        $student_lname = $_POST["student_lname"];
        $student_bday = $_POST["student_bday"];
        $student_suffix = $_POST["student_suffix"];
        $student_sex = $_POST["student_sex"];
        $student_marital = $_POST["student_marital"];
        $student_email = addslashes($_POST["student_email"]);
        $student_address = addslashes($_POST["student_address"]);

        if (isset($_FILES['student_img']['tmp_name'])) {
            $new_img = addslashes(file_get_contents($_FILES['student_img']['tmp_name']));
            $set_img = "`rsd_Img` = '$new_img' ,";
        } else {
            $new_img = '';
            $set_img = '';
        }

        try {
            $stmt = $student->runQuery("UPDATE 
				`record_student_details` 
				SET 
				" . $set_img . "
				`rsd_StudNum` = '$student_lrn' ,
				`rsd_FName` = '$student_fname' ,
				`rsd_MName` = '$student_mname' ,
				`rsd_LName` = '$student_lname' ,
				`suffix_ID` = '$student_suffix' ,
				`sex_ID` = '$student_sex' ,
				`marital_ID` = '$student_marital' ,
				`rsd_Email` = '$student_email' ,
				`rsd_Bday` = '$student_bday' ,
				`rsd_Address` = '$student_address' 
				WHERE `record_student_details`.`rsd_ID` = $student_ID;");

            $result = $stmt->execute();
            if (!empty($result)) {
                echo  "Student Record Succesfully Updated";
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "delete_student") {
        try {
            $statement = $student->runQuery("DELETE FROM `record_student_details` WHERE `rsd_ID` = :student_ID");
            $result = $statement->execute(
                array(
                    ':student_ID'    =>    $_POST["student_ID"]
                )
            );

            if (!empty($result)) {
                echo 'Successfully Deleted';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "gen_account") {
        $student_ID = $_POST["student_ID"];
        $student->generate_account($student_ID, "student");
    }
}
