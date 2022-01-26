<?php 
	require '../connection/db.php';
	require "model.php";
	$class_like = (array_key_exists('class', $_GET) ? $_GET['class'] : '10')."%";


	
	$is_active = array_key_exists('is_active', $_GET) ? $_GET['is_active'] : null;
	$weekId = array_key_exists('weekId', $_GET) ? $_GET['weekId'] : null;
	$lessons = R::getAll( 'SELECT * FROM lessons');
	$classes = R::getAll( 'SELECT * FROM classes where name like ?' , [$class_like]);
	if(isset($is_active)){
		$week = R::getAssocRow( 'SELECT * FROM weeks where is_active = "1"' );
		$timetable = R::getAll( 'SELECT t.*,s.name as sname, s.short_name as sname2, teach.name as tname, room_id as rname FROM timetables t 
		join subjects s on t.subject_id=s.id 
		left join teachers teach on t.teacher_id=teach.id 
		join weeks w on week_id = w.id
		where w.is_active = "1" order by sname');
	}else{
		$week = R::getAssocRow( 'SELECT * FROM weeks where id = ?', [$weekId] ); // $_GET['week'];
		$timetable = R::getAll( 'SELECT t.*,s.name as sname, s.short_name as sname2, teach.name as tname, room_id as rname FROM timetables t 
		join subjects s on t.subject_id=s.id 
		left join teachers teach on t.teacher_id=teach.id 
		where t.week_id =? order by sname', [$weekId]);
	}
	
	$week_model = new WeekEditModel($week[0], $lessons);
	
	class ViewItem {
		public $items = array();
		
		public function getSubjects($isIncludeFlags = false){
			$str = "";
			$delimeter = '';
			foreach($this->items as $v){
				$str .= $delimeter.$v['sname2'];
				
				if($isIncludeFlags){
					$text = getFlagsString($v['flags']);
					if($text != ''){
						$str .= '<span class="span-inline-flags"> ('.$text.')</span>';
					}
				}
				
				$delimeter = ' / ';
			}
			
			return $str;
		}
		
		public function getTeachers(){
			$str = "";
			$delimeter = '';
			foreach($this->items as $v){
				$str .= $delimeter.(empty($v['rname']) ? '' : ($v['rname'].', ')).$v['tname'];
				$delimeter = ' / ';
			}
			
			return $str;
		}
		
		public function isFlagsEqual(){
			for($i=1; $i< count($this->items); $i++){
				if($this->items[0]['flags'] != $this->items[$i]['flags'])
					return false;
			}
			
			return true;
		}
		
	}
	
	$tt = array();

	foreach($week_model->days as $d) {
		$day = $d->date->format("Y-m-d");
		foreach($lessons as $l) {
			$lid = $l['id'];
			foreach($classes as $c) {
				$cid = $c['id'];
				if(!array_key_exists($day, $tt)){
					$tt[$day] = array();
				}
				
				if(!array_key_exists($lid, $tt[$day])){
					$tt[$day][$lid] = array();
				}
				
				if(!array_key_exists($cid, $tt[$day][$lid])){
					$tt[$day][$lid][$cid] = new ViewItem();
				}
			}
		}
	}
		
	foreach($timetable as $t) {		
		if(array_key_exists($t['class_id'],$tt[$t['day']][$t['lesson_id']]))
			array_push($tt[$t['day']][$t['lesson_id']][$t['class_id']]->items, $t);
	}

 	
	
	//print_r($tt);

	$config = R::getAssoc( 'SELECT param as `key`, value FROM config where section= ?', ['editor']);
	$subjects = R::getAll( 'SELECT * FROM subjects order by name');
	
	$rooms = R::getAll( 'SELECT * FROM rooms');
	
	//$teachers = R::getAll( 'SELECT t.*, subject_id  FROM teachers t join teacher_subjects s on t.id=s.teacher_id order by t.name');
	$teachers = R::getAll( 'SELECT * FROM teachers order by name');
	
	$class_width =  100 / count($classes);
	$subj_width =  $class_width / 2;

	define("FL_ONLINE",1);
	define("FL_OPTIONAL",2);
	define("FL_OLIMP",4);
	define("FL_ADVICE",8);
	define("FL_EGE",16);
	define("FL_EXTRA",32);
	function getFlagsString($flags){
		$f = intval($flags);
		if($f === 0)
			return '';
			
		$ret = array();
		//print($flags); die();

		if(($f & FL_ONLINE) === FL_ONLINE)
			array_push($ret, '<span class="flag-color-'.FL_ONLINE.'">online</span>');			
		if(($f & FL_OPTIONAL) === FL_OPTIONAL)
			array_push($ret,'<span class="flag-color-'.FL_OPTIONAL.'">ф-в</span>');	
		if(($f & FL_OLIMP) === FL_OLIMP)
			array_push($ret,'<span class="flag-color-'.FL_OLIMP.'">олимп</span>');	
		if(($f & FL_ADVICE) === FL_ADVICE)
			array_push($ret,'<span class="flag-color-'.FL_ADVICE.'">конс</span>');	
		if(($f & FL_EGE) === FL_EGE)
			array_push($ret,'<span class="flag-color-'.FL_EGE.'">егэ</span>');	
		if(($f & FL_EXTRA) === FL_EXTRA)
			array_push($ret, '<span class="flag-color-'.FL_EXTRA.'">доп</span>');
		return implode(', ', $ret);
	}		
	
	function getFlagsClasses($flags){
		$f = intval($flags);
		if($f === 0)
			return '';
			
		$ret = array();

		if(($f & FL_ONLINE) === FL_ONLINE)
			array_push($ret, 'flag-color-'.FL_ONLINE);			
		if(($f & FL_OPTIONAL) === FL_OPTIONAL)
			array_push($ret,'flag-color-'.FL_OPTIONAL);	
		if(($f & FL_OLIMP) === FL_OLIMP)
			array_push($ret,'flag-color-'.FL_OLIMP);	
		if(($f & FL_ADVICE) === FL_ADVICE)
			array_push($ret,'flag-color-'.FL_ADVICE);	
		if(($f & FL_EGE) === FL_EGE)
			array_push($ret,'flag-color-'.FL_EGE);	
		if(($f & FL_EXTRA) === FL_EXTRA)
			array_push($ret, 'flag-color-'.FL_EXTRA);
		return implode(' ', $ret);
	}		
?>




<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Веб-страница</title>
		<link rel="stylesheet" href="includes/kendo/kendo.common.min.css">
		<link rel="stylesheet" href="includes/kendo/kendo.custom.css">
		<link rel="stylesheet" href="includes/main.css">
		<script type="text/javascript" src="includes/jquery-3.6.0.js"></script>
		<script src="includes/kendo/kendo.all.min.js"></script>
		<script src="includes/kendo/kendo.culture.ru-RU.min.js"></script>
		<script src="includes/kendo/kendo.messages.ru-RU.min.js"></script>
		<script>kendo.culture("ru-RU");</script>
		<style>
		    * {
				margin:0;
				padding:0;
				font-family:Calibri;
			}
			
			.day-td{overflow:hidden;font-size:14px;	}
			
			.day-col{
				transform: rotate(-90deg);				
				float:left;
				width:15px;
				height:10px;				
			}
			
			.day-col-0 {margin-top:60px;}
			.day-col-1 {margin-top:25px;}
			.day-col-2 {margin-top:15px;}
			.day-col-3 {margin-top:25px;}
			.day-col-4 {margin-top:25px;}
			.day-col-5 {margin-top:30px;}
			
			table {
				table-layout: fixed;
				border-collapse:collapse;
			}
			
			table td { overflow: hidden; }
			
			.table-main {
				width:840px;
				table-layout: fixed;
				border-collapse:collapse;
			}				
		   
		   .table-header tr td {border-left:1px solid black;  padding:5px 0 5px 5px;   color:white;   }
		   
		   .table-header tr td:first-child {border:0; }
		   
		   .td-lesson-time {font-size:10px; padding-left:4px;}
		   
		   .table-subj tr td {
			   border-left:1px solid black;
			   position:relative;
		   }
		   .table-subj tr td:first-child {border-left:0;}
		   .table-subj tr:first-child td {border-bottom:1px solid black;}
		   
		   .tr-subj { font-size:20px; font-weight:bold;  }
		   
		   .tr-teach {   font-size:12px;   }
		   
		   .tr-subj td{   padding:7px 0 0 5px;  }
		   
		   .tr-teach td {   padding: 1px 0 0 5px;   }
		   
		   .uch-ned {  font-size:12px; color:white;  padding-left:5px;   }
		   
		   .table-main > tbody > tr:nth-child(2n+4){background-color:white;}
		   .table-main > tbody > tr:nth-child(2n+3)
		   {
			   background-color:rgb(235,235,235);
		   }
		   
		   .span-inline-flags {font-size:10px;}
		   
		   .span-flags {
			   font-size:10px;
			   position:absolute;
			   top:0;
			   right:3px;
		   }
		   
		   .day-border {
			   /*background-color:#0072c6;*/
			   /*background-color:rgb(25, 132, 200);*/			   
			   background-color:#3b5998;			   
			   padding:5px;
			   color:white;
				border:1px solid black;
		   }
		   
		   .day-space {
			   border-left:1px solid white;
			   border-right:1px solid white;
			   font-size:10px;
			   background:white;
		   }
		   
		   .tr-head {
			   /*background-color:rgb(14,177,161);*/
			   /*background-color:rgb(25, 132, 200);*/
			   background-color:#3b5998;
		   }
		   
		   .tr-head > td {
			   border:1px solid black;
		   }
		   
		   span.flag-color-1 {color:black;}
		   span.flag-color-2 {color:green/*#3a7a1f*/;}
		   span.flag-color-4 {color:purple/*#762e84*/;}
		   span.flag-color-8 {color:#016e7f;}
		   span.flag-color-16 {color:#d51923;}
		   span.flag-color-32 {color:#ff5c1a;}
		   
		   td.flag-color-1 {background-color:rgba(3, 169, 244,0.5);}
		   td.flag-color-2 {color:green;}
		   td.flag-color-4 {color:purple;}
		   td.flag-color-8 {color:#016e7f;}
		   td.flag-color-16 {color:#d51923;}
		   td.flag-color-32 {color:#ff5c1a;}
		   
		   .div-filter {
			   width:840px; margin-bottom:15px;
		   }
		   
		   .div-filter > .lbl {
			   display:inline-block;
			   position:relative;
			   top:2px;
			   margin-right:5px;
			   margin-left:5px;
		   }
		   
		   .cmt {
			   font-size:14px;
			   font-weight:normal;
		   }
		
		</style>
	</head>
	<body class = "main">
	<div align="right" class="div-filter">
	<span class="lbl" style="float:left; position:relative; top:7px; left:0px;"><?= $week_model->number; ?> учебная неделя</span>
	<span class="lbl">Фильтровать по</span>
	<input type="text" placeholder="классу" id="bySubject"> <span class="lbl">или по</span>
	<input type="text" placeholder="преподавателю" id="byTeacher">
	</div>
		<table class="table-main" border=1>
			<!--<col width="30px">	-->		
			<col width="35px">			
			<tr class="tr-head">
				<td class="uch-ned" align=center colspan=1>&nbsp;</td>
				
				<td>
					<table width="100%" class="table-header">
						<col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>">
						<col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>">
						<tr>
							<?php
								foreach ($classes as  $class) {
									echo '<td colspan=2 class="">'.$class['name'].'</td>';
								}
							?>	
						</tr>
					</table>
				</td>
				
			</tr>	
			
			
		<?php
			foreach ($week_model->days as $dnum =>$day) { 
		?>
				<tr>
					<td class = "day-space" align=center colspan = "2">&nbsp;</td>
				</tr>
				<tr>
					<td class = "day-border" align=center colspan = "2"> <?= $day->date->format("d.m") ?> <?= $day->dayName ?> </td>					
				</tr>
				
				
				<?php foreach($day->lessons as $i=>$lesson) { ?>
				
				<tr>
					<!--<?php if($i == 0){ ?> <td class="day-td" align=center rowspan="4"><div class="day-col day-col-<?=$dnum?>"><?= $day->dayName ?></div></td><?php } ?>-->
					<td class="td-lesson-time"><?= substr($lesson->start,0,5).'-<br>'.substr($lesson->stop,0,5) ?></td>
					<td>
						<table class="table-subj" width="100%">
							<col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>">
							<col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>">
							<?php
							$tr1 = '';
							$tr2 = '';
							$d = $day->date->format("Y-m-d");
							foreach($classes as $ind => $class) { 
								$c = count($tt[$d][$lesson->lessonId][$class['id']]->items);
								if($c == 1){									
									$cur_subj = $tt[$d][$lesson->lessonId][$class['id']]->getSubjects();
									$cur_subj_x = $tt[$d][$lesson->lessonId][$class['id']]->getSubjects(true);
									$cur_teach = $tt[$d][$lesson->lessonId][$class['id']]->getTeachers();
									$cur_class =  $class['id'];
									
									if($ind > 0 &&
										$cur_subj_x == $tt[$d][$lesson->lessonId][$classes[$ind-1]['id']]->getSubjects(true) &&
										$cur_teach == $tt[$d][$lesson->lessonId][$classes[$ind-1]['id']]->getTeachers()){
										continue;
									}
									
									$colspan = 2;
									$align = '';
									for($i= $ind+1; $i < count($classes); $i++){
										if($cur_subj_x == $tt[$d][$lesson->lessonId][$classes[$i]['id']]->getSubjects(true) &&
											$cur_teach == $tt[$d][$lesson->lessonId][$classes[$i]['id']]->getTeachers()){
											$colspan +=2;
											$cur_class .= ':'.$classes[$i]['id'];
											$align = ' align=center';
										} else {
											break;
										}
									}									
									 
									$tmp = $tt[$d][$lesson->lessonId][$class['id']]->items[0];
									$cm = empty($tmp['comment']) ? '' : "<div class='cmt'>(".$tmp['comment'].")</div>";
									$flag_text = getFlagsString($tmp['flags']);
									$flags_cl = getFlagsClasses($tmp['flags']);
									$tr1 .= '<td'.$align.' data-cl="'.$cur_class.'" data-teach="'.$tmp['tname'].'" class="'.$flags_cl.'" valign=top colspan='.$colspan.'>'.$tmp['sname'].($cm).($flag_text!='' ? '<span class="span-flags">'.$flag_text.'</span>':'').'</td>';
									$tr2 .= '<td'.$align.' data-cl="'.$cur_class.'" data-teach="'.$tmp['tname'].'" class="'.$flags_cl.'" valign=top colspan='.$colspan.'>'.($tmp['rname'] ? $tmp['rname'].', ' : '').$tmp['tname'].'</td>';
								} else if($c == 0){
									$tr1 .= '<td valign=top colspan=2>&nbsp;</td>';
									$tr2 .= '<td valign=top colspan=2>&nbsp;</td>';
								} else if($c == 2){
									$tmp1 = $tt[$d][$lesson->lessonId][$class['id']]->items[0];
									$tmp2 = $tt[$d][$lesson->lessonId][$class['id']]->items[1];
									
									$flags_cl1 = getFlagsClasses($tmp1['flags']);
									$flags_cl2 = getFlagsClasses($tmp2['flags']);
									
									if(($tmp1['sname'] == $tmp2['sname']) && ($tmp1['flags'] == $tmp2['flags'])){
										$flag_text = getFlagsString($tmp1['flags']);
										$tr1 .= '<td data-cl="'.$class['id'].'" data-teach="'.$tmp1['tname'].':'.$tmp2['tname'].'" class="'.$flags_cl1.'" valign=top colspan=2>'.$tmp1['sname'].($flag_text!='' ? '<span class="span-flags">'.$flag_text.'</span>':'').'</td>';
									} else {
										$flag_text1 = getFlagsString($tmp1['flags']);
										$flag_text2 = getFlagsString($tmp2['flags']);
										$tr1 .= '<td data-cl="'.$class['id'].'" data-teach="'.$tmp1['tname'].'" class="'.$flags_cl1.'" valign=top>'.$tmp1['sname2'].($flag_text1!='' ? '<span class="span-flags">'.$flag_text1.'</span>':'').'</td><td data-cl="'.$class['id'].'" data-teach="'.$tmp2['tname'].'" class="'.$flags_cl2.'" valign=top>'.$tmp2['sname2'].($flag_text2!='' ? '<span class="span-flags">'.$flag_text2.'</span>':'').'</td>';
									}
									
									$tr2 .= '<td data-cl="'.$class['id'].'" data-teach="'.$tmp1['tname'].'" class="'.$flags_cl1.'" valign=top>'.($tmp1['rname'] ? $tmp1['rname'].', ' : '').$tmp1['tname'].'</td><td data-cl="'.$class['id'].'" data-teach="'.$tmp2['tname'].'" valign=top class="'.$flags_cl2.'">'.($tmp2['rname'] ? $tmp2['rname'].', ' : '').$tmp2['tname'].'</td>';
								} else if($c >= 3){
									$isFlagsEqual = $tt[$d][$lesson->lessonId][$class['id']]->isFlagsEqual();
									//print_r($tt[$d][$lesson->lessonId][$class['id']]);
									$cur_subj = $tt[$d][$lesson->lessonId][$class['id']]->getSubjects(!$isFlagsEqual);
									$cur_subj_x = $tt[$d][$lesson->lessonId][$class['id']]->getSubjects(true);
									$cur_teach = $tt[$d][$lesson->lessonId][$class['id']]->getTeachers();
									$cur_class =  $class['id'];
									
									if($ind > 0 &&
										$cur_subj_x == $tt[$d][$lesson->lessonId][$classes[$ind-1]['id']]->getSubjects(true) &&
										$cur_teach == $tt[$d][$lesson->lessonId][$classes[$ind-1]['id']]->getTeachers()){
										continue;
									}

									$colspan = 2;
									for($i= $ind+1; $i < count($classes); $i++){
										if($cur_subj_x == $tt[$d][$lesson->lessonId][$classes[$i]['id']]->getSubjects(true) &&
											$cur_teach == $tt[$d][$lesson->lessonId][$classes[$i]['id']]->getTeachers()){
											$cur_class .= ':'.$classes[$i]['id'];
											$colspan +=2;
										}
									}
									
									$flags_text ='';
									$flags_cl ='';
									if($isFlagsEqual){
										$ftxt = getFlagsString($tt[$d][$lesson->lessonId][$class['id']]->items[0]['flags']);
										$flags_cl = getFlagsClasses($tt[$d][$lesson->lessonId][$class['id']]->items[0]['flags']);
										if($ftxt != '')
											$flags_text = '<span class="span-flags">'.$ftxt.'</span>';
									}
									
									$tr1 .= '<td data-cl="'.$cur_class.'" data-teach="'.$cur_teach.'" class="'.$flags_cl.'" align=center valign=top colspan='.$colspan.'>'.$cur_subj.$flags_text.'</td>';
									$tr2 .= '<td data-cl="'.$cur_class.'" data-teach="'.$cur_teach.'" class="'.$flags_cl.'" align=center valign=top colspan='.$colspan.'>'.$cur_teach.'</td>';
								}
														
							 } ?>
						<tr class="tr-subj"><?= $tr1 ?></tr>
						<tr class="tr-teach"><?= $tr2 ?></tr>
						
						</table>
					</td>
			
				</tr>	
				
			<?php } ?>
		<?php } ?>

	</table>
	
	<script>
		
		var classes = <?php echo json_encode($classes); ?>;
		var teachers = <?php echo json_encode($teachers); ?>;
		$cells = $("td[data-teach]");
		$cells2 = $("td[data-cl]");
		
		$("#bySubject").kendoComboBox({
			dataSource:classes,
			dataTextField:'name',
			dataValueField:'id',
			noDataTemplate: 'Ничего не нашлось',
			filter:"startswith",
			change: function(e){
				$("#byTeacher").data("kendoComboBox").value('');
				let v = e.sender.value();
				console.log(v);
				if(!v){
					$cells2.css("visibility", "visible");
				} else {
					$cells2.each(function(){
						if(this.dataset.cl.indexOf(v) > -1){
							this.style.visibility = "visible";
						} else {
							this.style.visibility = "hidden";
						}
					})
				}
				
			}
		});
		
		$("#byTeacher").kendoComboBox({
			dataSource:teachers,
			dataTextField:'name',
			dataValueField:'name',
			filter:"startswith",
			noDataTemplate: 'Ничего не нашлось',
			change: function(e){
				$("#bySubject").data("kendoComboBox").value('');
				let v = e.sender.value();
				if(!v){
					$cells.css("visibility", "visible");
				} else {
					$cells.each(function(){
						if(this.dataset.teach.indexOf(v) > -1){
							this.style.visibility = "visible";
						} else {
							this.style.visibility = "hidden";
						}
					})
				}
				//$cells.first().css("visibility", "hidden");
			}
		});
		
	</script>
		

		
	</body>	
</html>	