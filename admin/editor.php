<?php
	require '../connection/db.php';
	require 'model.php';
	$weekId = 4;
	$lessons = R::getAll( 'SELECT * FROM lessons');
	$week = R::getAssocRow( 'SELECT * FROM weeks where id = ?', [$weekId] ); // $_GET['week'];
	$week_model = new WeekEditModel($week[0], $lessons);
	//print_r($week_model);

	$config = R::getAssoc( 'SELECT param as `key`, value FROM config where section= ?', ['editor']);
	$subjects = R::getAll( 'SELECT * FROM subjects order by name');
	$classes = R::getAll( 'SELECT * FROM classes');
	$rooms = R::getAll( 'SELECT * FROM rooms');
	$timetable = R::getAll( 'SELECT * FROM timetables');
	$teachers = R::getAll( 'SELECT t.*, subject_id  FROM teachers t join teacher_subjects s on t.id=s.teacher_id order by t.name');
    
	

	
	?><!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Admin</title>
	<link rel="stylesheet" href="includes/kendo/kendo.common.min.css">
	<link rel="stylesheet" href="includes/kendo/kendo.custom.css">
	<link rel="stylesheet" href="includes/editor.css">
	<script src="includes/js/jquery-3.6.0.js"></script>
	<script src="includes/kendo/kendo.all.min.js"></script>
	<script src="includes/kendo/kendo.culture.ru-RU.min.js"></script>
	<script src="includes/kendo/kendo.messages.ru-RU.min.js"></script>
	<script>kendo.culture("ru-RU");</script>
	<script>
		const config = {
			weekId: <?= $weekId ?>,
			autosavePeriodInMinutes: <?= $config['autosavePeriodInMinutes'] ?>,
		}
	</script>
	<script src="includes/js/editor.js"></script>
</head>
<body>
	<ul style="display:none;" id="menu">
        <li data-command="add">Добавить еще предмет</li>
        <li data-command="copy:left">Скопировать слева</li>
        <li data-command="copy:right">Скопировать справа</li>
        <li data-command="mark:online">Метка: Онлайн</li>
        <li data-command="mark:optional">Метка: Факультатив</li>
        <li data-command="mark:olympiad">Метка: Олимпиада</li>
        <li data-command="hi">Сказать привет!</li>
	</ul>
	<span style="display:none;" id="note"></span>
	
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
						echo '<td class="cls">'.$class['name'].'</td>';
					}
				?>	
			</tr>	
			
			
		<?php
			foreach ($week_model->days as $day) {
		?>
				<tr>
					<td class = "day-border" colspan = "<?= (count($classes) + 1) ?>"> <?= $day->date->format("d.m") ?> <?= $day->dayName ?> </td>
				</tr>

				<!-- <tr>
					<td class = "date" rowspan="5"><input type="text" placeholder="Дата" value="'.date('d M', strtotime($days_en[$n].' '.$week.' week')).'"></td>
				</tr> -->
				
				<?php foreach($day->lessons as $lesson) { ?>
				
				<tr>
					<td class="time time-col"><?= substr($lesson->start,0,5).' - '.substr($lesson->stop,0,5) ?></td>
					
					<?php foreach($classes as $class) { ?> 
						<td valign="top" data-class-id="<?= $class['id'] ?>" data-lesson-id="<?= $lesson->lessonId ?>" data-day="<?= $day->date->format("Y-m-d") ?>" class="subject">							
						</td>
					
					<?php } ?>
				</tr>			
				
			<?php } ?>
		<?php } ?>

	</table>
	<script id="tpl" type="text/x-template">
		<div class="subject-block" data-item-id="{no}">
			<div style="margin-bottom:5px;"><input id="is_{no}" style="width:100%;" type="text" class="isubject"></div>
			<div style="width:90px; float:right;"><input id="ir_{no}" style="width:90px;" type="text" class="iroom"></div>
			<div style="margin-right:95px;"><input id="it_{no}" style="width:100%;" type="text" class="iteacher"></div>
		</div>
	</script>
	
	<script>
		$("col:not(:first)").attr("width", ($("body").width() - 60 - <?= count($classes)+1 ?> )/ <?= count($classes)?>);
		
		let subjects = <?php echo json_encode($subjects); ?>;
		let classes = <?php echo json_encode($classes); ?>;
		let rooms = <?php echo json_encode($rooms); ?>;
		let lessons = <?php echo json_encode($lessons); ?>;
		let teachers = <?php echo json_encode($teachers); ?>;
		let timetable = <?php echo json_encode($timetable); ?>;
		let timetable_hash = {};
		calcHash();			
		init();	
		initContextMenu();
		
		
	</script>
</body>
</html>