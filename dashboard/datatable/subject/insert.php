<?php
require_once('../class-function.php');
$subject = new DTFunction();
if (isset($_POST["operation"])) {
    if ($_POST["operation"] == "submit_subject") {
        try {
            $subject_title = $_POST["subject_title"];
            $faculty_id = $_POST["subject_faculty"];
            $sem_id = $_POST["subject_semester"];

            $sql = "INSERT INTO `subject`  (`subject_id`, `subject_name`, `faculty_id`, `sem_id`) 
			VALUES (NULL, :subject_title, :faculty_id, :sem_id);";

            $statement = $subject->runQuery($sql);
            $result = $statement->execute(
                array(
                    ':subject_title'        =>    $subject_title,
                    ':faculty_id'        =>    $faculty_id,
                    ':sem_id'        =>    $sem_id
                )
            );
            if (!empty($result)) {
                echo 'Successfully Added';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "subject_edit") {
        try {
            $subject_title = $_POST["subject_title"];
            $faculty_id = $_POST["subject_faculty"];
            $sem_id = $_POST["subject_semester"];

            $sql = "UPDATE `subject` SET `subject_name` = :subject_title, `faculty_id` = :faculty_ID, `sem_id` = :sem_id WHERE `subject`.`subject_id` =  :subject_ID;";
            $statement = $subject->runQuery($sql);

            $result = $statement->execute(
                array(
                    ':subject_ID'    =>    $_POST["subject_ID"],
                    ':subject_title'        =>    $subject_title,
                    ':faculty_ID' => $faculty_id,
                    ':sem_id' => $sem_id
                )
            );
            if (!empty($result)) {
                echo 'Successfully Updated';
            }
        }  catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "delete_subject") {
        try {
            $statement = $subject->runQuery(
            "DELETE FROM `subject` WHERE `subject_id` = :subject_id"
            );
            $result = $statement->execute(
                array(
                    ':subject_id'    =>    $_POST["subject_ID"]
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
