<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
</head>
<body>
<form>
<button class="js-textareacopybtn" type="button">Copy to Clipboard</button>
<br>
<a href="upc.php?l=<?php echo $_GET['l']+1;?>">Next</a>
<a href="upc.php?l=<?php echo $_GET['l']-1;?>">Previous</a>
<?php
	if (isset($_GET['l'])) {
		echo "<textarea class='js-copytextarea'>";
		$l = $_GET['l']*100;
		$file = fopen("list.txt", "r") or die("Unable to open file");
		if ($file) {
			$i = 0;
			while (($line = fgets($file)) !== false) {
				$i++;
				if ($i>=$l && $i < $l+100) {
					echo "- " . $line;
				}
			}
			fclose($file);
		}
		echo "</textarea></form>";
	} else {
		header('Location: ?l=0');
	}

	?>

 <script type="text/Javascript">
var copyTextareaBtn = document.querySelector('.js-textareacopybtn');

copyTextareaBtn.addEventListener('click', function(event) {
  var copyTextarea = document.querySelector('.js-copytextarea');
  copyTextarea.select();

  try {
    var successful = document.execCommand('copy');
    var msg = successful ? 'successful' : 'unsuccessful';
    console.log('Copying text command was ' + msg);
  } catch (err) {
    console.log('Oops, unable to copy');
  }
});
</script>
</body>
</html>