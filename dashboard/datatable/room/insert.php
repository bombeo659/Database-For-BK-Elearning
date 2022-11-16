<?php
require_once('../class-function.php');
$room = new DTFunction();

if (isset($_POST["operation"])) {
    if ($_POST["operation"] == "add_classroom") {
        try {
            $teacher_ID = $_POST["rid_ID"];
            $section_ID = $_POST["teacher_section"];
            $semester_ID = $_POST["teacher_semester"];
            $stmt1 = $room->runQuery("SELECT * FROM `room` WHERE 
			rid_ID = '$teacher_ID' AND section_ID = '$section_ID' AND sem_ID = '$semester_ID' LIMIT 1");
            $stmt1->execute();
            $rs = $stmt1->fetchAll();
            if ($stmt1->rowCount() > 0) {
                echo "You cannot add same teacher in same Semester & Section";
            } else {
                $q = "SELECT * FROM `ref_semester` WHERE sem_ID = " . $semester_ID . ";";
                $s1 = $room->runQuery($q);
                $s1->execute();
                $r1 = $s1->fetchAll();
                foreach ($r1 as $rz) {
                    if ($rz["stat_ID"] == 0) {
                        $status_ID = 2;
                    } else {
                        $status_ID = 1;
                    }
                }

                $sql = "INSERT INTO `room` (`room_ID`, `rid_ID`, `section_ID`, `sem_ID`, `status_ID`) 
				    VALUES ( NULL, :teacher_ID, :section_ID, :semester_ID, :status_ID);";
                $statement = $room->runQuery($sql);
                $result = $statement->execute(array(
                    ':teacher_ID'      =>    $teacher_ID,
                    ':section_ID'      =>    $section_ID,
                    ':semester_ID'     =>    $semester_ID,
                    ':status_ID'       =>    $status_ID)
                );
                if (!empty($result)) {
                    echo 'Successfully Added';
                } else {
                    echo 'Failed! Some things wrong!';
                }
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "update_classroom") {
        try {
            $room_ID = $_POST["room_ID"];
            $teacher_ID = $_POST["rid_ID"];
            $section_ID = $_POST["teacher_section"];
            $semester_ID = $_POST["teacher_semester"];
            $stmt1 = $room->runQuery("SELECT * FROM `room` WHERE 
			rid_ID = '$teacher_ID' AND section_ID = '$section_ID' AND sem_ID = '$semester_ID' LIMIT 1");
            $stmt1->execute();
            $rs = $stmt1->fetchAll();
            if ($stmt1->rowCount() > 0) {
                echo "You cannot add same teacher in same Semester & Section";
            } else {
                $q = "SELECT * FROM `ref_semester` WHERE sem_ID = " . $semester_ID . ";";
                $s1 = $room->runQuery($q);
                $s1->execute();
                $r1 = $s1->fetchAll();
                foreach ($r1 as $rz) {
                    if ($rz["stat_ID"] == 0) {
                        $status_ID = 2;
                    } else {
                        $status_ID = 1;
                    }
                }

                $sql = "UPDATE `room` SET
                `rid_ID` = '$teacher_ID', `section_ID` = '$section_ID', `sem_ID` = '$semester_ID', `status_ID` = '$status_ID'
                WHERE `room`.`room_ID` = $room_ID";
                $statement = $room->runQuery($sql);
                $result = $statement->execute();
                if (!empty($result)) {
                    echo 'Successfully Updated';
                } else {
                    echo 'Failed! Some things wrong!';
                }
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "delete_classroom") {
        try {
            $statement = $room->runQuery("DELETE FROM `room` WHERE `room_ID` = :room_ID");
            $result = $statement->execute(array(':room_ID' => $_POST["room_ID"]));

            if (!empty($result)) {
                echo 'Successfully Deleted';
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }        

    //ROOM LIST OF STUDENT
    if ($_POST["operation"] == "submit_student") {
        try {
            $vstmt = $room->runQuery(
                "SELECT * FROM `room_student` WHERE rsd_ID = :rsd_ID AND room_ID = :room_ID"
            );
            $vstmt->execute(
                array(
                    ':rsd_ID'    =>    $_POST["rsd_ID"],
                    ':room_ID'    =>    $_POST["room_ID"]
                )
            );
            if ($vstmt->rowCount() > 0) {
                echo 'You cannot add same student in this room.';
            } else {
                $statement = $room->runQuery(
                    "INSERT INTO `room_student` (`res_ID`, `rsd_ID`, `room_ID`) VALUES (NULL, :rsd_ID, :room_ID);"
                );
                $result = $statement->execute(
                    array(
                        ':rsd_ID'    =>    $_POST["rsd_ID"],
                        ':room_ID'    =>    $_POST["room_ID"]
                    )
                );
                if (!empty($result)) {
                    echo 'Successfully Added';
                } else echo 'Somethings Wrong!' . $_POST["rsd_ID"] . ' and ' . $_POST["room_ID"];
            }
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }

    if ($_POST["operation"] == "delete_student") {
        try {
            $statement = $room->runQuery("DELETE FROM `room_student` WHERE `res_ID` = :res_ID");
            $result = $statement->execute(
                array(
                    ':res_ID'    =>    $_POST["student_ID"]
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
