<?php
require_once("../../../class-user.php");

$chingchong = new USER();

if (isset($_POST['action'])) {
    try {
        if ($_POST['action'] == "get_topic") {
            $output = array();
            $stmt = $chingchong->runQuery("SELECT * FROM `module_topic` WHERE topic_id = '" . $_POST["topic_ID"] . "' 
				LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetchAll();
            foreach ($result as $row) {
                $output["mtopic_ID"] = $row["topic_id"];
                $output["mtopic_Title"] = $row["topic_title"];
            }
            echo json_encode($output);
        }

        if ($_POST['action'] == "get_subtopic") {
            $output = array();
            $stmt = $chingchong->runQuery("SELECT * FROM `module_subtopic` WHERE subtopic_id  = '" . $_POST["submtop_ID"] . "' 
				LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetchAll();
            foreach ($result as $row) {
                $output["submtop_ID"] = $row["subtopic_id"];
                $output["submtop_Title"] = $row["subtopic_title"];
                $output["submtop_Content"] = $row["subtopic_content"];
            }
            echo json_encode($output);
        }

        if ($_POST['action'] == "add_topic") {
            $mod_ID = $_POST["mod_ID"];
            $topic_title = ucwords(strtolower($_POST["topic_title"]));
            $stmt = $chingchong->runQuery(
                "INSERT INTO `module_topic` (`topic_id`, `module_id`, `topic_title`) VALUES (NULL, '$mod_ID', '$topic_title');"
            );
            $stmt->execute();
            echo "Successfully Added!";
        }

        if ($_POST['action'] == "update_topic") {
            $topic_ID = $_POST["topic_ID"];
            $topic_title = $_POST["topic_title"];
            $stmt = $chingchong->runQuery(
                "UPDATE `module_topic` SET `topic_title` = '$topic_title' WHERE `module_topic`.`topic_id` = $topic_ID;");
            $stmt->execute();
            echo "Successfully Updated!";
        }

        if ($_POST['action'] == "delete_topic") {
            $topic_ID = $_POST["topic_ID"];
            $stmt1 = $chingchong->runQuery("DELETE FROM `module_subtopic` WHERE `topic_id` = $topic_ID");
            $stmt1->execute();

            $stmt2 = $chingchong->runQuery("DELETE FROM `module_topic` WHERE `topic_id` = $topic_ID");
            $stmt2->execute();

            echo "Successfully Deleted!";
        }

        if ($_POST['action'] == "add_subtopic") {
            $mod_ID = $_POST["mod_ID"];
            $topic_ID = $_POST["topic_ID"];
            $subtopic_title = $_POST["subtopic_title"];
            $subtopic_content = $_POST["subtopic_content"];

            $stmt = $chingchong->runQuery("INSERT INTO `module_subtopic` 
			(`subtopic_id`, `topic_id`, `subtopic_title`, `subtopic_content`) 
			VALUES (NULL, '$topic_ID', '$subtopic_title', '$subtopic_content');");
            $stmt->execute();
            echo "Successfully Added!";
        }

        if ($_POST['action'] == "update_subtopic") {
            $submtop_ID = $_POST["submtop_ID"];
            $subtopic_title = $_POST["subtopic_title"];
            $subtopic_content = $_POST["subtopic_content"];

            $stmt = $chingchong->runQuery(
                "UPDATE `module_subtopic` SET `subtopic_title` = '$subtopic_title',`subtopic_content` = '$subtopic_content'
			    WHERE `module_subtopic`.`subtopic_id` = $submtop_ID;");
            $stmt->execute();

            echo "Successfully Updated!";
        }

        if ($_POST['action'] == "delete_subtopic") {
            $subtopicID = $_POST["subtopicID"];
            $stmt1 = $chingchong->runQuery("DELETE FROM `module_subtopic` WHERE `subtopic_id` = $subtopicID");
            $stmt1->execute();

            echo "Successfully Deleted!";
        }
    } catch (PDOException $e) {
        echo "There is some problem in connection: " . $e->getMessage();
    }
}
