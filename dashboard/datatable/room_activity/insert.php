<?php
require_once('../class-function.php');
$account = new DTFunction();
session_start();
if (isset($_POST["operation"])) {

    if ($_POST["operation"] == "test_submit") {
        try {
            $room_ID = $_POST["room_ID"];
            $test_name = $_POST["test_name"];
            $test_expired = $_POST["test_expired"];
            $test_timer = $_POST["test_timer"];
            $test_type = $_POST["test_type"];
            $test_status = $_POST["test_status"];

            $sql = "INSERT INTO `room_test` (`test_ID`, `room_ID`, `test_Name`, `test_Added`, `test_Expired`, `test_Timer`, `status_ID`, `tstt_ID`) 
			VALUES (NULL, :room_ID, :test_name, CURRENT_TIMESTAMP, :test_expired, :test_timer, :test_status, :test_type);";
            $statement = $account->runQuery($sql);

            $result = $statement->execute(
                array(
                    ':room_ID'        =>    $room_ID,
                    ':test_name'      =>    $test_name,
                    ':test_expired'   =>    $test_expired,
                    ':test_timer'     =>    $test_timer,
                    ':test_status'    =>    $test_status,
                    ':test_type'      =>    $test_type,
                )
            );
            if (!empty($result)) {
                echo 'Successfully Added';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "test_edit") {
        try {
            $room_ID = $_POST["room_ID"];
            $test_ID = $_POST["test_ID"];
            $test_name = $_POST["test_name"];
            $test_expired = $_POST["test_expired"];
            $test_timer = $_POST["test_timer"];
            $test_type = $_POST["test_type"];
            $test_status = $_POST["test_status"];
            $sql = "UPDATE `room_test` 
			SET 
			`test_Name` = :test_name,
			`test_Expired` = :test_expired,
			`test_Timer` = :test_timer,
			`status_ID` = :test_status,
			`tstt_ID` = :test_type
			 WHERE `test_ID` = :test_ID AND `room_ID` = :room_ID";
            $statement = $account->runQuery($sql);

            $result = $statement->execute(
                array(
                    ':room_ID'        =>    $room_ID,
                    ':test_ID'        =>    $test_ID,
                    ':test_name'      =>    $test_name,
                    ':test_expired'   =>    $test_expired,
                    ':test_timer'     =>    $test_timer,
                    ':test_type'      =>    $test_type,
                    ':test_status'    =>    $test_status,

                )
            );
            if (!empty($result)) {
                echo 'Successfully Updated';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "test_delete") {
        try {
            $statement = $account->runQuery(
                "DELETE FROM `room_test` WHERE `test_ID` = :test_ID"
            );
            $result = $statement->execute(
                array(
                    ':test_ID'    =>    $_POST["test_ID"]
                )
            );

            if (!empty($result)) {
                echo 'Successfully Deleted';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }
    if ($_POST["operation"] == "test_view") {
        try {
            $test_ID = $_POST["test_ID"];
            $user_ID = $_SESSION["user_ID"];
            $statement = $account->runQuery(
                "SELECT * FROM `room_test_score` WHERE test_ID = :test_ID and user_ID = :user_ID 
			ORDER BY `score_ID` DESC   LIMIT 1"
            );
            $statement->execute(
                array(
                    ':test_ID'    =>    $test_ID,
                    ':user_ID'    =>    $user_ID
                )
            );

            $result = $statement->fetchAll();

            if ($statement->rowCount() > 0) {
                foreach ($result as $row) {
                    $score = 'Score: ' . $row['score'];
                }
            } else {
                $score = 'No score';
            }
            echo $score;
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }
}
