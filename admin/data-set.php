<?php
    require '../connection/db.php';
    require 'model.php';
    require 'data-set-plugs.php'; 	
    
    if(isset($_POST['add-aud'])){
      $db_a = R::dispense('rooms');
      $db_a->name = $_POST['aud'];
      $db_a->capacity = $_POST['capacity'];
      R::store($db_a);
	  header("Location: data-set.php"); die();
    }

    if(isset($_POST['add_subject'])){
      $db_s = R::dispense('subjects');
      $db_s->name= $_POST['new-subject-name'];
      $db_s->short_name= $_POST['new-subject-short-name'];
      $db_s->default_room_id = empty($_POST['new-default-auditory']) ? null : $_POST['new-default-auditory'];
      R::store($db_s);
	  header("Location: data-set.php");  die();
    }
	
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
    .teacher-row:hover{
      background: #E9ECEF;
      cursor: pointer;
    }
	.w-label {
		display:inline-block;
		width:160px;
	}

	.add-week-block form > div {
		margin-bottom:15px;
	}
	
	#t-sub-select > div {display:inline-block;}
	#teachers .offcanvas-start {width:450px;}
  </style>
  <link rel="stylesheet" href="includes/kendo/kendo.common.min.css">
  <link rel="stylesheet" href="includes/kendo/kendo.custom.css">
  
  <script type="text/javascript" src="includes/js/jquery-3.6.0.js"></script>

  <link rel="stylesheet" href="includes/js/bootstrap.min.css">
  <script src="includes/js/bootstrap.bundle.min.js"></script>

  <link rel=stylesheet href="includes/menu.css">
  <script src="includes/kendo/kendo.all.min.js"></script>
	<script src="includes/kendo/kendo.culture.ru-RU.min.js"></script>
	<script src="includes/kendo/kendo.messages.ru-RU.min.js"></script>
	<script>kendo.culture("ru-RU");</script>
  
  
  <script>
    $(document).ready(function() {

        $('#teachers-table').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "data-set-control.php",
                "type": "post"
            }, 
            "columns": [
                { "data": "name_teach" },
                { "data": "name_sub" },

            ],
            paging: false,
            searching: false,
            ordering : false,
            info:false,
            "stripeClasses": ["teacher-row"],
            createdRow: function (row, data, dataIndex) {
                $(row).attr('data-id', data.id);
                $(row).attr('data-bs-toggle', "offcanvas");
                $(row).attr('data-bs-target', "#offcanvaseditteacher");
                $(row).attr('aria-controls', "offcanvaseditteacher");
            }

        } );
        $('tbody tr').addClass('teacher-row');
    } );
  </script>
</head>
<body>
    <?php require 'menu.php'; ?>

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

        <table id="teachers-table" class="display table" style="width:100%">
          <thead>
              <tr>
                  <th>Name</th>
                  <th>Subject</th>

              </tr>
          </thead>
          
      </table>
        
      </div>
      <div class="tab-pane fade" id="subjects" role="tabpanel" aria-labelledby="subjects-tab">
        <?php add_header('предмет', 'subject');?>
        <?php add_sub();?>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Название</th>
              <th scope="col">Короткое название</th>
              <th scope="col">Аудитория по умолчанию</th>
            </tr>
          </thead>
          <?php foreach ($subjects as $subject): ?>
            <tr  data-id="<?= $subject['id']?>">
              <td><div class="subject"><?= $subject['name']?></div></td>
              <td><div class="subject"><?= $subject['short_name']?></div></td>
              <td><div class="subject"><?= $subject['default_room_id']?></div></td>
            </tr>
          <?php endforeach ?>
        </table>
      </div>
      <div class="tab-pane fade" id="auds" role="tabpanel" aria-labelledby="auds-tab">
        <?php add_header('аудиторию', 'aud');?>
        <?php add_room();?>
        <table class="table" >
          <thead>
            <tr>
              <th scope="col">Номер</th>
              <th scope="col">Вместимость</th>
            </tr>
          </thead>
        <?php foreach ($auds as $aud): ?>
            <tr data-id="<?= $aud['id']?>">
              <td><div class="aud" ><?= $aud['name']?></div></td>
              <td><div class="aud" ><?= $aud['capacity']?></div></td>
            </tr>
         
          <?php endforeach ?>
          </table>
      </div>


      <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
        <?php require 'settings.php';?>
        
      </div>
      
    </div>

 

    <script type="text/x-template" id="tpl-sub">
      <div id="t-sub-select">
        Предмет:
        <select name="sub-{type}-teacher" id="t-sub-select">
          <option value="{subId}" selected>{subName}</option>
            <?php foreach ($subjects as $value_sub): ?>
              <option value="<?=$value_sub['id']?>"><?=$value_sub['name']?></option>
            <?php endforeach ?>
        </select>
        <button type="button" class="btn-close delete text-reset" aria-label="Close"></button>
      </div>
    </script>

    <script id = "tpl" type="text/x-template">
      <div class="offcanvas-body" >
        <div class="edit-teacher-block">
          <div class="dropdown mt-3">
         
            <input type="text" id="id" name="edit-teach-id" value="{id}" hidden>
            <input type="text" value="{name}" name="edit-teacher-name">
            
            <div class="content-add-teacher">
           
            
            
            </div>
            
            <button id="edit-teach-save">Сохранить</button>
          <button class="add-sub-input">Добавить поле</button>
          </div>
        </div>
      </div>
    </script>
   
	<script>
    $("#teacher-subject-select-edit").kendoMultiSelect();
		$("#teacher-subject-select").kendoMultiSelect();
		$("#new-default-auditory").kendoDropDownList();
		$("#capacity").kendoNumericTextBox();
	</script>
    
    <script src="includes/js/set.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/ju/dt-1.11.4/datatables.min.js"></script>
    
</body>
</html>

