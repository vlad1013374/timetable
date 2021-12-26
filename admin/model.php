<?php

function get_table($week, $classes, $subjects)
{

	$days = array('Понедельник' , 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
	$days_en = array('monday' , 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
	$time = array("8:45 - 10:20", "10:30 - 12:00", "12:30 - 14:05", "14:15 - 15:50");
	$colspan_day = count($classes)*2+3;

	echo '

	<form method="POST">
	<input type="submit" name="save" value="Сохранить" class="send">
	<table cellpadding="4" cellspacing="0" border="1" class="time-t">
			<tr>
				<td class="week-num" colspan = "2">10 уч. неделя</td>';

				
					foreach ($classes as  $class) {
						echo '<td class="cls" colspan="2">'.$class.'</td>';
					}
				
				
			echo '</tr>';
			
			
			
			
			foreach ($days as $day) {
				echo '<tr>
				<td class = "day-border" colspan = "'.$colspan_day.'">'.$day.'</td>
				</tr>
				<tr>';
				echo '<tr>';
				echo '<td class = "date" rowspan="5"><input type="text" placeholder="Дата" name="date[]" value="'.date('d M', strtotime($days_en[$n].' '.$week.' week')).'"></td>';
				echo '</tr>';
				for ($i=1; $i <= 4; $i++) { 
					$day_sub = R::getAll( 'SELECT `subject`,`teacher`, `audithory` FROM timetable where  `time` = ? and  `date`=? ', [$i, date('d.m', strtotime($days_en[$n].' '.$week.' week'))]);
					
					
					echo '<td class = "time" >'.$time[$i - 1].'</td>';
					foreach ($day_sub as  $value) {
						echo '<td class = "subject">
						<select class="js-selectize" name="sub[]">';
						if(!empty($value['subject'])){
							echo '<option selected>'.$value['subject'].'/'.$value['teacher'].'</option>';
						}
						foreach ($subjects as $subject) {
							echo '<option >'.$subject.'</option>';
						}'
						</select>
						</td>';
						
						echo '<td class="classroom">
						<select class="aud" name = "aud[]">';
						echo '<option selected>'.$value['audithory'].'</option>';
						foreach ($audithories as $value) {
							echo '<option>'.$value.'</option>';
						}
						echo '</td>';
					
					}
					echo '</tr>';
					
				}
				$n = $n+1;
			}

		echo '</table></form>'	;

}


function save($subs, $auds, $classes)
{
	$days = array('Понедельник' , 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота');
	$days_en = array('monday' , 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
	$time = array("8:45 - 10:20", "10:30 - 12:00", "12:30 - 14:05", "14:15 - 15:50");
	$sub_classes =array();
	$sub_time = array();
	for ($i=0; $i < count($days)*4; $i++) { 
		foreach ($classes as  $value) {
			array_push($sub_classes, $value);
		}
	}
	foreach($days as $day){
		for ($i=0; $i < 4; $i++) { 
			for ($n=0; $n < count($classes); $n++) { 
				array_push($sub_time , $i+1);
			}
		
		}
	}
	
	foreach ($days as $day) {
		echo $day;
		echo $sub_classes[$i].' - '.$sub_time[$i].' - '.  $subs[$i].' ='. $auds[$i];
			
		$i = $i +1;
		echo '<br>';
		/*for ($i=0; $i < count($classes)*4; $i++) { 
			
			
		}*/
	}
	
}

?>


