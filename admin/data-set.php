<?php
    require '../connection/db.php';
    require 'model.php';
    require 'data-set-plugs.php';
    $teachers = R::getAll('SELECT * FROM teachers order by name ASC');
    $subjects = R::getAll('SELECT * FROM subjects order by name ASC');
    $auds = R::getAll('SELECT * FROM rooms order by name ASC');
    
    if(isset($_POST['add-aud'])){
      $db_a = R::dispense('rooms');
      $db_a->name = $_POST['aud'];
      $db_a->capacity = $_POST['capacity'];
      R::store($db_a);
    }
    if(isset($_POST['add-teacher'])){
      $db_t = R::dispense('teachers');
      $db_t->name = $_POST['new-teacher-name'];
      R::store($db_t);

      $new_teacher_id = R::getInsertID();
      foreach ($_POST['sub-new-teacher'] as $sub) {
         R::exec('INSERT INTO teacher_subjects (teacher_id, subject_id) values(?,?)', [$new_teacher_id, $sub]);
      } 
    }
    if(isset($_POST['edit-teach-save'])){

      R::exec('DELETE FROM teacher_subjects WHERE teacher_id = ?', [$_POST['edit-teach-id']]);
      foreach ($_POST['sub-edit-teacher'] as $edit_sub_id) {
          R::exec('INSERT INTO teacher_subjects (teacher_id, subject_id) values(?,?)',[$_POST['edit-teach-id'], $edit_sub_id] );
      }
        
    }
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
    .teacher-row:hover{
      background: #E9ECEF;
      cursor: pointer;
    }
  </style>

  <script type="text/javascript" src="includes/js/jquery-3.6.0.js"></script>
  <link rel="stylesheet" href="includes/js/bootstrap.min.css">
  <script src="includes/js/bootstrap.bundle.min.js"></script>
  
</head>
<body>
    <div class="menu">
      <a class="a-menu" href="http://dada.hhos.ru/admin/index.php">Список недель</a>
      <a class="a-menu" href="http://dada.hhos.ru/admin/data-set.php">Информация</a>
    </div>

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
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Настройки</button>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="teachers" role="tabpanel" aria-labelledby="teachers-tab">
        <?php add_header('преподавателя', 'teacher');?>
        <?php add_teacher();?>
        <?php edit_teacher();?>
        <table class="table" >
          <?php foreach ($teachers as $teacher): ?>
            <?php $t_s = R::getAll('SELECT *, s.name as name_sub from teacher_subjects ts
                    join teachers t on t.id = teacher_id
                    join subjects s on s.id = subject_id where teacher_id =?
                     ', [$teacher['id']]); ?>
            <tr class="teacher-row" data-bs-toggle="offcanvas" data-bs-target="#offcanvaseditteacher" aria-controls="offcanvaseditteacher">
              <td scope="row"  class="teacher" data-id="<?= $teacher['id']?>"><?= $teacher['name']?></td> 
              
                <td>
                  <?php foreach ($t_s as  $value): ?>
                    <div class="t-sub" data-id="<?=$value['subject_id']?>"><?=$value['name_sub']?></div>
                  <?php endforeach ?>
                </td>
                        
              
             </tr>
          <?php endforeach ?>
          </table>
      </div>
      <div class="tab-pane fade" id="subjects" role="tabpanel" aria-labelledby="subjects-tab">
        <?php add_header('предмет', 'subject');?>
        <?php add_sub();?>
          <?php foreach ($subjects as $subject): ?>
          
            <div class="subject" data-id="<?= $subject['id']?>"><?= $subject['name']?><button type="button"  class="btn-close delete-week" aria-label="Close"></button></div>

          <?php endforeach ?>
      </div>
      <div class="tab-pane fade" id="auds" role="tabpanel" aria-labelledby="auds-tab">
        <?php add_header('кабинет', 'aud');?>
        <?php add_room();?>
        <?php foreach ($auds as $aud): ?>
          
            <div class="aud" data-id="<?= $aud['id']?>"><?= $aud['name']?><button type="button"  class="btn-close delete-week" aria-label="Close"></button></div>
            
         
          <?php endforeach ?>
      </div>
      <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
        <?php require 'settings.php';?>
        
      </div>
      
    </div>

 
    <script src="includes/js/set.js"></script>

    <script type="text/x-template" id="tpl-sub">
        <select name="sub-edit-teacher[]" id="edit-t-sub-select">
          <option value="{subId}" selected>{subName}</option>
            <?php foreach ($subjects as $value_sub): ?>
              <option value="<?=$value_sub['id']?>"><?=$value_sub['name']?></option>
            <?php endforeach ?>
        </select>
    </script>

    <script id = "tpl" type="text/x-template">
      <div class="offcanvas-body" >
        <div class="dropdown mt-3">
          <form method="post">
            <input type="text" id="id" name="edit-teach-id" value="{id}" hidden>
            <input type="text" value="{name}" name="edit-name">
            
            <div class="content-add-teacher">
              
            
            </div>
            <input type="submit" name="edit-teach-save" value="Сохранить">
          </form>
          <button class="add-sub-input">Добавить поле</button>
        </div>
      </div>
    </script>
   
</body>
</html>

