<?php
require_once('class-function.php');
$profile = new ACFunction();
session_start();

if (isset($_POST['action'])) {
    $output = array();
    // change password
    if ($_POST['action'] == "change_password") {
        $user_id = $_SESSION['user_id'];
        $update_password_old = $_REQUEST["update_password_old"];
        $update_password_new = $_REQUEST["update_password_new"];
        $update_password_newconfirm = $_REQUEST["update_password_newconfirm"];

        if ($update_password_new === $update_password_newconfirm) {
            $stmt = $profile->runQuery("SELECT * FROM `user_account` WHERE user_id = :user_id LIMIT 1");
            $stmt->execute(array(':user_id' => $user_id));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() == 1) {
                if (password_verify($update_password_old, $userRow['user_pass'])) {
                    $new_password = password_hash($update_password_newconfirm, PASSWORD_DEFAULT);
                    $stmt = $profile->runQuery("UPDATE `user_account` SET `user_pass` = :user_pass WHERE `user_account`.`user_id` = :user_id");
                    $stmt->bindparam(":user_id", $user_id);
                    $stmt->bindparam(":user_pass", $new_password);
                    $stmt->execute();
                    $output['success'] = "Password changed successfully!";
                } else {
                    $output['error'] = "Old Password not match!";
                }
            }
        } else {
            $output['error'] = "Password not match!";
        }
    }

    // change profile picture
    if ($_POST['action'] == "change_picture") {
        $user_id = $_SESSION['user_id'];

        $user_type = "";
        $user_type_acro = "";
        if ($_SESSION['lvl_ID'] == "1") {
            $user_type = "student";
            $id_type = "sd";
        }
        if ($_SESSION['lvl_ID'] == "2") {
            $user_type = "instructor";
            $id_type = "ind";
        }
        if ($_SESSION['lvl_ID'] == "3") {
            $user_type = "admin";
            $id_type = "ad";
        }

        $query = "SELECT * FROM `" . $user_type . "s_has_account` WHERE `user_id` = " . $user_id;
        $stmt = $profile->runQuery($query);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($_FILES['change_profile']['tmp_name'])) {
            $new_img = addslashes(file_get_contents($_FILES['change_profile']['tmp_name']));
            $stmt = $profile->runQuery("UPDATE `user_account` SET `user_img` = '$new_img' WHERE `user_id` = $user_id;");
            $result = $stmt->execute();

            $stmt = $profile->runQuery("UPDATE `" . $user_type . "_details` SET `" . $id_type . "_img` = '.$new_img' WHERE `" . $id_type . "_id` = " . $userRow[$id_type .'_id'] .";");
            $result = $stmt->execute();

            if (!empty($result)) {
                $output['success'] = "Change profile image succesfully!";
                $profile->getUserPic($user_id);
            } else {
                $output['error'] =  "Error updating record: " . mysqli_error($conn);
            }
        } else {
            $new_img = '';
        }
    } 

    // change email
    if ($_POST['action'] == "change_email") {
        $user_type = "";
        $user_type_acro = "";
        if ($_SESSION['lvl_ID'] == "1") {
            $user_type = "student";
            $id_type = "sd";
        }
        if ($_SESSION['lvl_ID'] == "2") {
            $user_type = "instructor";
            $id_type = "ind";
        }
        if ($_SESSION['lvl_ID'] == "3") {
            $user_type = "admin";
            $id_type = "ad";
        }
        $update_email =  $_REQUEST["update_email"];

        $query = "SELECT * FROM `" . $user_type . "s_has_account` WHERE `user_id` = " . $_SESSION['user_id'];
        $stmt = $profile->runQuery($query);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "UPDATE `" . $user_type . "_details` SET `" . $id_type . "_email` = '" . $update_email . "' WHERE `" . $id_type . "_id` = " . $userRow[$id_type .'_id'] ;
        $stmt = $profile->runQuery($query);
        $result = $stmt->execute();

        if (!empty($result)) {
            $output['success'] = "Email has been updated successfully!";
        } else {
            $output['error'] = "Email update failed!";
        }
    } 
    
    // change address
    if ($_POST['action'] == "change_address") {
        $user_type = "";
        $user_type_acro = "";
        if ($_SESSION['lvl_ID'] == "1") {
            $user_type = "student";
            $id_type = "sd";
        }
        if ($_SESSION['lvl_ID'] == "2") {
            $user_type = "instructor";
            $id_type = "ind";
        }
        if ($_SESSION['lvl_ID'] == "3") {
            $user_type = "admin";
            $id_type = "ad";
        }
        $update_address =  $_REQUEST["update_address"];

        $query = "SELECT * FROM `" . $user_type . "s_has_account` WHERE `user_id` = " . $_SESSION['user_id'];
        $stmt = $profile->runQuery($query);
        $stmt->execute();
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $query = "UPDATE `" . $user_type . "_details` SET `" . $id_type . "_address` = '" . $update_address . "' WHERE `" . $id_type . "_id` = " . $userRow[$id_type .'_id'];
        $stmt = $profile->runQuery($query);
        $result = $stmt->execute();

        if (!empty($result)) {
            $output['success'] = "Address has been updated successfully!";
        } else {
            $output['error'] = "Address update failed!";
        }
    } else {
        $output['error'] = "Unexpected Error";
    }
    
    echo json_encode($output);
}
