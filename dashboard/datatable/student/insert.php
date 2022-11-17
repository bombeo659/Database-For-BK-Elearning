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
        // $student_suffix = $_POST["student_suffix"];
        $student_sex = $_POST["student_sex"];
        // $student_marital = $_POST["student_marital"];
        $student_email = addslashes($_POST["student_email"]);
        $student_address = addslashes($_POST["student_address"]);

        if (isset($_FILES['student_img']['tmp_name'])) {
            $new_img = addslashes(file_get_contents($_FILES['student_img']['tmp_name']));
        } else {
            $new_img = '';
        }

        $stmt1 = $student->runQuery("
            SELECT sd_studnum FROM `student_details` WHERE sd_studnum = $student_lrn LIMIT 1");
        $stmt1->execute();
        $rs = $stmt1->fetchAll();
        if ($stmt1->rowCount() > 0) {
            echo "LRN Already Used";
        } else {
            try {
                $stmt = $student->runQuery("INSERT INTO `student_details` 
				(`sd_id`, `sd_img`, `sd_studnum`, `sd_fname`, `sd_mname`, `sd_lname`, `sd_gender`, `sd_email`, `sd_bday`, `sd_address`) 
				VALUES (NULL, '$new_img', '$student_lrn', '$student_fname', '$student_mname', '$student_lname', '$student_sex', '$student_email', '$student_bday', '$student_address');");

                $result = $stmt->execute();
                if (!empty($result)) {
                    echo  "Successfully Added!";
                }
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        }
    }

    if ($_POST["operation"] == "student_update") {
        $student_id = $_POST["student_id"];
        $student_lrn = $_POST["student_lrn"];
        $student_fname = $_POST["student_fname"];
        $student_mname = $_POST["student_mname"];
        $student_lname = $_POST["student_lname"];
        $student_bday = $_POST["student_bday"];
        // $student_suffix = $_POST["student_suffix"];
        $student_sex = $_POST["student_sex"];
        // $student_marital = $_POST["student_marital"];
        $student_email = addslashes($_POST["student_email"]);
        $student_address = addslashes($_POST["student_address"]);

        if (isset($_FILES['student_img']['tmp_name'])) {
            $new_img = addslashes(file_get_contents($_FILES['student_img']['tmp_name']));
            $set_img = " `sd_img` = '$new_img' , ";
        } else {
            $new_img = '';
            $set_img = '';
        }

        try {
            $stmt = $student->runQuery("UPDATE `student_details` 
				SET 
				" . $set_img . "
				`sd_studnum` = '$student_lrn' ,
				`sd_fname` = '$student_fname' ,
				`sd_mname` = '$student_mname' ,
				`sd_lname` = '$student_lname' ,
				`sd_gender` = '$student_sex' ,
				`sd_email` = '$student_email' ,
				`sd_bday` = '$student_bday' ,
				`sd_address` = '$student_address' 
				WHERE `student_details`.`sd_id` = $student_id;");
				// `marital_ID` = '$student_marital' ,
				// `suffix_ID` = '$student_suffix' ,

            $result1 = $stmt->execute();

            $query = "SELECT * FROM `students_has_account` WHERE `sd_id` = $student_id";
            $stmt = $student->runQuery($query);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt = $student->runQuery(
                "UPDATE `user_account` SET `user_img` = '$new_img' WHERE `user_id` = " . $userRow['user_id'] . ";");
                $result2 = $stmt->execute();
            }

            if (!empty($result1)) {
                echo  "Succesfully Updated!";
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "delete_student") {
        try {
            $student_id = $_POST["student_id"];
            $query = "SELECT * FROM `students_has_account` WHERE `sd_id` = $student_id";
            $stmt = $student->runQuery($query);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                $acc_id = $userRow['user_id'];
                $stmt = $student->runQuery("DELETE FROM `students_has_account` WHERE `sd_id` = '$student_id'");
                $result2 = $stmt->execute();
                $stmt = $student->runQuery("DELETE FROM `user_account` WHERE `user_id` = '$acc_id'");
                $result2 = $stmt->execute();
            }
            
            $statement = $student->runQuery("DELETE FROM `student_details` WHERE `sd_id` = :student_id");
            $result = $statement->execute(array(':student_id'    =>    $_POST["student_id"]));

            if (!empty($result)) {
                echo 'Successfully Deleted';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "gen_account") {
        $student_id = $_POST["student_id"];
        $student->generate_account($student_id, "student");
    }
}
