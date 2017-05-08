<?php defined('INCLUDED') || die('<center><h1>No direct access</h1><a href="../../index.php">Go Home.</a></center>');
if (!updateLastViewed($_GET['p'], $_GET['s'], $mysqli)) {echo "updateLastViewed system is broken, please contact an administrator.";}
