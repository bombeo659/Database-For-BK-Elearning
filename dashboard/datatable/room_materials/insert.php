<?php
require_once('../class-function.php');
$account = new DTFunction();
if (isset($_POST["m_operation"])) {

    if ($_POST["m_operation"] == "material_submit") {
        try {
            $room_ID = $_POST["room_ID"];
            $mod_ID = $_POST["mod_ID"];
            $material_name = $_POST["material_name"];
            $file_origin_name = $_FILES["material_file"]["name"];
            $mcn = array($material_name, $file_origin_name);
            $material_names = json_encode($mcn);
            $file_mime_type = $_FILES["material_file"]["type"];

            if (isset($_FILES['material_file']['tmp_name'])) {
                $file_Data = addslashes(file_get_contents($_FILES['material_file']['tmp_name']));
                $sql = "INSERT INTO `attachment` 
                (`attachment_id`, `class_id`, `attachment_name`, `attachment_mime`, `attachment_data`, `attachment_date`,`module_id`) 
                VALUES (NULL, :room_ID, :material_names, :file_mime_type, '$file_Data', CURRENT_TIMESTAMP,'$mod_ID');";
                $statement = $account->runQuery($sql);
    
                $result = $statement->execute(
                    array(
                        ':room_ID'                =>    $room_ID,
                        ':file_mime_type'        =>    $file_mime_type,
                        ':material_names'        =>    $material_names,
                    )
                );
                if (!empty($result)) {
                    echo 'Successfully Added!';
                }
            } else {
                $file_Data = '';
                echo "Error: Cann't find data of file!";
            }
            
        } catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
    }


    if ($_POST["m_operation"] == "material_delete") {
        try {
            $statement = $account->runQuery(
                "DELETE FROM `attachment` WHERE `attachment_id` = :attachment_ID"
            );
            $result = $statement->execute(
                array(
                    ':attachment_ID'    =>    $_POST["material_ID"]
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
