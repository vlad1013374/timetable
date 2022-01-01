<?php
	require '../connection/db.php';
	require 'model.php';
	$weekId = $_GET['weekId'];
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
			flagsClass: "<?= $config['positionFlagsLeft'] == "1" ? 'flags-left':'' ?>",
			
		}
		const flagList = {
			online: 1,	
			optional: 2,
			olimp: 4,
			
		}
	</script>
	<script src="includes/js/editor.js"></script>

</head>
<body>
	<div>
		<div id="toolbar" style=""></div>
	</div>
	
	<script>

	</script>

	<ul style="display:none;" id="menu">
        <li data-command="add">Добавить еще предмет</li>
        <li data-command="copy:left">Скопировать слева</li>
        <li data-command="copy:right">Скопировать справа</li>
        <li style="background:#3e80ed;color:white;" data-command="mark:online">Метка: Онлайн</li>
        <li style="background:#55b22d;color:white;" data-command="mark:optional">Метка: Факультатив</li>
        <li style="background:#aa46be;color:white;" data-command="mark:olimp">Метка: Олимпиада</li>
        <li data-command="delete">Удалить</li>
	</ul>
	<ul style="display:none;" id="menutd">
        <li data-command="add">Добавить еще предмет</li>
        <li data-command="copy:left">Скопировать слева</li>
        <li data-command="copy:right">Скопировать справа</li>
	</ul>
	<span style="display:none;" id="note"></span>
	
	
	<table cellpadding="4" cellspacing="0" border="1" class="time-t">
			<col width="60">
			<?php 
				$col_size = 100;
				for($i=0; $i < count($classes); $i++) 		
				 echo '<col width="',$col_size,'">'; 			
			?>
			
			<tr class="hhh">
				<td width="59" class="week-num time-col" colspan=1><?= $week_model->number; ?> уч. неделя</td>

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
					
			<div style="display:none;" class="flags-block">
				<div style="display:none;" title="Online" class="flg f-online"><?= $config['positionFlagsLeft'] == "1" ? 'On':'Online' ?></div>
				<div style="display:none;" title="Факультатив" class="flg f-optional"><?= $config['positionFlagsLeft'] == "1" ? 'Фа':'Факульт' ?></div>
				<div style="display:none;" title="Олимпиада" class="flg f-olimp"><?= $config['positionFlagsLeft'] == "1" ? 'Ол':'Олимп' ?></div>	
			</div>			
			<div class="dl" style="margin-bottom:5px;"><input id="is_{no}" style="width:100%;" type="text" class="isubject"></div>
			<div style="width:90px; float:right;"><input id="ir_{no}" style="width:90px;" type="text" class="iroom"></div>
			<div class="dl" style="margin-right:95px;"><input id="it_{no}" style="width:100%;" type="text" class="iteacher"></div>
		</div>
	</script>
	
	<script>
		let w = ($("body").width() - 60 - 9 )/ 8;
		$("col:not(:first)").attr("width", w);
		$(".hhh td:not(:first)").css("width", w+"px");
		
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
		
		$("#toolbar").kendoToolBar({
                        items: [
                            /*{ type: "button", text: "Button" },
                            { type: "button", text: "Toggle Button", togglable: true },*/
                            {
                                type: "splitButton",
                                text: "Очистить",
                                menuButtons: [
                                    { text: "Все"/*, icon: "insert-up"*/ },
                                    { text: "Понедельник"/*, icon: "insert-up"*/ },
                                    { text: "Вторник"/*, icon: "insert-up"*/ },
                                    { text: "Среду"/*, icon: "insert-up"*/ },
                                    { text: "Четверг"/*, icon: "insert-up"*/ },
                                    { text: "Пятницу"/*, icon: "insert-up"*/ },
                                    { text: "Субботу"/*, icon: "insert-up"*/ },
                                    { text: "10-е классы"/*, icon: "insert-up"*/ },
                                    { text: "11-е классы"/*, icon: "insert-up"*/ },
                                    { text: "10A"/*, icon: "insert-up"*/ },
                                    { text: "10Б"/*, icon: "insert-up"*/ },
                                    { text: "11А"/*, icon: "insert-up"*/ },
                                    { text: "11Б"/*, icon: "insert-up"*/ },
                                    { text: "11В"/*, icon: "insert-up"*/ }
                                ]
                            },
							 { type: "separator" },
							{
                                type: "splitButton",
                                text: "Добавить метку <span style='color:#3e80ed;'>&nbsp;Online</span>",
                                menuButtons: [
                                    { text: "Для всех"/*, icon: "insert-up"*/ },
                                    { text: "Понедельнику"/*, icon: "insert-up"*/ },
                                    { text: "Вторнику"/*, icon: "insert-up"*/ },
                                    { text: "Среде"/*, icon: "insert-up"*/ },
                                    { text: "Четвергу"/*, icon: "insert-up"*/ },
                                    { text: "Пятнице"/*, icon: "insert-up"*/ },
                                    { text: "Субботе"/*, icon: "insert-up"*/ },
                                    { text: "10-ым классам"/*, icon: "insert-up"*/ },
                                    { text: "11-ым классам"/*, icon: "insert-up"*/ },
                                    { text: "10A"/*, icon: "insert-up"*/ },
                                    { text: "10Б"/*, icon: "insert-up"*/ },
                                    { text: "11А"/*, icon: "insert-up"*/ },
                                    { text: "11Б"/*, icon: "insert-up"*/ },
                                    { text: "11В"/*, icon: "insert-up"*/ }
                                ]
                            },
							{ type: "button", text: "Сохранить", click: function() {
									var dt = JSON.stringify(timetable);
									$.post( "editor-save.php", {data: dt})
									  .done(function(ev, a, b) {
										var r = JSON.parse(ev);
										note.show({message: r.message}, r.status);
										
									  })
									  .fail(function() {
										alert( "error" );
									  })
								} 
							}
                            /*{ type: "separator" },
                            { template: "<label for='dropdown'>Format:</label>" },
                            {
                                template: "<input id='dropdown' style='width: 150px;' />",
                                overflow: "never"
                            },
                            { type: "separator" },
                            {
                                type: "buttonGroup",
                                buttons: [
                                    { icon: "align-left", text: "Left", togglable: true, group: "text-align" },
                                    { icon: "align-center", text: "Center", togglable: true, group: "text-align" },
                                    { icon: "align-right", text: "Right", togglable: true, group: "text-align" }
                                ]
                            },
                            {
                                type: "buttonGroup",
                                buttons: [
                                    { icon: "bold", text: "Bold", togglable: true },
                                    { icon: "italic", text: "Italic", togglable: true },
                                    { icon: "underline", text: "Underline", togglable: true }
                                ]
                            },
                            {
                                type: "button",
                                text: "Action",
                                overflow: "always"
                            },
                            {
                                type: "button",
                                text: "Another Action",
                                overflow: "always"
                            },
                            {
                                type: "button",
                                text: "Something else here",
                                overflow: "always"
                            }*/
                        ]
                    });
		
		
	</script>
</body>
</html>