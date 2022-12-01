<?php
require_once('../class-function.php');
$class = new DTFunction();

if (isset($_POST["operation"])) {
    if ($_POST["operation"] == "add_classroom") {
        try {
            $class_name = $_POST["class_name"];
            $teacher_ID = $_POST["rid_ID"];
            $subject_ID = $_POST["subject_ID"];
            $stmt1 = $class->runQuery("SELECT * FROM `class` WHERE `subject_id` = " .$subject_ID ." and `class_name` = '" .$class_name . "' LIMIT 1");
            $stmt1->execute();
            $rs = $stmt1->fetchAll();
            if ($stmt1->rowCount() > 0) {
                echo "You cannot add same Class name in same Subject";
            } else {
                $q = "SELECT * FROM `semester` WHERE sem_id IN (select sem_id from `subject` where subject_id = " . $subject_ID . ")";
                $s1 = $class->runQuery($q);
                $s1->execute();
                $r1 = $s1->fetchAll();
                foreach ($r1 as $rz) {
                    if ($rz["status_id"] == 2) {
                        $status_ID = 2;
                    } else {
                        $status_ID = 1;
                    }
                }

                $sql = "INSERT INTO `class` (`class_id`, `class_name`, `subject_id`, `status_id`, `ind_id`) 
				    VALUES ( NULL, :class_name, :subject_id, :status_id, :ind_id);";
                $statement = $class->runQuery($sql);
                $result = $statement->execute(array(
                    ':class_name'      =>    $class_name,
                    ':subject_id'      =>    $subject_ID,
                    ':status_id'     =>    $status_ID,
                    ':ind_id'       =>    $teacher_ID)
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
            $class_ID = $_POST["room_ID"];
            $teacher_ID = $_POST["rid_ID"];
            $class_name = $_POST["class_name"];
            $subject_ID = $_POST["subject_ID"];

            $stmt1 = $class->runQuery("SELECT * FROM `class` WHERE `subject_id` = " .$subject_ID ." and `class_name` = '" .$class_name . "' LIMIT 1");
            $stmt1->execute();
            $rs = $stmt1->fetchAll();
            if ($stmt1->rowCount() > 0) {
                echo "You cannot add same Class name in same Subject";
            } else {
                $q = "SELECT * FROM `semester` WHERE sem_id IN (select sem_id from `subject` where subject_id = " . $subject_ID . ")";
                $s1 = $class->runQuery($q);
                $s1->execute();
                $r1 = $s1->fetchAll();
                foreach ($r1 as $rz) {
                    if ($rz["status_id"] == 2) {
                        $status_ID = 2;
                    } else {
                        $status_ID = 1;
                    }
                }

                $sql = "UPDATE `class` SET
                `ind_id` = '$teacher_ID', `class_name` = '$class_name', `subject_id` = '$subject_ID', `status_id` = '$status_ID'
                WHERE `class`.`class_id` = $class_ID";
                $statement = $class->runQuery($sql);
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
            $statement = $class->runQuery("DELETE FROM `class` WHERE `class_id` = :room_ID");
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
            $vstmt = $class->runQuery(
                "SELECT * FROM `class_student` WHERE sd_id = :rsd_ID AND class_id = :class_ID"
            );
            $vstmt->execute(
                array(
                    ':rsd_ID'    =>    $_POST["rsd_ID"],
                    ':class_ID'    =>    $_POST["room_ID"]
                )
            );
            if ($vstmt->rowCount() > 0) {
                echo 'You cannot add same student in this class.';
            } else {
                $statement = $class->runQuery(
                    "INSERT INTO `class_student` (`class_id`, `sd_id`) VALUES (:class_ID, :rsd_ID);"
                );
                $result = $statement->execute(
                    array(
                        ':rsd_ID'    =>    $_POST["rsd_ID"],
                        ':class_ID'    =>    $_POST["room_ID"]
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
            $statement = $class->runQuery("DELETE FROM `class_student` WHERE `sd_id` = :sd_id and `class_id` = :class_id");
            $result = $statement->execute(
                array(
                    ':sd_id'    =>    $_POST["student_ID"],
                    ':class_id'    =>    $_POST["room_ID"]
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
