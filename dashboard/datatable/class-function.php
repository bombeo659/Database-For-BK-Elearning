<?php
require_once('../../../db-config.php');

class DTFunction
{
    private $conn;
    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

    public function get_total_all_records($q)
    {
        try {
            $statement = $stmt = $this->conn->prepare("$q");
            $statement->execute();
            $result = $statement->fetchAll();
            return $statement->rowCount();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function check_user_level($var)
    {
        try {
            $statement = $stmt = $this->conn->prepare("SELECT * FROM `user_level` WHERE `lvl_ID` = $var");
            $statement->execute();
            $result = $statement->fetchAll();
            foreach ($result as $row) {
                $level_name = $row["lvl_Name"];
            }
            return $level_name;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insert_subject($subject_title, $Abbreviation)
    {
        try {
            $sql = "INSERT INTO `ref_subject` (`subject_ID`, `subject_Title`,`Abbreviation`) 
            VALUES (NULL, '$subject_title','$Abbreviation');";
            $statement = $this->runQuery($sql);
            $result = $statement->execute();
            return $last_id = $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function generate_account($id, $user_type)
    {
        try {
            $user_type_acro = "";

            if ($user_type == "student") {
                $user_type_acro = "rsd";
                $sc_id = "rsd_StudNum";
                $slvl = 1;
            }
            if ($user_type == "instructor") {
                $user_type_acro = "rid";
                $sc_id = "rid_EmpID";
                $slvl = 2;
            }
            if ($user_type == "admin") {
                $user_type_acro = "rad";
                $sc_id = "rad_EmpID";
                $slvl = 3;
            }
            $q1 = "SELECT * FROM `record_" . $user_type . "_details` WHERE " . $user_type_acro . "_ID = '$id'";
            $stmt1 = $this->conn->prepare($q1);
            $stmt1->execute();
            $result1 = $stmt1->fetchAll();

            foreach ($result1 as $row) {
                $firstname = $row[$user_type_acro . "_FName"];
                $sc_id = $row[$sc_id];
            }
            $ac_user = $sc_id;
            $ac_pass = strtolower($firstname) . '123';

            $n_pass = password_hash($ac_pass, PASSWORD_DEFAULT);

            $q2 = "INSERT INTO `user_account` (`user_ID`, `lvl_ID`, `user_Img`, `user_Name`, `user_Pass`, `user_Registered`) VALUES (NULL, '$slvl', NULL, '$ac_user', '$n_pass', CURRENT_TIMESTAMP);";
            $stmt2 = $this->conn->prepare($q2);
            $stmt2->execute();
            $last_id = $this->conn->lastInsertId();

            $q3  = "UPDATE `record_" . $user_type . "_details` SET `user_ID` = '$last_id' WHERE `" . $user_type_acro . "_ID` = '$id'";
            $stmt3 = $this->conn->prepare($q3);
            $r3 = $stmt3->execute();

            if (!empty($r3)) {
                echo '<div class="text-center"><strong>Username:</strong>' . $ac_user . '<br>';
                echo '<strong>Password:</strong>' . $ac_pass . '<br>';
                echo 'Account Successfully Created</div>';
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insert_attendance($room_ID, $stud_ID, $clicked_day, $attnd_stat)
    {
        try {
            $sql = "INSERT INTO `room_student_attendance` 
                (`attendance_ID`, `room_ID`, `res_ID`, `attendance_Time`, `attendance_Status`) 
                VALUES ( NULL, '$room_ID', '$stud_ID', '$clicked_day', '$attnd_stat');";
            $statement = $this->runQuery($sql);
            $result = $statement->execute();
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function insert_choice($question_ID, $is_correct, $choice)
    {
        try {
            $sql = " INSERT INTO `room_test_choices` 
            (`choice_ID`, `question_ID`, `is_correct`, `choice`)
             VALUES (NULL, '$question_ID', '$is_correct', '$choice');";
            $statement = $this->runQuery($sql);
            $result = $statement->execute();
            return $last_id = $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function insert_question($question, $test_ID, $xtype)
    {
        try {
            $sql = " INSERT INTO `room_test_questions` (`question_ID`, `test_ID`, `question`,`type`) 
            VALUES (NULL, $test_ID, '$question','$xtype');";
            $statement = $this->runQuery($sql);
            $result = $statement->execute();
            return $last_id = $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function setnewChoice($choice_ID, $newChoice)
    {
        try {
            $sql = "UPDATE `room_test_choices` SET `choice` = '$newChoice' 
                    WHERE `room_test_choices`.`choice_ID` = $choice_ID;";
            $statement = $this->runQuery($sql);
            $result = $statement->execute();
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function check_choice($choice_ID)
    {
        try {
            $sql = " SELECT is_correct FROM `room_test_choices` WHERE choice_ID =  $choice_ID and is_correct = 1;";
            $statement = $this->runQuery($sql);
            $statement->execute();
            $result = $statement->rowCount();
            return $result;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function test_score($score, $user_ID, $test_ID)
    {
        try {
            $sql = "INSERT INTO `room_test_score` 
            (`score_ID`, `test_ID`, `score`, `user_ID`) 
            VALUES (NULL, '$test_ID', '$score', '$user_ID');";
            $statement = $this->runQuery($sql);
            $statement->execute();
            return $last_id = $this->conn->lastInsertId();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function atmp_count($user_ID, $test_ID)
    {
        try {
            $sql = "SELECT `count` atmp_count FROM `room_test_attemp` 
                    WHERE user_ID = '$user_ID' and test_ID = '$test_ID'";
            $statement = $this->runQuery($sql);
            $statement->execute();
            $result = $statement->fetchAll();
            $atmp_count = 0;
            foreach ($result as $row) {
                $atmp_count = $row["atmp_count"];
            }
            return $atmp_count;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function student_level()
    {
        if ($_SESSION['lvl_ID'] == "1") {
            return true;
        } else {
            return false;
        }
    }
    public function instructor_level()
    {
        if ($_SESSION['lvl_ID'] == "2") {
            return true;
        } else {
            return false;
        }
    }
    public function admin_level()
    {
        if ($_SESSION['lvl_ID'] == "3") {
            return true;
        } else {
            return false;
        }
    }

    public function subtopic($mtopic)
    {
        try {
            $sql = " SELECT * FROM `room_module_subtopic` WHERE mtopic_ID = '$mtopic'";
            $statement = $this->runQuery($sql);
            $statement->execute();

            $result = $statement->fetchAll();
            $atmp_count = 0;
            $li = "";

            foreach ($result as $row) {
                if ($this->student_level()) {
                    $btn = "";
                } else {
                    $btn = '<div class="btn-group float-right" style="margin-top:-25px;">
                    <button type="button" class="btn btn-secondary btn-sm rounded mr-1 edit_subtopic" sub-topic="' . $row["submtop_ID"] . '">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm rounded delete_subtopic" sub-topic="' . $row["submtop_ID"] . '">Delete</button> </div>';
                }
                $li .=  '<li class="list-group-item " >
                <div class="view_subtopic" sub-topic="' . $row["submtop_ID"] . '">' 
                    . $row["submtop_Title"] . '</div>' . $btn . 
                '</li>';
            }
            return $li;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
