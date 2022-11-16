<?php
require_once('../../db-config.php');

class ACFunction
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

    public function getUserPic($user_ID)
    {
        $query = "SELECT user_Img FROM `user_account` WHERE user_ID = $user_ID LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            if (!empty($row['user_Img'])) {
                $_SESSION['user_Img']  = 'data:image/jpeg;base64,' . base64_encode($row['user_Img']);
            } else {
                $_SESSION['user_Img']  = "../assets/img/users/default.jpg";
            }
        }
    }
}
