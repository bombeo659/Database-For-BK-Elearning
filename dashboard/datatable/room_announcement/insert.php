<?php
require_once('../class-function.php');
$room = new DTFunction();
session_start();

if (isset($_POST["operation"])) {
    if ($_POST["operation"] == "post_submit") {
        try {
            $room_ID = $_POST["room_ID"];
            $post_title = $_POST["post_title"];
            $post_content = $_POST["post_content"];

            $sql = "INSERT INTO `room_post` 
			(`post_ID`, `user_ID`, `room_ID`, `post_Name`, `post_Description`, `post_Date`) 
			VALUES (NULL, :user_ID, :room_ID, :post_title, :post_content, CURRENT_TIMESTAMP);";
            
            $statement = $room->runQuery($sql);
            $result = $statement->execute(
                array(
                    ':post_title'        =>    $post_title,
                    ':post_content'      =>    $post_content,
                    ':room_ID'        =>    $room_ID,
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
            $room_ID = $_POST["room_ID"];
            $post_ID = $_POST["post_ID"];

            $sql = "UPDATE `room_post` SET `post_Description` = :post_content, `post_Name` = :post_title
			WHERE `post_ID` = :post_ID AND `room_ID` = :room_ID;";
            
            $statement = $room->runQuery($sql);
            $result = $statement->execute(
                array(
                    ':post_title'        =>    $post_title,
                    ':post_content'      =>    $post_content,
                    ':post_ID'        =>    $post_ID,
                    ':room_ID'        =>    $room_ID,
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
            $statement = $room->runQuery("DELETE FROM `room_post` WHERE `post_ID` = :post_ID");
            $result = $statement->execute(array(':post_ID'  =>  $_POST["post_ID"]));
            if (!empty($result)) {
                echo 'Successfully Deleted';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "post_comment") {
        try {
            $user_ID = $_SESSION['user_ID'];
            $comment = $_POST['comment'];
            $post_ID = $_POST['post_ID'];
    
            $statement = $room->runQuery(
                "INSERT INTO `room_comment` (`comment_ID`, `user_ID`, `post_ID`, `comment_content`, `comment_Date`)
                 VALUES (NULL, :user_ID, :post_ID, :comment, CURRENT_TIMESTAMP);"
            );
            $result = $statement->execute(
                array(
                    ':post_ID'    =>    $post_ID,
                    ':comment'    =>    $comment,
                    ':user_ID'    =>    $user_ID
                )
            );
            if (!empty($result)) {
                echo 'Successfully Commented!';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "delete_comment") {
        try {
            $statement = $room->runQuery(
                "DELETE FROM `room_comment` WHERE `comment_ID` = :comment_ID"
            );
            $result = $statement->execute(
                array(
                    ':comment_ID'    =>    $_POST["comment_ID"]
                )
            );
            if (!empty($result)) {
                echo 'Successfully Deleted!';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }
}
