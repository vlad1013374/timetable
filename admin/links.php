
	<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvaslinks" aria-labelledby="offcanvaslinksLabel">
	  <div class="offcanvas-header">
	    <h5 class="offcanvas-title" id="offcanvaslinksLabel">Добавление связи</h5>
	    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	  </div>
	  <div class="offcanvas-body">
	 	<div class="add-links-block">
	    <div class="dropdown mt-3">
	    
	     <div>
	     		<div class="w-label">Преподаватель:</div> 
                    <select style="width:250px;" multiple="multiple" id="links-teacher-select" >
                     <?php foreach ($teachers as $teacher): ?>
	     		
			     			<option value="<?=$teacher['id']?>"><?=$teacher['name']?></option>
			     		
			     	<?php endforeach ?>
			     	</select>
        </div>
	     	
	     
	     <div>
	     		<div class="w-label">Предмет:</div> 
                    <select style="width:250px;" multiple="multiple" id="links-subject-select" >
                    <?php foreach ($subjects as $subject): ?>
	     		
			     			<option value="<?=$subject['id']?>"><?=$subject['name']?></option>
			     		
			     	<?php endforeach ?>
			     	</select>
         </div>
	     	
	    
	     <div>
	     	<div class="w-label">Класс:</div> 
                <select style="width:250px;" multiple="multiple" id="links-class-select" >
                <?php foreach ($classes as $class): ?>
	     		
		     			<option value="<?=$class['id']?>"><?=$class['name']?></option>
		     		
		     	<?php endforeach ?>
		     	</select>
        </div>
	     	
	    
	     <div>
	     	<div class="w-label">Номер пары:</div> 
                <select style="width:250px;" multiple="multiple" id="links-lesson-select">
                <option value=''></option>
                <?php foreach ($lessons as $lesson): ?>
	     		
		     			<option value="<?=$lesson['id']?>"><?=$lesson['name']?></option>
		     		
		     	<?php endforeach ?>
		     	</select>
         </div>
	     	
	     
	     <div>
	     	<div class="w-label">День недели:</div> 
                <select style="width:250px;" multiple="multiple" id="links-week-day-select">
                <option value=''></option>
                <?php $n = 1;?>
		     	<?php foreach (DAYS as $day): ?>
		     			
		     			<option value="<?=$n?>"><?=$day?></option>
		     			<?php $n = $n+1;?>
		     	<?php endforeach ?>
		     	</select>
         </div>
	     	
	    
	     
	     <div class="w-label">&nbsp;</div>
	       <button  class="k-button" id="add-link">Добавить</button>
	   </div>
	  </div>
	</div>
	   
	</div>
		
	
