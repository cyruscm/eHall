<?php
include_once 'db_connect.php';
include_once 'functions.php';
 
sec_session_start(); // Our custom secure way of starting a PHP session.
 
if (isset($_POST['email'], $_POST['p'])) {
    $email = $_POST['email'];
    $password = $_POST['p']; 
	$error_msg = login($email, $password, $mysqli);
    if ($error_msg==3){
        // Login success 
        if ($_POST['remember']=='true'){
          $token = md5(uniqid(rand(), true));
          setcookie("eHall:loginremember", $_SESSION['user_id'].".".$token, time() + (86400 * 90), "/");
          if ($stmt = $mysqli->prepare("INSERT INTO remember_sessions
                             (user, token, expire) 
                              VALUES (?, ?, ?)")){
          $stmt->bind_param('iss', $_SESSION['user_id'], $token, date('Y-m-d', strtotime("+90 days")));
          $stmt->execute();
          }
        }
        header('Location: ../home.php?p=home');
		die();
    } else {
        // Login failed 
		header('Location: ../login.php?err=' .$error_msg. '');
		die();
    }
} else {
    // The correct POST variables were not sent to this page. 
	header('Location: ../login.php?err=4');
		die();
}

?>