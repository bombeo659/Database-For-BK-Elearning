<?php
require_once('../class-function.php');
$subject = new DTFunction();

if (isset($_POST['action'])) {
    $output = array();
    $stmt = $subject->runQuery("SELECT * FROM `subject` WHERE subject_id  = '" . $_POST["subject_ID"] . "' LIMIT 1");
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row) {
        $output["subject_ID"] = $row["subject_id"];
        $output["subject_Name"] = $row["subject_name"];
        $output["faculty_ID"] = $row["faculty_id"];
        $output["semester_ID"] = $row["sem_id"];
    }
    echo json_encode($output);
}   