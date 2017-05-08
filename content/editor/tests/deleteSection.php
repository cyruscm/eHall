<?php
	if (isset($_POST['id'])) {
		include_once '../../../includes/functions.php';
		include_once '../../../includes/db_connect.php';
		toggleActive($_POST['id'], "sections", $mysqli);
	}
?>