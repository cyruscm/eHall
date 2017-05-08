<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <script type="text/JavaScript" src="js/sha512.js"></script> 
</head>
<body>
</body>
</html>

<?php
include_once 'functions.php';
include_once 'db_connect.php';
if (isset($_POST['p'])){
        $random_salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        $password = hash('sha512', $_POST['p'] . $random_salt);
        unset($_POST['p']);
        echo $password." ".$random_salt;
        if ($stmt = $mysqli->prepare("UPDATE members 
                             SET password = ?, salt = ?
                             WHERE userID = ?")) {
          $stmt->bind_param('ssi', $password, $random_salt, $_POST['id']);
          $stmt->execute();
          $stmt->close();
        }
        unset($random_salt);
        unset($password);
        unset($_POST['id']);
}

?>