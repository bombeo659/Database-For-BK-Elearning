<?php
require_once('../class-function.php');
$room = new DTFunction();
if (isset($_POST["operation"])) {
    if ($_POST["operation"] == "submit_module") {
        try {
            $title = ucwords(strtolower($_POST["module_title"]));
            $room_id =  $_POST["room_ID"];
            // echo "SELECT * FROM `module` WHERE `module_title` = '" . $title . "';";
            $stmt1 = $room->runQuery("SELECT * FROM `module` WHERE `module_title` = '" . $title . "';");
            $stmt1->execute();
            $rs = $stmt1->fetchAll();
            if ($stmt1->rowCount() > 0) {
                echo "Error: Module Already Added!";
            } else {
                $statement = $room->runQuery(
                    "INSERT INTO `module` (`module_id`, `class_id`, `module_title`) 
                    VALUES (NULL, ". $room_id . ",'" . $title . "');"
                );
                $result = $statement->execute();

                if (!empty($result)) {
                    echo 'Successfully Added';
                }
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "module_edit") {
        try {
            $statement = $room->runQuery(
                "UPDATE `module` SET `module_title` = :module_title WHERE `module`.`module_id` = :mod_ID"
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
                "DELETE FROM `module` WHERE `module_id` = :mod_ID"
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
