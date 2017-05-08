<?php
	$page = getSectionPage($_GET['id'], $mysqli);
	$subject = getSubject($page, $mysqli);
	echo $page . " | " . $subject;
?>
  <a style="position:fixed;top:75px;left:300px;z-index:5" class="hide-on-med-and-down btn-floating btn-large light-blue lighten-2 waves-effect waves-light" href="home.php?p=classEditor&s=home&t=bySub&subject=<?php echo $subject;?>">
	   <i class="large mdi-navigation-arrow-back"></i>
  </a>

  <a style="position:fixed;top:75px;left:5px;z-index:5" class="hide-on-large-only btn-floating btn-large light-blue lighten-2 waves-effect waves-light" href="home.php?p=classEditor&s=home&t=bySub&subject=<?php echo $subject;?>">
    <i class="large mdi-navigation-arrow-back"></i>
  </a>