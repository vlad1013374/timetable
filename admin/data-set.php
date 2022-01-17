<?php
    require '../connection/db.php';
    require 'model.php';
    require 'data-set-plugs.php';
    $teachers = R::getAll('SELECT * FROM teachers order by name ASC');
    $subjects = R::getAll('SELECT * FROM subjects order by name ASC');
    $auds = R::getAll('SELECT * FROM rooms order by name ASC');
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    .delete-week{
      opacity: 0.06 !important;
    }
    .delete-week:hover{
      opacity: 1 !important;
    }
    .add{
      float:right;
    }

  </style>

  <script type="text/javascript" src="includes/js/jquery-3.6.0.js"></script>
  <link rel="stylesheet" href="includes/js/bootstrap.min.css">
  <script src="includes/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="teachers-tab" data-bs-toggle="tab" data-bs-target="#teachers" type="button" role="tab" aria-controls="teachers" aria-selected="true">Преподаватели</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="subjects-tab" data-bs-toggle="tab" data-bs-target="#subjects" type="button" role="tab" aria-controls="subjects" aria-selected="false">Предметы</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="auds-tab" data-bs-toggle="tab" data-bs-target="#auds" type="button" role="tab" aria-controls="auds" aria-selected="false">Аудитории</button>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="teachers" role="tabpanel" aria-labelledby="teachers-tab">
        <?php add('преподавателя', 'teacher');?>
          <?php foreach ($teachers as $teacher): ?>
          
            <div class="teacher" data-id="<?= $teacher['id']?>"><?= $teacher['name']?><button type="button"  class="btn-close delete-week" aria-label="Close"></button></div>
            
         
          <?php endforeach ?>
      </div>
      <div class="tab-pane fade" id="subjects" role="tabpanel" aria-labelledby="subjects-tab">
        <?php add('предмет', 'subject');?>
          <?php foreach ($subjects as $subject): ?>
          
            <div class="subject" data-id="<?= $subject['id']?>"><?= $subject['name']?><button type="button"  class="btn-close delete-week" aria-label="Close"></button></div>

          <?php endforeach ?>
      </div>
      <div class="tab-pane fade" id="auds" role="tabpanel" aria-labelledby="auds-tab">
        <?php add('кабинет', 'aud');?>
        <?php foreach ($auds as $aud): ?>
          
            <div class="aud" data-id="<?= $aud['id']?>"><?= $aud['name']?><button type="button"  class="btn-close delete-week" aria-label="Close"></button></div>
            
         
          <?php endforeach ?>
      </div>
      
    </div>

 
 
</body>
</html>

