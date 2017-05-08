<?php 
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';
sec_session_start();
if (isset($_POST)) {
	echo $_SESSION['user_id'];
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
	submitCompletion($_SESSION['user_id'], $_POST['id'], $_POST['data'], 0, $_POST['time'], $mysqli);
	die();
} else { 
	echo "No Valid Data";
}
