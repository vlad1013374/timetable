<?php
	require '../connection/db.php';
	require 'model.php';

	$weeks = R::getAll('SELECT * from `weeks` ORDER BY `number` ASC');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin</title>
	<link rel="stylesheet"  type="text/css" href="includes/style.css">
	<script src="includes/js/jquery-3.6.0.js"></script>
	

	
</head>
<body>
<?php

	require 'show.php';	



	
	
/*
	if(isset($_POST['add-week'])){
		$week_num = $_POST['week-num'];
		$week_start = $_POST['week-start'];
		$week_stop = $_POST['week-stop'];
		addWeek($week_num, $week_start, $week_stop);
	}*/

?>


	<link rel="stylesheet" href="includes/js/selectize.js-master/dist/css/selectize.default.css">
	<script src="includes/js/microplugin.js"></script>
	<script src="includes/js/sifter.min.js"></script>
	<script src="includes/js/selectize.js-master/dist/js/selectize.min.js"></script>
	<link rel="stylesheet" href="includes/js/bootstrap.min.css">
	<script src="includes/js/bootstrap.bundle.min.js"></script>
	<script src="includes/js/main.js"></script>
</body>
</html>