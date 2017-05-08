<!doctype html>
<html>
<head>
    <title>Haas Hall Academy</title>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/icon?family=Material+Icons" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/css/materialize.min.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css" rel="stylesheet" />

</head>
<body>

  <nav class="light-blue lighten-1" role="navigation">
    <div class="nav-wrapper container">
      <a id="logo-container" href="#" class="brand-logo">Haas Hall Academy</a>
      <ul class="right hide-on-med-and-down">
          <li<?php if ($_GET['p'] == 'home'){echo ' class="active"';}?>>
              <a href="main.php?p=home">Home</a>
          </li>
          <li<?php if ($_GET['p'] == 'records'){echo ' class="active"';}?>>
              <a href="main.php?p=records">Records</a>
          </li>
          <li<?php if ($_GET['p'] == 'allrecords'){echo ' class="active"';}?>>
              <a href="main.php?p=allrecords">All Records</a>
          </li>
      </ul>

      <ul id="nav-mobile" class="side-nav">
          <li<?php if ($_GET['p'] == 'home'){echo ' class="active"';}?>>
              <a href="main.php?p=home">Home</a>
          </li>
          <li<?php if ($_GET['p'] == 'records'){echo ' class="active"';}?>>
              <a href="main.php?p=records">Records</a>
          </li>
          <li<?php if ($_GET['p'] == 'allrecords'){echo ' class="active"';}?>>
              <a href="main.php?p=allrecords">All Records</a>
          </li>

      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>
  <div class="container">
  	<br>
  <?php
  	include_once 'content/'.$_GET['p'].'.php';
  ?>
  </div>
  <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.1/js/materialize.min.js"></script>
    <script type="text/Javascript">
        $(function() {
        	$(".button-collapse").sideNav();
    	});
    </script>
</body>
</html>