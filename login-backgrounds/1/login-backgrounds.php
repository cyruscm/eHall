<?php
include_once 'http://107.167.80.4/includes/db_connect.php';
include_once 'http://107.167.80.4/includes/functions.php';
 
sec_session_start();
 
if (login_check($mysqli) == true) {
    header( 'Location: home.php?p=home' ) ;
}
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
    <head>
	<?php include_once 'http://107.167.80.4/favi/includes.php'; ?>
        <title>eHall Login : Haas Hall Academy</title>
		<link rel="stylesheet" type="text/css" href="http://107.167.80.4/css/login.css">
		<link rel="stylesheet" type="text/css" href="http://107.167.80.4/css/notification.css">
        <script type="text/JavaScript" src="http://107.167.80.4/js/sha512.js"></script> 
        <script type="text/JavaScript" src="http://107.167.80.4/js/forms.js"></script> 
		<script type="text/JavaScript" src="http://107.167.80.4/js/login.js"></script> 
    </head>
    <body>
        
	<div class="wrapper">
		<div class="container">
		<h1>eHall Scholar Login</h1>
		
		<form class="form" action="includes/process_login.php" method="post" name="login_form">
			<input type="text" placeholder="email@address.com" name="email" />
			<input type="password" placeholder="Password" name="password" id="password" />
			<button type="submit" value="Login" id="login-button" onclick="formhash(this.form, this.form.password);" />Login</button>
			<br>Don't have an account? <a href='./register.php'>Register</a>
		</form>
		<center>
		<?php
				$err = htmlspecialchars($_GET["err"]);

        if ($err==1)
		{
			echo '<div class="error message">';
			echo '	<h3><b>Wrong Password</b></h3>';
			echo '	<p>This attempt has been logged, you have x more tries until this account is locked.</p>';
			echo '</div>';
		}
		elseif ($err==2) {
			echo '<div class="error message">';
			echo '	<h3><b>Account is locked</b></h3>';
			echo '	<p>You have used an incorrect password too many times. This will reset in 2 hours.</p>';
			echo '</div>';
		}
		elseif ($err==5) {
			echo '<div class="error message">';
			echo '	<h3><b>Invalid Email</b></h3>';
			echo '	<p>No account is linked to this Email Address, if you believe this is a mistake, please contact an Administrator.</p>';
			echo '</div>';
		}
		elseif ($err==4 or $err==6){
			echo '<div class="error message">';
			echo '	<h3><b>Something went horribly wrong</b></h3>';
			echo '	<p>Please contact an administrator.</p>';
			echo '</div>';
		}
		elseif ($err==403){
			echo '<div class="error message">';
			echo '	<h3><b>Unauthorized</b></h3>';
			echo '	<p>You attempted to access a page without logging in.</p>';
			echo '</div>';
		}
		elseif ($err=="register"){
			echo '<div class="success message">';
			echo '	<h3><b>Success!</b></h3>';
			echo '	<p>You have registered</p>';
			echo '</div>';
		}
    ?></center>
	</div>
	
		<ul class="bg-bubbles">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>

	</ul>

</div>

 

    </body>
</html>