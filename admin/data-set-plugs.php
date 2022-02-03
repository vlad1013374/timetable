<?php

function add_header($rus, $en)
{
	
	echo '
		<br><button class="k-button add '.$en.'-add" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas'.$en.'" aria-controls="offcanvas'.$en.'">
			  Добавить '.$rus.'
		</button>
		
              
      	
      
	';
}

/*foreach ($subjects as $subject) {
                  		echo '<option>'.$subject['name'].'</option>';
                  	}*/
function add_teacher()
{
	global $subjects;
	echo '<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasteacher" aria-labelledby="offcanvasteacher">
		<div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasteacher">Добавить преподавателя</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
			  <div class="add-week-block">
               
                <div class="dropdown mt-3">
                <form id="utf" method="post">
                 <div>
                  <div class="w-label">Преподаватель:</div> 
                  <input type="text" style="width:250px;" class="k-textbox" name="new-teacher-name" placeholder="Имя">
                 </div>
                 <div class="t-subs">
                  <div id="t-sub-select" >
                    <div class="w-label">Предмет:</div> 
                    <select style="width:250px;" multiple id="teacher-subject-select" name="sub-add-teacher[]">
                    ';
                    	foreach ($subjects as $subject) {
                    		echo '<option value="'.$subject["id"].'">'.$subject["name"].'</option>';
                    	}
                    echo '</select>
                  </div>
                 </div>
                 <div>
                  <div class="w-label">Блокировка:</div> 
                  <input type="checkbox" value="1" class="k-checkbox" name="is-active">
                 </div>
                </form>
				<div>
				<div class="w-label">&nbsp;</div>
                <input type="submit" form="utf" class="k-button" name="add-teacher" value="Добавить">
                </div>
                </div>
              </div>
			  </div>
            </div>';
}

function edit_teacher()
{
	echo '<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvaseditteacher" aria-labelledby="offcanvaseditteacher">
		<div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvaseditteacher">Редактировать преподавателя</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              
            </div>';
}

function add_sub()
{
  global $auds;
	echo '<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvassubject" aria-labelledby="offcanvassubject">
		<div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvassubject">Добавить предмет</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
               <div class="add-week-block">
                <div class="dropdown mt-3">
                <form method="post">
                <div>
                  <div class="w-label">Название предмета:</div>
                 <input style="width:200px;" type="text" class="k-textbox" name="new-subject-name">
                </div>
                <div>
                  <div class="w-label">Короткое название:</div>
                 <input style="width:200px;" type="text" class="k-textbox" name="new-subject-short-name">
                </div>
                <div>
                  <div class="w-label">Аудитория по умолчанию:</div>
                 <select style="width:200px;" id="new-default-auditory" name="new-default-auditory">
                 <option value="" selected></option>';

                  foreach ($auds as $aud) {
                    echo '<option value="'.$aud['id'].'">'.$aud['name'].'</option>';
                  }
                echo '</select>
                </div>
                 <div>
				<div class="w-label">&nbsp;</div>
                 <input class="k-button" type="submit" name="add_subject" value="Сохранить">
                </div>
				</form>
                </div>
                </div>
              </div>
            </div>';
}

function add_room()
{
	echo '<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasaud" aria-labelledby="offcanvasaud">
		<div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasaud">Добавить кабинет</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
               <div class="add-week-block">
                <div class="dropdown mt-3">
                <form method="post">
                 <div>
				<div class="w-label">Номер:</div>
				<input class="k-textbox" style="width:200px;" type="text" name="aud">
				</div>
                 <div>
				<div class="w-label">Вместимость:</div>
				<input id="capacity" type="text" style="width:200px;" name="capacity">
</div>
                 <div>
				<div class="w-label">&nbsp;</div>
                 <input type="submit" class="k-button" name="add-aud" value="Добавить">
				 </div>
                </form>
                </div>
                </div>
              </div>
            </div>';
}




?>