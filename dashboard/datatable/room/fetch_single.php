<?php
require_once('../class-function.php');
$room = new DTFunction();

if (isset($_POST['action'])) {
    try {
        $output = array();
        // $stmt = $room->runQuery("SELECT * FROM `ref_section` WHERE section_ID  = '" . $_POST["section_ID"] . "' LIMIT 1");
        $stmt = $room->runQuery("SELECT
        cla.class_id,
        cla.class_name,
        cla.subject_id,
        ind.ind_fname,
        ind.ind_mname,
        ind.ind_lname,
        ind.ind_id,
        CONCAT(ind.ind_fname,' ', ind.ind_lname,' ', ind.ind_mname) class_adviser,
        stat.status_id,
        stat.status FROM `class` `cla`
        LEFT JOIN `instructor_details` `ind` ON `ind`.`ind_id` = `cla`.`ind_id`
        LEFT JOIN `status` `stat` ON `stat`.`status_id` = `cla`.`status_id`
        WHERE class_id = '" . $_POST["room_ID"] . "' LIMIT 1");

        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            $output["teacher_name"] = $row["class_adviser"];
            $output["class_name"] = $row["class_name"];
            $output["subject_id"] = $row["subject_id"];
            $output["ind_id"] = $row["ind_id"];
        }
    } catch (PDOException $e) {
        echo "There is some problem in connection: " . $e->getMessage();
    }

    echo json_encode($output);
}
