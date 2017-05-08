<li class="truncate<?php if($P == "home"){echo ' active';} ?>"><a href="home.php?p=home">Home</a></li>
<li class="divider"></li>
<?php 
$classArray = getClasses($_SESSION['user_id'], $mysqli);
for ($i = 0; $i < count($classArray); $i=$i+2 ){
  $page = getLastViewed($classArray[$i], $mysqli);
  if ($page == false){
    echo '<li class="truncate"><a href="home.php?p=-1&s=home">'.$classArray[$i+1].'</a></li>';
  }
  else{
    echo '<li class="truncate"><a href="home.php?p='.$page.'&s='.$classArray[$i].'">'.$classArray[$i+1].'</a></li>';
  }
}
?>
