<?php
require_once('../class-function.php');
$admin = new DTFunction();

ini_set('display_errors', 1);
ini_set('error_reporting', E_ERROR);

if (isset($_POST["operation"])) {

    if ($_POST["operation"] == "submit_admin") {
        $admin_EmpID = $_POST["admin_EmpID"];
        $admin_fname = $_POST["admin_fname"];
        $admin_mname = $_POST["admin_mname"];
        $admin_lname = $_POST["admin_lname"];
        $admin_sex = $_POST["admin_sex"];
        // $admin_suffix = $_POST["admin_suffix"];
        $admin_bday = $_POST["admin_bday"];
        // $admin_marital = $_POST["admin_marital"];
        $admin_email = addslashes($_POST["admin_email"]);
        $admin_address = addslashes($_POST["admin_address"]);

        if (isset($_FILES['admin_img']['tmp_name'])) {
            $new_img = addslashes(file_get_contents($_FILES['admin_img']['tmp_name']));
        } else {
            $new_img = '';
        }

        $stmt1 = $admin->runQuery("SELECT ad_empid FROM `admin_details` WHERE ad_empid = $admin_EmpID LIMIT 1");
        $stmt1->execute();
        $rs = $stmt1->fetchAll();
        if ($stmt1->rowCount() > 0) {
            echo "Admin ID Already Used";
        } else {
            try {
                $stmt = $admin->runQuery("INSERT INTO `admin_details` 
				(`ad_id`, `ad_img`, `ad_empid`, `ad_fname`, `ad_mname`, `ad_lname`, `ad_gender`, `ad_email`, `ad_bday`, `ad_address`) VALUES (NULL, '$new_img', '$admin_EmpID', '$admin_fname', '$admin_mname', '$admin_lname', '$admin_sex', '$admin_email', '$admin_bday', '$admin_address');");

                $result = $stmt->execute();
                if (!empty($result)) {
                    echo  "Succesfully Added";
                }
            } catch (PDOException $e) {
                echo "There is some problem in connection: " . $e->getMessage();
            }
        }
    }

    if ($_POST["operation"] == "admin_update") {
        $admin_ID = $_POST["admin_ID"];
        $admin_EmpID = $_POST["admin_EmpID"];
        $admin_fname = $_POST["admin_fname"];
        $admin_mname = $_POST["admin_mname"];
        $admin_lname = $_POST["admin_lname"];
        $admin_sex = $_POST["admin_sex"];
        // $admin_suffix = $_POST["admin_suffix"];
        $admin_bday = $_POST["admin_bday"];
        // $admin_marital = $_POST["admin_marital"];
        $admin_email = addslashes($_POST["admin_email"]);
        $admin_address = addslashes($_POST["admin_address"]);

        if (isset($_FILES['admin_img']['tmp_name']) && $_FILES['admin_img']['tmp_name'] != "") {
            $new_img = addslashes(file_get_contents($_FILES['admin_img']['tmp_name']));
            $set_img = "`ad_img` = '$new_img' ,";
            $set_uimg = "`user_img` = '$new_img' ,";
        } else {
            $new_img = '';
            $set_img = '';
            $set_uimg = '';
        }

        try {
            $stmt = $admin->runQuery("UPDATE 
				`admin_details` 
				SET 
				" . $set_img . "
				`ad_empid` = '$admin_EmpID' ,
				`ad_fname` = '$admin_fname' ,
				`ad_mname` = '$admin_mname' ,
				`ad_lname` = '$admin_lname' ,
				`ad_gender` = '$admin_sex' ,
				`ad_email` = '$admin_email' ,
				`ad_bday` = '$admin_bday' ,
				`ad_address` = '$admin_address' 
				WHERE `ad_id` = $admin_ID;");
				// `suffix_ID` = '$admin_suffix' ,
				// `marital_ID` = '$admin_marital' ,

            $result1 = $stmt->execute();

            $query = "SELECT * FROM `admins_has_account` WHERE `ad_id` = $admin_ID";
            $stmt = $admin->runQuery($query);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt = $admin->runQuery(
                "UPDATE `user_account` SET " . $set_uimg. "  `user_name` = '$admin_EmpID' WHERE `user_id` = " . $userRow['user_id'] . ";");
                $result2 = $stmt->execute();
            }

            if (!empty($result1)) {
                echo  "Successfully Updated";
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "delete_admin") {
        try {
            $admin_id = $_POST["admin_ID"];
            $query = "SELECT * FROM `admins_has_account` WHERE `ad_id` = $admin_id";
            $stmt = $admin->runQuery($query);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
                $acc_id = $userRow['user_id'];
                $stmt = $admin->runQuery("DELETE FROM `admins_has_account` WHERE `ad_id` = '$admin_id'");
                $result2 = $stmt->execute();
                $stmt = $admin->runQuery("DELETE FROM `user_account` WHERE `user_id` = '$acc_id'");
                $result2 = $stmt->execute();
            }
            $statement = $admin->runQuery(
                "DELETE FROM `admin_details` WHERE `ad_id` = $admin_id;"
            );
            $result = $statement->execute();
    
            if (!empty($result)) {
                echo 'Successfully Deleted';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "gen_account") {
        $admin_ID = $_POST["admin_ID"];

        $admin->generate_account($admin_ID, "admin");
    }
}
