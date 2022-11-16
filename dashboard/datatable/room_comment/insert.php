<?php
require_once('../class-function.php');
$room = new DTFunction();
session_start();
if (isset($_POST["operation"])) {
    if ($_POST["operation"] == "post_submit") {
        try {
            $class_ID = $_POST["class_ID"];
            $post_title = $_POST["post_title"];
            $post_content = $_POST["post_content"];

            $sql = "INSERT INTO `class_room_post` 
			(`post_ID`, `user_ID`, `class_ID`, `post_Name`, `post_Description`, `post_Date`) 
			VALUES (NULL, :user_ID, :class_ID, :post_title, :post_content, CURRENT_TIMESTAMP);";
            $statement = $room->runQuery($sql);

            $result = $statement->execute(
                array(
                    ':post_title'        =>    $post_title,
                    ':post_content'      =>    $post_content,
                    ':class_ID'       =>    $class_ID,
                    ':user_ID'        =>    $_SESSION["user_ID"],
                )
            );
            if (!empty($result)) {
                echo 'Successfully Added';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }
    if ($_POST["operation"] == "post_edit") {
        try {
            $post_title = $_POST["post_title"];
            $post_content = $_POST["post_content"];
            $class_ID = $_POST["class_ID"];
            $post_ID = $_POST["post_ID"];

            $sql = "UPDATE `class_room_post` 
			SET `post_Description` = :post_content,
			 `post_Name` = :post_title
			WHERE `post_ID` = :post_ID AND `class_ID` = :class_ID;";
            $statement = $room->runQuery($sql);

            $result = $statement->execute(
                array(
                    ':post_title'     =>    $post_title,
                    ':post_content'   =>    $post_content,
                    ':post_ID'        =>    $post_ID,
                    ':class_ID'       =>    $class_ID,
                )
            );
            if (!empty($result)) {
                echo 'Successfully Updated';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "post_delete") {
        try {
            $statement = $room->runQuery(
                "DELETE FROM `class_room_post` WHERE `post_ID` = :post_ID"
            );
            $result = $statement->execute(
                array(
                    ':post_ID'    =>    $_POST["post_ID"]
                )
            );
            if (!empty($result)) {
                echo 'Successfully Deleted';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }
}
