<?php
require_once('db-config.php');
class USER
{
    private $conn;
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
	}

    // log in function 
	public function doLogin($login_user, $login_password)
	{
		try {
			$stmt = $this->conn->prepare("SELECT user_id, lvl_ID, user_Name, user_Pass, user_Img FROM user_account WHERE user_Name=:user_Name");
			$stmt->execute(array(':user_Name' => $login_user));
			$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($stmt->rowCount() == 1) {
				if (password_verify($login_password, $userRow['user_Pass'])) {
					$_SESSION['lvl_ID'] = $userRow['lvl_ID'];
					$_SESSION['user_id'] = $userRow['user_id'];
					$_SESSION['user_Name'] = $userRow['user_Name'];
					if (!empty($userRow['user_Img'])) {
						$s_img = 'data:image/jpeg;base64,' . base64_encode($userRow['user_Img']);
					} else {
						$s_img = "../assets/img/users/default.jpg";
					}
					$_SESSION['user_Img'] = $s_img;
					return true;
				} else {
					return false;
				}
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	// register function
	// public function register($reg_studentnum, $reg_password, $reg_email)
	// {
	// 	try {
	// 		$stmt = $this->conn->prepare("SELECT * FROM `record_student_details` WHERE rsd_StudNum = :reg_studentnum OR rsd_Email = :reg_email LIMIT 1");
	// 		$stmt->bindparam(":reg_studentnum", $reg_studentnum);
	// 		$stmt->bindparam(":reg_email", $reg_email);
	// 		$stmt->execute();
	// 		$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
	// 		if ($stmt->rowCount() == 1) {
	// 			$rsd_ID = $userRow["rsd_ID"];
	// 			$new_password = password_hash($reg_password, PASSWORD_DEFAULT);

	// 			$stmt = $this->conn->prepare("INSERT INTO `user_account` (`user_id`, `lvl_ID`, `user_Img`, `user_Name`, `user_Pass`, `user_Registered`) VALUES (NULL, 1, NULL, :reg_studentnum, :reg_password, CURRENT_TIMESTAMP);");

	// 			$stmt->bindparam(":reg_studentnum", $reg_studentnum);
	// 			$stmt->bindparam(":reg_password", $new_password);
	// 			$stmt->execute();
	// 			$user_id = $this->conn->lastInsertId();

	// 			$stmt = $this->conn->prepare("UPDATE `record_student_details` SET `user_id` = :user_id WHERE `record_student_details`.`rsd_ID` = :rsd_ID;");
	// 			$stmt->bindparam(":user_id", $user_id);
	// 			$stmt->bindparam(":rsd_ID", $rsd_ID);
	// 			$stmt->execute();

	// 			return $stmt;
	// 		}
	// 	} catch (PDOException $e) {
	// 		echo $e->getMessage();
	// 	}
	// }

	public function check_accesslevel($page_level)
	{
		if (isset($_SESSION['lvl_ID'])) {
			if ($_SESSION['lvl_ID'] <  $page_level) {
				header('Location: ../error');
			}
		}
	}

	// redirect function
    public function redirect_dashboard()
	{
		if (isset($_SESSION['lvl_ID'])) {
			header("Location: dashboard");
		}
	}

	public function redirect($url)
	{
		header("Location: $url");
	}

	// check isLogin -> if true -> go to dashboard
    public function is_loggedin()
	{
		if (isset($_SESSION['user_id'])) {
			return true;
		}
	}

	// log out function
	public function doLogout()
	{
		session_destroy();
		unset($_SESSION['user_id']);
		return true;
	}

	public function parseUrl()
	{
		if (isset($_GET['url'])) {
			$url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
			return $url;
		}
	}

	// get user info
    public function getUsername()
	{
		echo $_SESSION['user_Name'];
	}
	public function getUserPic()
	{
		echo $_SESSION['user_Img'];
	}

    public function profile_name()
	{
		$user_type = "";
		$user_type_acro = "";
		if ($_SESSION['lvl_ID'] == "1") {
			$user_type = "student";
			$user_type_acro = "sd";
		}
		if ($_SESSION['lvl_ID'] == "2") {
			$user_type = "instructor";
			$user_type_acro = "ind";
		}
		if ($_SESSION['lvl_ID'] == "3") {
			$user_type = "admin";
			$user_type_acro = "ad";
		}
		$query = "SELECT A." . $user_type_acro . "_FName, A." . $user_type_acro . "_MName, A." . $user_type_acro . "_LName FROM `" . $user_type . "_details` A JOIN " . $user_type . "s_has_account B ON A." .$user_type_acro . "_id = B." . $user_type_acro . "_id WHERE `B`.`user_id` = " . $_SESSION['user_id'];
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		if ($stmt->rowCount() == 1) {
			foreach ($result as $row) {
				$full_name = "";
				$full_name .= $row[$user_type_acro . "_FName"] . " ";
				$full_name .= $row[$user_type_acro . "_LName"] . " ";
				$full_name .= $row[$user_type_acro . "_MName"];
			}
			echo $full_name;
		} else {
			echo "Empty";
		}
	}

    public function profile_sex()
	{
		$user_type = "";
		if ($_SESSION['lvl_ID'] == "1") {
			$user_type = "student";
			$user_type_acro = "sd";
		}
		if ($_SESSION['lvl_ID'] == "2") {
			$user_type = "instructor";
			$user_type_acro = "ind";
		}
		if ($_SESSION['lvl_ID'] == "3") {
			$user_type = "admin";
			$user_type_acro = "ad";
		}

		$query = "SELECT `" . $user_type_acro . "_gender`  FROM `" . $user_type . "_details` A JOIN " . $user_type . "s_has_account B ON A." .$user_type_acro . "_id = B." . $user_type_acro . "_id WHERE `B`.`user_id` = " . $_SESSION['user_id'];

		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		if ($stmt->rowCount() == 1) {
			foreach ($result as $row) {
				echo $row[$user_type_acro . "_gender"];
			}
		} else {
			echo "Empty";
		}
	}

	public function profile_school_id()
	{
		$user_type = "";
		$id_type = "";
		if ($_SESSION['lvl_ID'] == "1") {
			$user_type = "student";
			$id_type = "sd_StudNum";
			$user_type_acro = "sd";
		}
		if ($_SESSION['lvl_ID'] == "2") {
			$user_type = "instructor";
			$id_type = "ind_EmpID";
			$user_type_acro = "ind";
		}
		if ($_SESSION['lvl_ID'] == "3") {
			$user_type = "admin";
			$id_type = "ad_EmpID";
			$user_type_acro = "ad";
		}
		$query = "SELECT A." . $id_type . " FROM `" . $user_type . "_details` A JOIN " . $user_type . "s_has_account B ON A." .$user_type_acro . "_id = B." . $user_type_acro . "_id WHERE `B`.`user_id` = " . $_SESSION['user_id'];
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		if ($stmt->rowCount() == 1) {
			foreach ($result as $row) {
				echo $row[$id_type];
			}
		} else {
			echo "Empty";
		}
	}

	public function profile_email()
	{
		$user_type = "";
		$user_type_acro = "";
		if ($_SESSION['lvl_ID'] == "1") {
			$user_type = "student";
			$user_type_acro = "sd";
		}
		if ($_SESSION['lvl_ID'] == "2") {
			$user_type = "instructor";
			$user_type_acro = "ind";
		}
		if ($_SESSION['lvl_ID'] == "3") {
			$user_type = "admin";
			$user_type_acro = "ad";
		}
		$query = "SELECT A." . $user_type_acro . "_email FROM `" . $user_type . "_details` A JOIN " . $user_type . "s_has_account B ON A." .$user_type_acro . "_id = B." . $user_type_acro . "_id WHERE `B`.`user_id` = " . $_SESSION['user_id'];
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();


		if ($stmt->rowCount() == 1) {
			foreach ($result as $row) {
				echo $row[$user_type_acro . "_email"];
			}
		} else {
			echo "Empty";
		}
	}

	public function profile_address()
	{
		$user_type = "";
		$user_type_acro = "";
		if ($_SESSION['lvl_ID'] == "1") {
			$user_type = "student";
			$user_type_acro = "sd";
		}
		if ($_SESSION['lvl_ID'] == "2") {
			$user_type = "instructor";
			$user_type_acro = "ind";
		}
		if ($_SESSION['lvl_ID'] == "3") {
			$user_type = "admin";
			$user_type_acro = "ad";
		}
		$query = "SELECT A." . $user_type_acro . "_address FROM `" . $user_type . "_details` A JOIN " . $user_type . "s_has_account B ON A." .$user_type_acro . "_id = B." . $user_type_acro . "_id WHERE `B`.`user_id` = " . $_SESSION['user_id'];
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();
		if ($stmt->rowCount() == 1) {
			foreach ($result as $row) {
				echo $row[$user_type_acro . "_address"];
			}
		} else {
			echo "Empty";
		}
	}

	public function ref_subject()
	{
		$query = "SELECT * FROM `subject`";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();

		foreach ($result as $row) {
			echo '<option value="' . $row["subject_id"] . '">' . $row["subject_name"] . '</option>';
		}
	}

	public function ref_section()
	{
		$query = "SELECT * FROM `ref_section`";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();

		foreach ($result as $row) {
			echo '<option value="' . $row["section_ID"] . '">' . $row["section_Name"] . '</option>';
		}
	}
	public function ref_semester()
	{
		$query = "SELECT *,CONCAT(YEAR(sem_start),' - ',YEAR(sem_end)) sem_year FROM `semester` ORDER BY status_id ASC ";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();

		foreach ($result as $row) {
			$stat_ID = $row["status_id"];
			if ($stat_ID == "1") {
				$stat = " (Active)";
			} else {
				$stat = " (Deactivate)";
			}
			echo '<option value="' . $row["sem_id"] . '">' . $row["sem_year"] . $stat . '</option>';
		}
	}

	public function ref_test_type()
	{
		$query = "SELECT * FROM `ref_test_type`";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();

		foreach ($result as $row) {
			echo '<option value="' . $row["tstt_ID"] . '">' . $row["tstt_Name"] . '</option>';
		}
	}
	public function ref_status()
	{
		$query = "SELECT * FROM `ref_status`";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();

		foreach ($result as $row) {
			echo '<option value="' . $row["status_ID"] . '">' . $row["status_Name"] . '</option>';
		}
	}

	public function user_faculty_option()
	{
		$query = "SELECT * FROM `faculty`";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();

		foreach ($result as $row) {
			echo '<option value="' . $row["faculty_id"] . '">' . $row["faculty_name"] . '</option>';
		}
	}

	public function user_suffix_option()
	{
		$query = "SELECT * FROM `ref_suffixname`";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();

		foreach ($result as $row) {
			echo '<option value="' . $row["suffix_ID"] . '">' . $row["suffix"] . '</option>';
		}
	}

	public function user_sex_option()
	{
		$query = "SELECT * FROM `ref_sex`";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();

		foreach ($result as $row) {
			echo '<option value="' . $row["sex_ID"] . '">' . $row["sex_Name"] . '</option>';
		}
	}

	public function user_marital_option()
	{
		$query = "SELECT * FROM `ref_marital`";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$result = $stmt->fetchAll();

		foreach ($result as $row) {
			echo '<option value="' . $row["marital_ID"] . '">' . $row["marital_Name"] . '</option>';
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

	public function room_adviser($room_ID)
	{
		$output = array();
		try {
			$query = "SELECT 
			cla.class_id,
			cla.class_name,
			cla.subject_id,
			sub.subject_name,
			ind.ind_fname,
			ind.ind_mname,
			ind.ind_lname,
			ind.ind_id,
			CONCAT(ind.ind_fname,' ', ind.ind_lname,' ', ind.ind_mname) class_adviser,
			CONCAT(YEAR(sem.sem_start),' - ',YEAR(sem.sem_end)) semyear,
			stat.status_id,
			stat.status FROM `class` `cla`
			LEFT JOIN `instructor_details` `ind` ON `ind`.`ind_id` = `cla`.`ind_id`
			LEFT JOIN `status` `stat` ON `stat`.`status_id` = `cla`.`status_id`
			LEFT JOIN `subject` `sub` ON `sub`.`subject_id` = `cla`.`subject_id`
			LEFT JOIN `semester` `sem` ON `sem`.`sem_id` = `sub`.`sem_id`
			WHERE class_id = '" . $room_ID . "' LIMIT 1";

			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$result = $stmt->fetchAll();

			if ($stmt->rowCount() == 1) {
				foreach ($result as $row) {
					$output["fullname"] =  $row["class_adviser"];
					$output["schoolyear"] =  $row["semyear"];
					$output["subject_name"] =  $row["subject_name"];
					$output["class_name"] =  $row["class_name"];
				}
			} else {
				$output["fullname"] =  "Empty";
				$output["schoolyear"] =  "Empty";
				$output["subject_name"] =  "Empty";
				$output["class_name"] =  "Empty";
			}
			return $output;
		} catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
	}

	public function get_module_title($mod_ID) {
		try {
			$query = 'SELECT mod_Title FROM room_module WHERE mod_ID = ' . $mod_ID . ' LIMIT 1';
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
			$result = $stmt->fetchAll();
			if ($stmt->rowCount() == 1) {
				foreach ($result as $row) {
					echo $row['mod_Title'];
				}
			} else {
				echo "Empty";
			}
		} catch (PDOException $e) {
            echo "There is some problem in connection: " . $e->getMessage();
        }
	}

	public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
}
