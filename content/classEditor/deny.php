<?php
include_once '../../includes/db_connect.php';
  if ($stmt = $mysqli->prepare("DELETE FROM enrollments WHERE userID =? AND subjectID =?")) 
    {
      $stmt->bind_param('ii', $_POST['uID'], $_POST['sID']);
      $stmt->execute();
    }
?>