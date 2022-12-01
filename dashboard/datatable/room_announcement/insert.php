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

            $sql = "INSERT INTO `post` 
			(`post_id`, `user_id`, `class_id`, `post_name`, `post_description`, `post_date`) 
			VALUES (NULL, :user_ID, :room_ID, :post_title, :post_content, CURRENT_TIMESTAMP);";
            
            $statement = $room->runQuery($sql);
            $result = $statement->execute(
                array(
                    ':post_title'        =>    $post_title,
                    ':post_content'      =>    $post_content,
                    ':room_ID'        =>    $room_ID,
                    ':user_ID'        =>    $_SESSION["user_id"],
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

            $sql = "UPDATE `post` SET `post_description` = :post_content, `post_name` = :post_title, `post_date` = CURRENT_TIMESTAMP WHERE `post_id` = :post_ID AND `class_id` = :room_ID;";
            
            $statement = $room->runQuery($sql);
            $result = $statement->execute(
                array(
                    ':post_title'        =>    $post_title,
                    ':post_content'      =>    $post_content,
                    ':post_ID'        =>    $post_ID,
                    ':room_ID'        =>    $room_ID
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
            $statement = $room->runQuery("DELETE FROM `post` WHERE `post_id` = :post_ID");
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
            $user_ID = $_SESSION['user_id'];
            $comment = $_POST['comment'];
            $post_ID = $_POST['post_ID'];
    
            $statement = $room->runQuery(
                "INSERT INTO `post_comment` (`comment_id`, `user_id`, `post_id`, `comment_content`, `comment_date`)
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
                "DELETE FROM `post_comment` WHERE `comment_id` = :comment_ID"
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
