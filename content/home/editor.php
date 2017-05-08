<?php
if (getRole($mysqli)>=1){
  if (isset($_GET["t"])){
    include_once 'content/editor/'.$_GET["t"].'.php';
  }
}
else {
  echo "<center><h1 class='red-text'>UNAUTHORIZED ACCESS</h1>";
  echo "<div class='divider'></div>";
  echo "<p>Come on ".htmlentities($_SESSION['fName']).", you know you shouldn't try to get to this page. This attempt has been logged and reported to site admins.</p></center>";
}
?>