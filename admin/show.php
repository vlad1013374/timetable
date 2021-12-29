Изменение расписания:
<br>
Неделя:
<label ><input type="radio" name="week" value="this">Эта</label>
<label ><input type="radio" name="week" value="next">Следущая</label>
<br>


<button class="but">Показать</button>




<button class ="save">Отправить</button>
	<table cellpadding="4" cellspacing="0" border="1" class="time-t">
			<tr>
				<td class="week-num" colspan = "2">10 уч. неделя</td>

				<?php
					foreach ($classes as  $class) {
						echo '<td class="cls" colspan="2">'.$class.'</td>';
					}
				?>
				
				
			</tr>
			
			
			
		<?php
			foreach ($days as $day) {
				echo '<tr>
				<td class = "day-border" colspan = "'.$colspan_day.'">'.$day.'</td>
				</tr>
				<tr>

				<tr>
				<td class = "date" rowspan="5"><input type="text" placeholder="Дата" value="'.date('d M', strtotime($days_en[$n].' '.$week.' week')).'"></td>
				</tr>';
				for ($i=1; $i <= 4; $i++) { 
					$day_sub = R::getAll( 'SELECT `subject`,`teacher`, `audithory` FROM timetable where  `time` = ? and  `date`=?', [$i, date('d.m', strtotime($days_en[$n].' '.$week.' week'))]);
					
					
					echo '<td class = "time" >'.$time[$i - 1].'</td>';
					foreach ($day_sub as  $value) {
						echo '<td class = "subject">
						<select class="js-selectize">';
						if(!empty($value['subject'])){
							echo '<option selected>'.$value['subject'].'/'.$value['teacher'].'</option>';
						}
						foreach ($subjects as $subject) {
							echo '<option >'.$subject.'</option>';
						}'
						</select>
						</td>';
						
						echo '<td class="classroom">
						<select class="aud">';
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
		?>

	</table>

