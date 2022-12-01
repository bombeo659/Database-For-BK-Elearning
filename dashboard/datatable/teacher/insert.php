<?php
require_once('../class-function.php');
$teacher = new DTFunction();

ini_set('display_errors', 1);
ini_set('error_reporting', E_ERROR);

if (isset($_POST["operation"])) {

    if ($_POST["operation"] == "submit_teacher") {
        $teacher_EmpID = $_POST["teacher_EmpID"];
        $teacher_fname = ucfirst($_POST["teacher_fname"]);
        $teacher_mname = ucfirst($_POST["teacher_mname"]);
        $teacher_lname = ucfirst($_POST["teacher_lname"]);
        $teacher_bday = $_POST["teacher_bday"];
        // $teacher_suffix = $_POST["teacher_suffix"];
        $teacher_sex = $_POST["teacher_sex"];
        // $teacher_marital = $_POST["teacher_marital"];
        $teacher_email = addslashes($_POST["teacher_email"]);
        $teacher_address = addslashes($_POST["teacher_address"]);

        if (isset($_FILES['teacher_img']['tmp_name'])) {
            $new_img = addslashes(file_get_contents($_FILES['teacher_img']['tmp_name']));
        } else {
            $new_img = '';
        }

        $stmt1 = $teacher->runQuery("SELECT ind_empid FROM `instructor_details` WHERE ind_empid = $teacher_EmpID LIMIT 1");
        $stmt1->execute();
        $rs = $stmt1->fetchAll();
        if ($stmt1->rowCount() > 0) {
            echo "Teacher ID Already Used!";
        } else {
            try {
                $stmt = $teacher->runQuery("INSERT INTO `instructor_details` 
					(`ind_id`, `ind_img`, `ind_empid`, `ind_fname`, `ind_mname`, `ind_lname`, `ind_gender`, `ind_email`, `ind_bday`, `ind_address`) VALUES (NULL, '$new_img', '$teacher_EmpID', '$teacher_fname', '$teacher_mname', '$teacher_lname', '$teacher_sex', '$teacher_email', '$teacher_bday', '$teacher_address');");

                $result = $stmt->execute();
                if (!empty($result)) {
                    echo  "Succesfully Added!";
                }
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        }
    }

    if ($_POST["operation"] == "teacher_update") {
        $teacher_ID = $_POST["teacher_ID"];
        $teacher_EmpID = $_POST["teacher_EmpID"];
        $teacher_fname = $_POST["teacher_fname"];
        $teacher_mname = $_POST["teacher_mname"];
        $teacher_lname = $_POST["teacher_lname"];
        $teacher_bday = $_POST["teacher_bday"];
        // $teacher_suffix = $_POST["teacher_suffix"];
        $teacher_sex = $_POST["teacher_sex"];
        // $teacher_marital = $_POST["teacher_marital"];
        $teacher_email = addslashes($_POST["teacher_email"]);
        $teacher_address = addslashes($_POST["teacher_address"]);

        if (isset($_FILES['teacher_img']['tmp_name']) && $_FILES['teacher_img']['tmp_name'] != "") {
            $new_img = addslashes(file_get_contents($_FILES['teacher_img']['tmp_name']));
            $set_img = "`ind_img` = '$new_img' ,";
            $set_uimg = "`user_img` = '$new_img' ,";
        } else {
            $new_img = '';
            $set_img = '';
            $set_uimg = '';
        }

        try {
            $stmt = $teacher->runQuery("UPDATE `instructor_details` SET 
                " . $set_img . "
                `ind_empid` = '$teacher_EmpID' ,
                `ind_fname` = '$teacher_fname' ,
                `ind_mname` = '$teacher_mname' ,
                `ind_lname` = '$teacher_lname' ,
                `ind_gender` = '$teacher_sex' ,
                `ind_email` = '$teacher_email' ,
                `ind_bday` = '$teacher_bday' ,
                `ind_address` = '$teacher_address' 
				WHERE `instructor_details`.`ind_id` = '$teacher_ID';");
                // `suffix_ID` = '$teacher_suffix' ,
                // `marital_ID` = '$teacher_marital' ,

            $result = $stmt->execute();

            $query = "SELECT * FROM `instructors_has_account` WHERE `ind_id` = '$teacher_ID'";
            $stmt = $teacher->runQuery($query);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt = $teacher->runQuery(
                "UPDATE `user_account` SET " . $set_uimg. " `user_name` = '$teacher_EmpID' WHERE `user_id` = " . $userRow['user_id'] . ";");
                $result2 = $stmt->execute();
            }

            if (!empty($result)) {
                echo  "Succesfully Updated";
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "delete_teacher") {
        try {
            $teacher_id = $_POST["teacher_ID"];
            $query = "SELECT * FROM `instructors_has_account` WHERE `ind_id` = $teacher_id";
            $stmt = $teacher->runQuery($query);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                $acc_id = $userRow['user_id'];
                $stmt = $teacher->runQuery("DELETE FROM `instructors_has_account` WHERE `ind_id` = '$teacher_id'");
                $result2 = $stmt->execute();
                $stmt = $teacher->runQuery("DELETE FROM `user_account` WHERE `user_id` = '$acc_id'");
                $result2 = $stmt->execute();
            }

            $statement = $teacher->runQuery("DELETE FROM `instructor_details` WHERE `ind_ID` = '$teacher_id'");
            $result = $statement->execute();

            if (!empty($result)) {
                echo 'Successfully Deleted';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "gen_account") {
        $teacher_ID = $_POST["teacher_ID"];
        $teacher->generate_account($teacher_ID, "instructor");
    }
}
