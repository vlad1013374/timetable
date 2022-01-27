
<div align="right" style="margin:10px;">
	<span style="float:left;"><b>Список недель</b></span>
	<button class="k-button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasEdit" aria-controls="offcanvasEdit">Редактировать неделю</button> &nbsp;
	<button class="k-button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">Добавить неделю</button> &nbsp;
	<a class="k-button" href="data-set.php">Настройки</a></div>
<div id="listView" style="width:100%;"></div>
<script type="text/x-kendo-template" id="template">
        <div class="week-li" week-id="#:id#" week-number="#:number#">
            <div style="width:100px;"><a href = "editor.php?weekId=#:id#">#:number# неделя</a></div>
            <div style="width:250px;">#:getDates(start, stop)#</div>            
            <div style="width:180px;">#:curMonday == start ? 'Текущая неделя':(nextMonday == start ? 'Следущая неделя':'')#</div>
			<div style="width:100px;" class="is-active-week">#:is_active == '1' ? 'Активна':''#</div>
            <div style="width:130px;">#=is_active == '1' ? '':'<button data-id="'+id+'" class="but-act k-button">Активировать</button>'#</div>
			<div style="width:200px;"><a target="_blank" href="../pub/print.php?weekId=#:id#&class=10">печать 10</a> &nbsp; <a target="_blank" href="../pub/print.php?weekId=#:id#&class=11">печать 11</a></div>
			<div style="width:350px;">#:comment ? comment:''#</div>			
						
			<button type="button" style="float:right; margin:10px;" class="btn-close delete-week" aria-label="Close"></button>
        </div>
    </script>
<script>
const weeks = <?= json_encode($weeks); ?>;
const curMonday = '<?= date('Y-m-d', strtotime('monday this week')) ?>';
const nextMonday = '<?= date('Y-m-d', strtotime('monday next week')) ?>';
const months = ['января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
const w = $(window).height();
$("#listView").kendoListView({
                dataSource: {
                    data: weeks,
                    pageSize: 20
                },
				height: w - 55,
				scrollable: "endless",
                template: kendo.template($("#template").html()),
                
            });



function getDates(start, stop){
	const s = new Date(start);
	const e = new Date(stop);
	
	return s.getDate() + ' ' + months[s.getMonth()] + ' - ' + e.getDate() + ' ' + months[e.getMonth()];
}
</script>


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
			<div class="w-label">Скопировать неделю:</div>
			<select class="copy">
				<option selected></option>
				<?php 
				$months = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
				foreach ($weeks as $week): 
					$tmp_start = date_parse($week['start']);
					$tmp_stop = date_parse($week['stop']);
				?>
					
					<option value="<?=$week['id']?>"><?=$week['number']?> (<?= $tmp_start['day'].' '.$months[$tmp_start['month']-1] ?> - <?=$tmp_stop['day'].' '.$months[$tmp_stop['month']-1]?>)</option>
				<?php endforeach ?>

			</select>
		</div>
		<div>
			<div class="w-label">Комментарий</div>
			<input type="text" class ="week-comment k-textbox" value="">
		</div>
		<div>
			<div class="w-label">&nbsp;</div>
			<button class="add-week k-button">Добавить</button>
		</div>
    </div>
   	
   
  </div>

  
</div>



<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasEdit" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Редактор недели</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
  	
    <div class="add-week-block">
     	<div><div class="w-label">Выберите неделю</div> 
     		<select class="week-id edit">
				<option selected></option>
				<?php 
				$months = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
				foreach ($weeks as $week): 
					$tmp_start = date_parse($week['start']);
					$tmp_stop = date_parse($week['stop']);
				?>
					
					<option value="<?=$week['id']?>"><?=$week['number']?> (<?= $tmp_start['day'].' '.$months[$tmp_start['month']-1] ?> - <?=$tmp_stop['day'].' '.$months[$tmp_stop['month']-1]?>)</option>
				<?php endforeach ?>

			</select>
		</div>
     	<div>
			<div class="w-label">Дата понедельника</div> 
			<input type="text" class ="week-start edit" value="<? echo date('d.m.Y', strtotime('monday next week')) ?>" >
		</div>
     	<div>
			<div class="w-label">Дата воскресенья</div>
			<input type="text" class ="week-stop edit" value="<? echo date('d.m.Y', strtotime('sunday next week')) ?>">
		</div>
		
		<div>
			<div class="w-label">Комментарий</div>
			<input type="text" class ="week-comment k-textbox edit" value="">
		</div>
		<div>
			<div class="w-label">&nbsp;</div>
			<button class="edit-week k-button">Сохранить</button>
		</div>
    </div>
   	
   
  </div>

  
</div>




 