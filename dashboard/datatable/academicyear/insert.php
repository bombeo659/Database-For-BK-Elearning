<?php
require_once('../class-function.php');
$acadyear = new DTFunction();
if (isset($_POST["operation"])) {

    if ($_POST["operation"] == "submit_semester") {
        try {
            $semester_start = $_POST["semester_start"];
            $semester_end = $_POST["semester_end"];
            // $semester_stat = $_POST["semester_stat"];
            $semester_stat = 0;

            if ($semester_stat == 1) {
                $s1 = "UPDATE `ref_semester` SET `stat_ID` = '0'";
                $st1 = $acadyear->runQuery($s1);
                $rs1 = $st1->execute();
            }

            $sql = "INSERT INTO `ref_semester` (`sem_ID`, `sem_start`, `sem_end`, `stat_ID`) 
			VALUES (NULL, :semester_start, :semester_end, :semester_stat);";
            $statement = $acadyear->runQuery($sql);

            $result = $statement->execute(
                array(
                    ':semester_start'        =>    $semester_start,
                    ':semester_end'        =>    $semester_end,
                    ':semester_stat'        =>    $semester_stat,
                )
            );
            if (!empty($result)) {
                echo 'Successfully Added';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "semester_edit") {
        $semester_ID = $_POST["semester_ID"];
        $semester_start = $_POST["semester_start"];
        $semester_end = $_POST["semester_end"];
        $semester_stat = $_POST["semester_stat"];

        if ($semester_stat == 1) {
            $s1 = "UPDATE `ref_semester` SET `stat_ID` = '0'";
            $st1 = $acadyear->runQuery($s1);
            $rs1 = $st1->execute();
            $s2 = "UPDATE `ref_semester` SET `stat_ID` = '1' WHERE `sem_ID` = $semester_ID; ";
            $st2 = $acadyear->runQuery($s2);
            $rs2 = $st2->execute();
        }

        $sql = "UPDATE `ref_semester` 
		SET `sem_start` = :semester_start ,
		`sem_end` = :semester_end 
		WHERE sem_ID = :semester_ID";
        $statement = $acadyear->runQuery($sql);

        $result = $statement->execute(
            array(
                ':semester_ID'        =>    $semester_ID,
                ':semester_start'        =>    $semester_start,
                ':semester_end'        =>    $semester_end,
            )
        );
        if (!empty($result)) {
            echo 'Successfully Updated';
        }
    }

    if ($_POST["operation"] == "delete_semester") {
        try {
           $statement = $acadyear->runQuery(
            "DELETE FROM `ref_semester` WHERE `sem_ID` = :sem_ID"
            );
            $result = $statement->execute(
                array(
                    ':sem_ID'    =>    $_POST["semester_ID"]
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
