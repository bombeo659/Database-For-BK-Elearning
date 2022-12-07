<?php
require_once('../class-function.php');
$room = new DTFunction();

if (isset($_POST['action'])) {
    $output = array();
    $stmt = $room->runQuery("SELECT crtq.*,
    (SELECT GROUP_CONCAT(crtc.choice_id)  FROM `question_choices` `crtc` WHERE crtc.question_id = crtq.question_id) choice_ID,
    (SELECT GROUP_CONCAT(crtc.is_correct)  FROM `question_choices` `crtc` WHERE crtc.question_id = crtq.question_id) is_correct,
    (SELECT GROUP_CONCAT(crtc.choice)  FROM `question_choices` `crtc` WHERE crtc.question_id = crtq.question_id) choice
    FROM `test_question` `crtq`
    WHERE crtq.question_ID =   '" . $_POST["question_ID"] . "' LIMIT 1");
    
    $stmt->execute();
    $result = $stmt->fetchAll();
    $a = 1;
    if (isset($_REQUEST["type"])) {
        $type = $_REQUEST["type"];
    }
    foreach ($result as $row) {

        $break_c = explode(",", $row["choice"]);
        $break_d = explode(",", $row["is_correct"]);


        $output["question_ID"] = $row["question_id"];
        $output["q_question"] = $row["question"];

        if (isset($_REQUEST["type"])) {
            $type = $_REQUEST["type"];
            if ($type == 1) {
                $output["q_choice_a"] = $break_c[0];
                $output["q_choice_b"] = $break_c[1];
                $output["q_choice_c"] = $break_c[2];
                $output["q_choice_d"] = $break_c[3];
                $break_d[0] == 1 ? $xic = "A" : "";
                $break_d[1] == 1 ? $xic = "B" : "";
                $break_d[2] == 1 ? $xic = "C" : "";
                $break_d[3] == 1 ? $xic = "D" : "";
            }
            if ($type == 2) {
                $output["q_choice_a"] = $break_c[0];
                $output["q_choice_b"] = $break_c[1];
                $break_d[0] == 1 ? $xic = "A" : "";
                $break_d[1] == 1 ? $xic = "B" : "";
            }
        }
        $output["q_is_correct"] = $xic;
        $a++;
    }
    echo json_encode($output);
}
