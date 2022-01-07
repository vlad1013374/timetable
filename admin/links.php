<?php
	require '../connection/db.php';
	require 'model.php';
	$links = R::getAll('SELECT *,t.name as t_name, s.name as s_name, c.name as c_name from links l
		join teachers t on t.id = teacher_id
		join subjects s on s.id = subject_id
		join classes c on c.id = class_id
		');

	$teachers = R::getAll('SELECT * FROM teachers');
	$subjects = R::getAll('SELECT * FROM subjects');
	$classes = R::getAll('SELECT * FROM classes');
	$lessons = R::getAll('SELECT * FROM lessons');

	const DAYS =  array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота");

	
	if (isset($_POST['add'])) {
		$datas = array($_POST['teacher'], $_POST['subject'], $_POST['class'], $_POST['lesson'], $_POST['day']);
		R::exec('INSERT INTO links (`teacher_id`,`subject_id`,`class_id`,`lesson_id`,`week_day`) values(? , ?, ?, ?, ?)
			', $datas);
		header("Location: links.php"); die();
	}
?>







<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	
	<link rel="stylesheet" href="includes/js/bootstrap.min.css">
	<script src="includes/js/bootstrap.bundle.min.js"></script>

</head>
<body>

	<div style="float:right;">
		<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
		  Добавить
		</button>
	</div>

	<?php foreach ($links as $link): ?>
		<div>
			<div class="teacher"><?=$link['t_name']?></div>
			<div class="subject"><?=$link['s_name']?></div>
			<div class="class"><?=$link['c_name']?></div>
			<div class="day"><?=DAYS[$link['week_day'] - 1]?></div>
		</div>
	<?php endforeach ?>
	


	<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
	  <div class="offcanvas-header">
	    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Добавление связи</h5>
	    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	  </div>
	  <div class="offcanvas-body">
	   
	    <div class="dropdown mt-3">
	    <form method="post">
	     <div>
	     	Пеподаватель: 
	     	<select name="teacher">
	     	<?php foreach ($teachers as $teacher): ?>
	     		
	     			<option value="<?=$teacher['id']?>"><?=$teacher['name']?></option>
	     		
	     	<?php endforeach ?>
	     	</select>
	     </div>
	     <div>
	     	Предмет: 
	     	<select name="subject">
	     	<?php foreach ($subjects as $subject): ?>
	     		
	     			<option value="<?=$subject['id']?>"><?=$subject['name']?></option>
	     		
	     	<?php endforeach ?>
	     	</select>
	     </div>
	     <div>
	     	Класс: 
	     	<select name="class">
	     	<?php foreach ($classes as $class): ?>
	     		
	     			<option value="<?=$class['id']?>"><?=$class['name']?></option>
	     		
	     	<?php endforeach ?>
	     	</select>
	     </div>
	     <div>
	     	Номер пары: 
	     	<select name="lesson">
	     	<option></option>
	     	<?php foreach ($lessons as $lesson): ?>
	     		
	     			<option value="<?=$lesson['id']?>"><?=$lesson['name']?></option>
	     		
	     	<?php endforeach ?>
	     	</select>
	     </div>
	     <div>
	     	День: 
	     	<select name="day">
	     	<option></option>
	     	<?php $n = 1;?>
	     	<?php foreach (DAYS as $day): ?>
	     			
	     			<option value="<?=$n?>"><?=$day?></option>
	     			<?php $n = $n+1;?>
	     	<?php endforeach ?>
	     	</select>
	     </div>
	     
	     <input type="submit" name="add" value="Добавить">
	    </form>
	    </div>
	  </div>
	</div>
</body>
</html>

