<?php
require_once('../class-function.php');
$room = new DTFunction();
if (isset($_POST["operation"])) {
    if ($_POST["operation"] == "submit_module") {
        try {
            $statement = $room->runQuery(
                "INSERT INTO `room_module` (`mod_ID`, `mod_Title`, `room_ID`) VALUES (NULL, :module_title, :room_ID);"
            );
            $result = $statement->execute(
                array(
                    ':room_ID'    =>    $_POST["room_ID"],
                    ':module_title'    =>    ucwords(strtolower($_POST["module_title"]))
                )
            );

            if (!empty($result)) {
                echo 'Successfully Added';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "module_edit") {
        try {
            $statement = $room->runQuery(
                "UPDATE `room_module` SET `mod_Title` = :module_title WHERE `room_module`.`mod_ID` = :mod_ID"
            );
            $result = $statement->execute(
                array(
                    ':mod_ID'    =>    $_POST["module_ID"],
                    ':module_title'    =>    ucwords(strtolower($_POST["module_title"]))
                )
            );

            if (!empty($result)) {
                echo 'Successfully Updated';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "delete_module") {
        try {
            $statement = $room->runQuery(
                "DELETE FROM `room_module` WHERE `mod_ID` = :mod_ID"
            );
            $result = $statement->execute(
                array(
                    ':mod_ID'    =>    $_POST["module_ID"]
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
