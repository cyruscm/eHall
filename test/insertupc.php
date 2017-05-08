<?php
if (isset($_POST['data'])) {
	$file = fopen("list.txt", "w") or die("Unable to open file");
	fwrite($file, $_POST['data']);
	fclose($file);
	echo $_POST['data'];
	//header('Location: upc.php');

} 
?>
<form method="POST" action="#">
<textarea id="data" name="data"></textarea>
<button type="submit">Submit</button>
</form>