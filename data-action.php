<?php
session_start(); // Starting Session
require_once("class-user.php");
$auth_user = new USER();

if (isset($_POST['operation'])) {
	$message = array();
	if ($_POST['operation'] === "submit_login") {
		$login_user = strip_tags($_POST['login_user']);
		$login_password = strip_tags($_POST['login_password']);

		if ($auth_user->doLogin($login_user, $login_password)) {
			$message["success"] = "Successfully Login";
		} else {
			$message["error"] = "Invalid username or password!";
		}
	}
	// if ($_POST['operation'] === "submit_register") {
	// 	$reg_studentnum = strip_tags($_POST['reg_studentnum']);
	// 	$reg_password = strip_tags($_POST['reg_password']);
	// 	$reg_cpassword = strip_tags($_POST['reg_cpassword']);
	// 	$reg_email = strip_tags($_POST['reg_email']);

	// 	if ($reg_password === $reg_cpassword) {
	// 		if ($auth_user->register($reg_studentnum, $reg_password, $reg_email)) {
	// 			$message["success"] = "Successfully Register";
	// 		} else {
	// 			$message["error"] = "Error Register";
	// 		}
	// 	} else {
	// 		$message["error"] = "Password Not Match";
	// 	}
	// }
	echo json_encode($message, true);
}
