<?php

function add_header($rus, $en)
{
	
	echo '
		<button class="btn btn-primary add '.$en.'-add" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas'.$en.'" aria-controls="offcanvas'.$en.'">
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
               
                <div class="dropdown mt-3">
                <form method="post">
                 <div>
                  Преподаватель: 
                  <input type="text" name="new-teacher-name" placeholder="Имя">
                 </div>
                 <div class="t-subs">
                  Предмет: 
                  <select id="t-sub-select" name="sub-new-teacher[]">';
                  	foreach ($subjects as $subject) {
                  		echo '<option value="'.$subject["id"].'">'.$subject["name"].'</option>';
                  	}
                  echo '</select>
                 </div>
                 
                 
                 <input type="submit" name="add-teacher" value="Добавить">
                </form>
                <button class="add-t-sub-select">Добавить поле</button>
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
	echo '<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvassubject" aria-labelledby="offcanvassubject">
		<div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvassubject">Добавить предмет</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
               
                <div class="dropdown mt-3">
                <form method="post">
                 
                 <div>
                  Предмет: 
                  <select name="subject">
                  <?php foreach ($subjects as $subject): ?>
                    
                      <option value=""></option>
                    
                  <?php endforeach ?>
                  </select>
                 </div>
                 <div>
                  Класс: 
                  <select name="class">
                  <?php foreach ($classes as $class): ?>
                    
                      <option value=""></option>
                    
                  <?php endforeach ?>
                  </select>
                 </div>
                 <div>
                  Номер пары: 
                  <select name="lesson">
                    <option></option>
                  <?php foreach ($lessons as $lesson): ?>
                    
                      <option value=""></option>
                    
                  <?php endforeach ?>
                  </select>
                 </div>
                 <div>
                  День: 
                  <select name="day">
                    <option></option>
                  <?php $n = 1;?>
                  <?php foreach (DAYS as $day): ?>
                      
                      <option value="<?=$n?>"><?=$day?></option>
                      <?php $n = $n+1;?>
                  <?php endforeach ?>
                  </select>
                 </div>
                 
                 <input type="submit" name="add" value="Добавить">
                </form>
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
               
                <div class="dropdown mt-3">
                <form method="post">
                 <div>
                  Пеподаватель: 
                  <select name="teacher">
                  <?php foreach ($teachers as $teacher): ?>
                    
                      <option value=""></option>
                    
                  <?php endforeach ?>
                  </select>
                 </div>
                 <div>
                  Предмет: 
                  <select name="subject">
                  <?php foreach ($subjects as $subject): ?>
                    
                      <option value=""></option>
                    
                  <?php endforeach ?>
                  </select>
                 </div>
                 <div>
                  Класс: 
                  <select name="class">
                  <?php foreach ($classes as $class): ?>
                    
                      <option value=""></option>
                    
                  <?php endforeach ?>
                  </select>
                 </div>
                 <div>
                  Номер пары: 
                  <select name="lesson">
                    <option></option>
                  <?php foreach ($lessons as $lesson): ?>
                    
                      <option value=""></option>
                    
                  <?php endforeach ?>
                  </select>
                 </div>
                 <div>
                  День: 
                  <select name="day">
                    <option></option>
                  <?php $n = 1;?>
                  <?php foreach (DAYS as $day): ?>
                      
                      <option value="<?=$n?>"><?=$day?></option>
                      <?php $n = $n+1;?>
                  <?php endforeach ?>
                  </select>
                 </div>
                 
                 <input type="submit" name="add" value="Добавить">
                </form>
                </div>
              </div>
            </div>';
}
?>