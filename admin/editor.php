﻿<?php
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
	$timetable = R::getAll( 'SELECT * FROM timetables where week_id = ?', [$weekId]);
	$links = R::getAssoc( "SELECT concat(`subject_id`,'-',`class_id`, '-',IFNULL(lesson_id,''),'-',IFNULL(week_day,'')) as 'key', `teacher_id` FROM `links`");
	$teachers = R::getAll( 'SELECT t.*, subject_id  FROM teachers t join teacher_subjects s on t.id=s.teacher_id order by t.name');
    
	

	
	?><!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Admin</title>
	<link rel="stylesheet" href="includes/kendo/kendo.common.min.css">
	<link rel="stylesheet" href="includes/kendo/kendo.custom.css">
	<link rel="stylesheet" href="includes/editor.css?t=<?= date('Ymd'); ?>">
	<script src="includes/js/jquery-3.6.0.js"></script>
	<script src="includes/kendo/kendo.all.min.js"></script>
	<script src="includes/kendo/kendo.culture.ru-RU.min.js"></script>
	<script src="includes/kendo/kendo.messages.ru-RU.min.js"></script>
	<script>kendo.culture("ru-RU");</script>
	<style>
		<?php if ($config['isActiveRowHover'] == 1) { ?>
		.tr-row:hover td{
			background: <?= $config['activeRowColor'] ?> !important;
		}
		<?php } ?>
		.row-active td {background: <?= $config['activeRowColor'] ?> !important;}
		.time {cursor:pointer;}
		.back-error input {background:red !important;}
		body{overflow-y:scroll;overflow-x:hidden;}
	</style>
	<script>
		const config = {
			weekId: <?= $weekId ?>,
			autosavePeriodInMinutes: <?= $config['autosavePeriodInMinutes'] ?>,
			flagsClass: "<?= $config['positionFlagsLeft'] == "1" ? 'flags-left':'' ?>",
			isValidateBeforeSave: <?= $config['isValidateBeforeSave'] ?>,
		}
		const flagList = {
			online: 1,	
			optional: 2,
			olimp: 4,
			advice: 8,
			ege: 16,
			extra: 32,
			
		}
	</script>
	<script src="includes/js/editor.js?t=<?= date('Ymd'); ?>"></script>

</head>
<body>
<div id="loader"><div>Загрузка данных</div></div> 
	<div>
		<div id="toolbar" style=""></div>
	</div>
	
	<script>

	</script>

	<ul style="display:none;" id="menu">
        <li data-command="add">Добавить еще предмет</li>
        <li data-command="copy:left">Скопировать слева</li>
        <li data-command="copy:right">Скопировать справа</li>
        <li data-command="comment">Добавить/Удалить комментарий</li>
        <li style="background:#3e80ed;color:white;" data-command="mark:online">Метка: Онлайн</li>
        <li style="background:#55b22d;color:white;" data-command="mark:optional">Метка: Факультатив</li>
        <li style="background:#aa46be;color:white;" data-command="mark:olimp">Метка: Олимпиада</li>
        <li style="background:#01a8c1;color:white;" data-command="mark:advice">Метка: Консультация</li>
        <li style="background:#d51923;color:white;" data-command="mark:ege">Метка: ЕГЭ</li>
        <li style="background:#ff5c1a;color:white;" data-command="mark:extra">Метка: Доп. Занятие</li>
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
					<td class = "day-border" colspan = "<?= (count($classes) + 1) ?>"> <span class="day-hide"><?= $day->date->format("d.m") ?> <?= $day->dayName ?></span> </td>
				</tr>
				
				<?php foreach($day->lessons as $lesson) { ?>
				
				<tr class="tr-row">
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
				<div style="display:none;" title="Консультация" class="flg f-advice"><?= $config['positionFlagsLeft'] == "1" ? 'Ко':'Конс' ?></div>	
				<div style="display:none;" title="Подготовка к ЕГЭ" class="flg f-ege"><?= $config['positionFlagsLeft'] == "1" ? 'ЕГ':'ЕГЭ' ?></div>	
				<div style="display:none;" title="Дополнительное занятие" class="flg f-extra"><?= $config['positionFlagsLeft'] == "1" ? 'До':'Доп' ?></div>	
			</div>			
			<div class="dl" style="margin-bottom:5px;"><input placeholder="Предмет" id="is_{no}" style="width:100%;" type="text" class="isubject"></div>
			<div style="width:90px; float:right;"><input placeholder="Каб" id="ir_{no}" style="width:90px;" type="text" class="iroom"></div>
			<div class="dl" style="margin-right:95px;"><input id="it_{no}" placeholder="Преподаватель" style="width:100%;" type="text" class="iteacher"></div>
			<div class="dl" style="margin-top:5px; display:none;"><input placeholder="Комментарий" id="ic_{no}" style="width:100%;" type="text" class="icomment k-textbox"></div>
		</div>
	</script>
	
	<script>
		let w = ($("body").width() - 60 - 9 )/ 8;
		$("col:not(:first)").attr("width", w);
		$(".hhh td:not(:first)").css("width", w+"px");
				
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
							 { type: "separator" },
							{
                                type: "splitButton",
                                text: "Расскраска",
                                menuButtons: [
									{ text: "Однотонная", click: function() {paint('none');}},
                                    { text: "По классам", click: function() {paint('class');}},                     
                                    { text: "По дням", click: function() {paint('week');}},            
                                    { text: "В шахматном порядке", click: function() {paint('chess');}},                                   
                                ]
                            },
							{ type: "separator" },
							{
                                type: "splitButton",
                                text: "Проверка",
                                menuButtons: [
									{ text: "Преподавателей", click: function() {checkTeachers();}},
                                    { text: "Аудиторий", click: function() {checkRooms();}},                                   
                                ]
                            },
							{ type: "separator" },
							{ type: "button", text: "Сохранить", click: function() {
									if(config.isValidateBeforeSave){
										let message = checkRooms(true, checkTeachers(true, ""));
										if(message && !confirm("Найдены возможные проблемы, все равно сохранить?\n\n" + message)){
											return;
										}
									}
								
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
                            
                        ]
                    });
		
		let subjects = <?php echo json_encode($subjects); ?>;
		let classes = <?php echo json_encode($classes); ?>;
		let rooms = <?php echo json_encode($rooms); ?>;
		let lessons = <?php echo json_encode($lessons); ?>;
		let teachers = <?php echo json_encode($teachers); ?>;
		let timetable = <?php echo json_encode($timetable); ?>;
		let links = <?php echo json_encode($links); ?>;
		let timetable_hash = {};
		let class_hash = {};
		
		setTimeout(function(){
			calcHash();			
			init();	
			initContextMenu();
			paint(localStorage.getItem("color_type"));	
			window.timetable_hash = JSON.stringify(timetable);
			$("#loader").hide();
		},10);
		
		$(".day-border").click(function(){
			let $el = $(this).parent();
			if($el.next().is(":visible")){
				$el.next().hide();
				$el.next().next().hide();
				$el.next().next().next().hide();
				$el.next().next().next().next().hide();
			} else {
				$el.next().show();
				$el.next().next().show();
				$el.next().next().next().show();
				$el.next().next().next().next().show();
			}
			
		});
		
		$rows = $(".tr-row");
		$(".cls").click(function(){			
			let index = $(this).index();
			if($rows.first().find('td:eq('+index+')').hasClass("shide")){
				$rows.each(function(){
					$(this).find('td:eq('+index+')').removeClass("shide");
				});
			} else {
				$rows.each(function(){
					$(this).find('td:eq('+index+')').addClass("shide");
				});
			}
			
			
		});
		
		$("td.time").click(function(){
			let tr = $(this).parent();
			console.log(tr);
			if(tr.hasClass("row-active")){
				tr.removeClass("row-active");
			} else {
				tr.addClass("row-active");
			}
		});
		
		Date.prototype.getDayOfWeek = function(){   
			return ["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"][ this.getDay() ];
		};
		
		function checkTeachers(isChane, str){
			if(!str){
				str = '';
			}
			
			$(".back-error").removeClass("back-error");
			let ret = [];
			for(let i=0; i< timetable.length; i++){
				let el = timetable[i];
				if(el['subject_id'] && !el['teacher_id']){
					console.log(el);
					let d = new Date(el['day']);
					ret.push($("#is_" + el['id']).parent().find("input").val() + " ("+ d.getDayOfWeek() + ", " + el['lesson_id'] + ' пара, ' + class_hash[el['class_id']] + ")");
					$("#it_" + el['id']).prev().addClass("back-error");
				}
			}
			
			if(ret.length > 0){
				str = str + 'Для следующих занятий не указан преподаватель:\n  ' + ret.join("\n  ") + "\n\n";
			}
			
			if(isChane){				
				return str;
			}
			
			if(ret.length > 0){
				alert(str);
			} else {
				alert("Все преподаватели заполнены корректно");
			}
		}
		
		function checkRooms(isChane, str){
			if(!str){
				str = '';
			}
			
			if(!isChane){
				$(".back-error").removeClass("back-error");
			}
			let ret = [];
			let check = {};
			for(let i=0; i< timetable.length; i++){
				let el = timetable[i];
				if(el['subject_id'] && !el['room_id'] && el['subject_id'] != '8'){
					console.log(el);
					let d = new Date(el['day']);
					ret.push($("#is_" + el['id']).parent().find("input").val() + " ("+ d.getDayOfWeek() + ", " + el['lesson_id'] + ' пара, ' + class_hash[el['class_id']] + ")");
					$("#ir_" + el['id']).prev().addClass("back-error");
				}
				
				if(el['room_id']){
					let k = el['day'] + "|" + el['lesson_id'] + "|" + el['room_id'];
					if(check.hasOwnProperty(k)){
						check[k].push(el);
					} else {
						check[k] = [el];					
					}
				}
			}
			
			if(ret.length > 0){
				str = str + 'Для следующих занятий не указана аудитория:\n  ' + ret.join("\n  ") + "\n\n";
			}
			
			let ret2 = [];
			for(let k in check){
				if(check[k].length > 1){
				let hash = check[k][0]['subject_id']+'|'+check[k][0]['teacher_id']+'|'+ check[k][0]['flags'];
					for(let i = 1; i< check[k].length; i++){
						let hash2 = check[k][i]['subject_id']+'|'+check[k][i]['teacher_id']+'|'+ check[k][i]['flags'];
						if(hash2 != hash){
							let d = new Date(check[k][0]['day']);
							ret2.push(check[k][0]['room_id'] + " ("+ d.getDayOfWeek() + ", " + check[k][0]['lesson_id'] + ' пара)');
							for(let j = 0; j< check[k].length; j++){
								$("#ir_" + check[k][j]['id']).prev().addClass("back-error");
							}
							break;
						}
					}
				}
			}
			
			if(ret2.length > 0){
				str = str + 'Для следующих аудиторий найдены дубли:\n  ' + ret2.join("\n  ") + "\n\n";
			}
			
			
			if(isChane){				
				return str;
			}
			
			if(ret.length > 0 || ret2.length > 0){
				alert(str);
			} else {
				alert("Все аудитории заполнены корректно");
			}
		}
		
	</script>
</body>
</html>