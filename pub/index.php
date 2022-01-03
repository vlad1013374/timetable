<?php 
	require '../connection/db.php';
	require "model.php";
	$class_like = $_GET['class']."%";


	
	$is_active = $_GET['is_active'];
	$weekId = $_GET['weekId'];
	$lessons = R::getAll( 'SELECT * FROM lessons');
	if(isset($is_active)){
		$week = R::getAssocRow( 'SELECT * FROM weeks where is_active = "1"' );
		$timetable = R::getAll( 'SELECT *,s.name as sub_name FROM timetables t 
		join subjects s on t.subject_id=s.id 
		join teachers teach on t.teacher_id=teach.id 
		join weeks w on week_id = w.id
		where w.is_active = "1" ');
	}else{
		$week = R::getAssocRow( 'SELECT * FROM weeks where id = ?', [$weekId] ); // $_GET['week'];
		$timetable = R::getAll( 'SELECT *,s.name as sub_name FROM timetables t 
		join subjects s on t.subject_id=s.id 
		join teachers teach on t.teacher_id=teach.id 
		where t.week_id =? ', [$weekId]);
	}
	$week_model = new WeekEditModel($week[0], $lessons);
	//print_r($week_model);

	$config = R::getAssoc( 'SELECT param as `key`, value FROM config where section= ?', ['editor']);
	$subjects = R::getAll( 'SELECT * FROM subjects order by name');
	$classes = R::getAll( 'SELECT * FROM classes where name like ?' , [$class_like]);
	$rooms = R::getAll( 'SELECT * FROM rooms');
	
	$teachers = R::getAll( 'SELECT t.*, subject_id  FROM teachers t join teacher_subjects s on t.id=s.teacher_id order by t.name');
	


	

	
				
?>




<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Веб-страница</title>
		<link rel="stylesheet" href="includes/main.css">
		<script type="text/javascript" src="includes/jquery-3.6.0.js"></script>
		
	</head>
	<body class = "main">
		<table cellpadding="4" cellspacing="0" border="1" class="time-t">
			<col width="60">
			<?php 
				$col_size = 100;
				for($i=0; $i < count($classes); $i++) 		
				 echo '<col width="',$col_size,'">'; 			
			?>
			
			<tr>
				<td class="week-num time-col" colspan=1><?= $week_model->number; ?> уч. неделя</td>

				<?php
					foreach ($classes as  $class) {
						echo '<td class="cls" colspan=2>'.$class['name'].'</td>';
					}
				?>	
			</tr>	
			
			
		<?php
			foreach ($week_model->days as $day) {
		?>
				<tr>
					<td class = "day-border" colspan = "<?= (count($classes) + 1)*2 ?>"> <?= $day->date->format("d.m") ?> <?= $day->dayName ?> </td>
				</tr>

				<!-- <tr>
					<td class = "date" rowspan="5"><input type="text" placeholder="Дата" value="'.date('d M', strtotime($days_en[$n].' '.$week.' week')).'"></td>
				</tr> -->
				
				<?php foreach($day->lessons as $lesson) { ?>
				
				<tr>
					<td class="time time-col" rowspan="2"><?= substr($lesson->start,0,5).' - '.substr($lesson->stop,0,5) ?></td>
				
					<?php foreach($classes as $class) { ?> 

						<td colspan="2" valign="top" data-class-id="<?= $class['id'] ?>" data-lesson-id="<?= $lesson->lessonId ?>" data-day="<?= $day->date->format("Y-m-d") ?>" class="subject ">	
									
						</td>
					
					<?php } ?>
				</tr>
				<tr>
				
					<?php foreach($classes as $class) { ?> 
						<td valign="top" data-class-id="<?= $class['id'] ?>" data-lesson-id="<?= $lesson->lessonId ?>" data-day="<?= $day->date->format("Y-m-d") ?>" class="teacher">	
									
						</td>
						<td valign="top" data-class-id="<?= $class['id'] ?>" data-lesson-id="<?= $lesson->lessonId ?>" data-day="<?= $day->date->format("Y-m-d") ?>" class="classroom">	
									
						</td>
					<?php } ?>
				</tr>			
				
			<?php } ?>
		<?php } ?>

	</table>
	
	<script>
		
		var timetable = <?php echo json_encode($timetable); ?>;
		

		
	</script>
	<script type="text/javascript" src="includes/pub.js"></script>	

		
	</body>	
</html>	