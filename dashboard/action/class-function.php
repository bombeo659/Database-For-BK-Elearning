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

    public function getUserPic($user_id)
    {
        $query = "SELECT user_img FROM `user_account` WHERE user_id = $user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            if (!empty($row['user_img'])) {
                $_SESSION['user_img']  = 'data:image/jpeg;base64,' . base64_encode($row['user_img']);
            } else {
                $_SESSION['user_img']  = "../assets/img/users/default.jpg";
            }
        }
    }
}
