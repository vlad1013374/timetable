<?php 
	require '../connection/db.php';
	require "model.php";
	$class_like = (array_key_exists('class', $_GET) ? $_GET['class'] : '10')."%";


	
	$is_active = array_key_exists('is_active', $_GET) ? $_GET['is_active'] : null;
	$weekId = array_key_exists('weekId', $_GET) ? $_GET['weekId'] : null;
	$lessons = R::getAll( 'SELECT * FROM lessons');
	$classes = R::getAll( 'SELECT * FROM classes where name like ?' , [$class_like]);
	$active_tid = '';
	if(isset($is_active)){
		$week = R::getAssocRow( 'SELECT * FROM weeks where is_active = "1"' );
		if(array_key_exists("teachid",$_COOKIE)){
			$active_tid = intval($_COOKIE['teachid']);
			$timetable = R::getAll( 'SELECT t.*, c.name as clname,s.name as sname, s.short_name as sname2, t.room_id as rname FROM timetables t 
			join subjects s on t.subject_id=s.id 
			join classes c on t.class_id=c.id 
			left join teachers teach on t.teacher_id=teach.id 
			join weeks w on week_id = w.id
			where w.is_active = "1" and t.teacher_id= ? order by sname', array($active_tid));
		} else {
			$timetable =array();
		}
	}else{
		$week = R::getAssocRow( 'SELECT * FROM weeks where id = ?', [$weekId] ); // $_GET['week'];
		$timetable =[];/* R::getAll( 'SELECT t.*,s.name as sname, s.short_name as sname2, teach.name as tname, room_id as rname FROM timetables t 
		join subjects s on t.subject_id=s.id 
		left join teachers teach on t.teacher_id=teach.id 
		where t.week_id =? order by sname', [$weekId]);*/
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
			//foreach($classes as $c) {
				//$cid = $c['id'];
				if(!array_key_exists($day, $tt)){
					$tt[$day] = array();
				}
				
				if(!array_key_exists($lid, $tt[$day])){
					$tt[$day][$lid] = array();
				}
				
				/*if(!array_key_exists($cid, $tt[$day][$lid])){
					$tt[$day][$lid][$cid] = new ViewItem();
				}*/
			//}
		}
	}
		
	foreach($timetable as $t) {		
		//if(array_key_exists($t['class_id'],$tt[$t['day']][$t['lesson_id']]))
			array_push($tt[$t['day']][$t['lesson_id']], $t);
	}
	//print_r($tt); die();
 	
	
	//print_r($tt);

	$config = R::getAssoc( 'SELECT param as `key`, value FROM config where section= ?', ['editor']);
	$subjects = R::getAll( 'SELECT * FROM subjects order by name');
	
	$rooms = R::getAll( 'SELECT * FROM rooms');
	
	//$teachers = R::getAll( 'SELECT t.*, subject_id  FROM teachers t join teacher_subjects s on t.id=s.teacher_id order by t.name');
	$teachers = R::getAll( 'SELECT * FROM teachers order by name');
	
	$class_width =  16.666;
	$subj_width =  16.666;

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

	$dayofweek = date('N');	
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
		   
		   .tr-subj { font-size:20px; font-weight:bold;  }
		   
		   .tr-teach {   font-size:12px;   }
		   
		   .tr-subj td{   padding:5px; height:58px; }
		   
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
		   
		   .tr-subj-fl {font-size:12px; text-align:right;}
		   
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
		   
		   .tr-subj-normal {
			   font-size:14px;
			   font-weight:normal;
		   }
		   
		   span.flag-color-1 {color:black;}
		   span.flag-color-2 {color:green/*#3a7a1f*/;}
		   span.flag-color-4 {color:purple/*#762e84*/;}
		   span.flag-color-8 {color:#016e7f;}
		   span.flag-color-16 {color:#d51923;}
		   span.flag-color-32 {color:#ff5c1a;}
		   
		   td.flag-color-1 {background-color:#caf0ff;}
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
		   
		   .table-subj {min-height:29px;}
		   
		   .table-header td:nth-child(<?= $dayofweek ?>) {
			   background: #169fe6;
		   }
		   
		   .table-subj td:nth-child(<?= $dayofweek ?>) {
			   border-left:1px solid #169fe6;
			   border-right:1px solid #169fe6;
		   }
		   
		
		</style>
	</head>
	<body class = "main">
	<div align="right" class="div-filter">
	<span class="lbl" style="float:left; position:relative; top:7px; left:0px;"><?= $week_model->number; ?> учебная неделя</span>
	<span class="lbl">Фильтровать по</span>
	<input style="width:220px;" type="text" placeholder="преподавателю" id="byTeacher">
	</div>
			<table class="table-main" border=1>
			<!--<col width="30px">	-->		
			<col width="35px">			
			<tr class="tr-head">
				<td class="uch-ned" align=center colspan=1>&nbsp;</td>
				
				<td>
					<table width="100%" class="table-header">
						<col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>">
						<col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>">
						<tr>
							<?php
								foreach ($week_model->days as  $day) {
									echo '<td colspan=1 class="">'.$day->date->format("d.m").'<br>'. $day->dayName.'</td>';
								}
							?>	
						</tr>
					</table>
				</td>
				
			</tr>	
			<?php 
				function un($arr, $prop){
					$ret = array();
					foreach($arr as $a){
						$ret[] = $a[$prop];
					}
					
					return implode(', ', array_unique($ret));
				}
			
				foreach($week_model->days[0]->lessons as $i=>$lesson) { ?>
				<tr>
					<td class="td-lesson-time"><?= substr($lesson->start,0,5).'-<br>'.substr($lesson->stop,0,5) ?></td>
					<td>
						<table width="100%" class="table-subj">
						<col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>">
						<col width="<?=$subj_width.'%'?>"><col width="<?=$subj_width.'%'?>">
						<tr class="tr-subj">
							<?php
							
								foreach($week_model->days as $day) {
									$d = $day->date->format("Y-m-d");
									if(count($tt[$d][$lesson->lessonId]) == 0){
										echo "<td>&nbsp;</td>";
										continue;
									}
									
									$f = intval($tt[$d][$lesson->lessonId][0]["flags"]);
									for($j=1; $j< count($tt[$d][$lesson->lessonId]); $j++){
										$f = $f | intval($tt[$d][$lesson->lessonId][$j]["flags"]);
									}
									
									$flag_text = getFlagsString($f);
									$flags_cl = getFlagsClasses($f);
									//for ($i = 0; $i < count($tt); $i++) {
										?>
										<td class="<?= $flags_cl ?>" valign="top">
										<div class="tr-subj-fl"><?= $flag_text ?></div>
										<div><?= un($tt[$d][$lesson->lessonId], "sname2") ?></div>
										<div class="tr-subj-normal"><?= str_replace(' - ','&nbsp;-&nbsp;',un($tt[$d][$lesson->lessonId], "clname")) ?></div>
										<div class="tr-subj-normal"><?= un($tt[$d][$lesson->lessonId], "rname") ?></div>
										<div class="tr-subj-normal"><?= $tt[$d][$lesson->lessonId][0]["comment"] ?></div>
										
										</td>
							<?php   //}
								}
							?>	
						</tr>
						</table>
					
					</td>
				</tr>
			
		<?php
			}
		?>
				
				
			

	</table>
	
	

	<script>
		function setCookie(name, value, options = {}) {

		  options = {
			path: '/',
			// при необходимости добавьте другие значения по умолчанию
			...options
		  };

		  //if (options.expires instanceof Date) {
			  let d = new Date();
			  d.setFullYear(d.getFullYear() + 5);
			options.expires = d.toUTCString();
		 // }

		  let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

		  for (let optionKey in options) {
			updatedCookie += "; " + optionKey;
			let optionValue = options[optionKey];
			if (optionValue !== true) {
			  updatedCookie += "=" + optionValue;
			}
		  }

		  document.cookie = updatedCookie;
		}
		var classes = <?php echo json_encode($classes); ?>;
		var teachers = <?php echo json_encode($teachers); ?>;
		$cells = $("td[data-teach]");
		$cells2 = $("td[data-cl]");
		
		$("#byTeacher").kendoComboBox({
			dataSource:teachers,
			dataTextField:'name',
			dataValueField:'id',
			value:'<?= $active_tid ?>',
			filter:"startswith",
			noDataTemplate: 'Ничего не нашлось',
			change: function(e){
				let v = e.sender.value();
				if(v) {
					setCookie("teachid", v);
					document.location.reload();
				}
				
			}
		});
		
	</script>
		

		
	</body>	
</html>	