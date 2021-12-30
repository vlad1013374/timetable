<div style="float:left; margin: 10px;">
	Изменение расписания:

<ul>
<?php
	foreach ($weeks as $week) {
		if (date('Y-m-d', strtotime('monday this week')) == $week['start']) {
			echo '<li><a href = "editor.php?weekId='.$week['id'].'">'.$week['number'].' неделя</a><label class="week-list">(текущая неделя)</label></a></li>';
		}elseif (date('Y-m-d', strtotime('monday next week')) == $week['start']) {
			echo '<li><a href = "editor.php?weekId='.$week['id'].'">'.$week['number'].' неделя</a><label class="week-list">(следущая неделя)</label></a></li>';
		}else{
			echo '<li><a href = "editor.php?weekId='.$week['id'].'">'.$week['number'].' неделя</a></li>';
		}
		
	}
?>

</ul>

<button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
  Добавить неделю
</button>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Добавление недели</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
  	
    <div class="add-week-block">
     	<div><div class="w-label">Номер недели</div> <input type="text" class="week-num"></div>
     	<div>
			<div class="w-label">Дата понедельника</div> 
			<input type="text" class ="week-start" value="<? echo date('d.m.Y', strtotime('monday next week')) ?>" >
		</div>
     	<div>
			<div class="w-label">Дата воскресенья</div>
			<input type="text" class ="week-stop" value="<? echo date('d.m.Y', strtotime('sunday next week')) ?>">
		</div>
		
		<div>
			<div class="w-label">&nbsp;</div>
			<button class="add-week k-button">Добавить</button>
		</div>
    </div>
   	
   
  </div>

  
</div>

</div>

 <a href="settings.php"><button class="btn btn-primary" style="float: right; margin:10px;">Настройки</button></a>