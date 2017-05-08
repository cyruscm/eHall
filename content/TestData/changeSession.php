<?php
include_once '../../includes/db_connect.php';
include_once '../../includes/functions.php';
sec_session_start();
if (isset($_POST['data'])) {
	echo $_SESSION['fName'];
	$data = explode(":",$_POST['data']);
	$_SESSION['test'.$data[0]] = $data[1];
	echo "success";
}