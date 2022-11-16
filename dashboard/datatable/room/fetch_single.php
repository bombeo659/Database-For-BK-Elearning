<?php
require_once('../class-function.php');
$room = new DTFunction();

if (isset($_POST['action'])) {
    try {
        $output = array();
        // $stmt = $room->runQuery("SELECT * FROM `ref_section` WHERE section_ID  = '" . $_POST["section_ID"] . "' LIMIT 1");
        $stmt = $room->runQuery("SELECT
        CONCAT(rid.rid_FName,' ', rid.rid_MName,'. ', rid.rid_LName) room_adviser,
        rm.rid_ID,
        sec.section_ID,
        sem.sem_ID
        FROM `room` `rm`
        LEFT JOIN `ref_section` `sec` ON `sec`.`section_ID` = `rm`.`section_ID`
        LEFT JOIN `record_instructor_details` `rid` ON `rid`.`rid_ID` = `rm`.`rid_ID`
        LEFT JOIN `ref_semester` `sem` ON sem.sem_ID = `rm`.`sem_ID`
        WHERE room_ID = '" . $_POST["room_ID"] . "' LIMIT 1
    ");
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            $output["teacher_name"] = $row["room_adviser"];
            $output["section_ID"] = $row["section_ID"];
            $output["sem_ID"] = $row["sem_ID"];
        }
    } catch (PDOException $e) {
        echo "There is some problem in connection: " . $e->getMessage();
    }

    echo json_encode($output);
}
