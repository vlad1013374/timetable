<?php
	require '../connection/db.php';
	require 'model.php';

	$week = $_GET['week'];
	$subjects = R::getCol( 'SELECT `name` FROM subject');
	$classes = R::getCol( 'SELECT `name` FROM classes');
	$days = array('Понедельник' , 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
	$days_en = array('monday' , 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
	$time = array("8:45 - 10:20", "10:30 - 12:00", "12:30 - 14:05", "14:15 - 15:50");
	$colspan_day = count($classes)*2+3;
	$audithories = array('','142', '143', '145', '146', '147', '148', '149', '151', '152');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Admin</title>
	<link rel="stylesheet" href="includes/style.css">
	<script src="includes/js/jquery-3.6.0.js"></script>
	
	
	<!-- /*$(".but").click(function() {
		var week = $("#week").val();
		location.href = "//rasp2/admin?week=" + week;
		console.log(week);
	 });*/ -->
	
</head>
<body>
<?php

	require 'show.php';	



	
	

	/*if (isset($_POST['save'])) {
		
		save();
	}*/

?>


	<link rel="stylesheet" href="includes/js/selectize.js-master/dist/css/selectize.default.css">
	<script src="includes/js/microplugin.js"></script>
	<script src="includes/js/sifter.min.js"></script>
	<script src="includes/js/selectize.js-master/dist/js/selectize.min.js"></script>
	<script src="includes/js/main.js"></script>

	
</body>
</html>