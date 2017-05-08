<?php 
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';
sec_session_start();
if (isset($_POST)) {
	submitCompletion($_SESSION['user_id'], $_POST['id'], $_POST['data'], 1, $_POST['time'], $mysqli);
	die();
} else { 
	echo "No Valid Data";
}
