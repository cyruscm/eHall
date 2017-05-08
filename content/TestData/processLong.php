<?php 
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';
sec_session_start();
if (isset($_POST)) {
	echo "<pre>";
		echo "Submitting User: ".$_SESSION['user_id'];
		print_r($_POST);
	echo "</pre>";
	submitCompletion($_SESSION['user_id'], $_POST['id'], $_POST['data'], 2, $_POST['time'], $mysqli);
	die();
} else { 
	echo "No Valid Data";
}
