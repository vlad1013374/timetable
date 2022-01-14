<?php

function add($rus, $en)
{
	
	echo '
		<button class="btn btn-primary add '.$en.'-add" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas'.$en.'" aria-controls="offcanvas'.$en.'">
			  Добавить '.$rus.'
		</button>
		<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas'.$en.'" aria-labelledby="offcanvas'.$en.'">
		<div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvas'.$en.'">Добавить '.$rus.'</h5>
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
            </div>
      	
      
	';
}

?>