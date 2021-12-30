<div>
	Изменение расписания:
</div>
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
  	
    <div >
     	Номер недели <input type="text" class="week-num"><br>
     	Дата понедельника <input type="text" class ="week-start" value="<? echo date('Y-m-d', strtotime('monday next week')) ?>" placeholder="YYYY-MM-DD"><br>
     	Дата пятницы <input type="text" class ="week-stop" value="<? echo date('Y-m-d', strtotime('friday next week')) ?>" placeholder="YYYY-MM-DD">
    </div>
   	<button class="add-week ">Добавить</button>
   
  </div>

  
</div>