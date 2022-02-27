<?php 
const DAYS =  array("Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота");
require '../connection/db.php';
require 'model.php';
/*$names = R::getAll('SELECT  t.name as name_teach, s.name as name_sub from teacher_subjects ts join teachers t on t.id = teacher_id join subjects s on s.id = subject_id');*/
if(isset($_POST['newTeacher'])){
	addNewTeacher($_POST['newTeacher']);
	
}else if(isset($_POST['editTeacher'])) {
	editTeacher($_POST['editTeacher']);

}else if(isset($_POST['newSubject'])) {
	addNewSubject($_POST['newSubject']);

}else if(isset($_POST['newRoom'])) {
	addNewRoom($_POST['newRoom']);

}else if(isset($_POST['newLink'])) {
	addNewLink($_POST['newLink']);

}else if(isset($_POST['removeLink'])) {
	removeLink($_POST['removeLink']);

}


switch ($_GET['dtype']) {
	case 'teachers':
			$data_teachers = R::getAll('SELECT t.id as id, GROUP_CONCAT(s.id) as sub_id, t.name as name_teach, GROUP_CONCAT(s.name) as name_sub FROM `teachers` t
			join teacher_subjects
			ts on t.id = ts.teacher_id
			join subjects s on ts.subject_id = s.id
			group by t.name');

			$data = ["data"=>$data_teachers];
			echo json_encode($data);
		break;
	
	case 'subjects':
			$data_subjects = R::getAll('SELECT * FROM subjects');
			$data = ["data"=>$data_subjects];
			echo json_encode($data);
		break;
	case 'links':
			$data_links = R::getAll('SELECT teacher_id, subject_id, class_id, t.name as t_name, s.name as s_name, c.name as c_name, lesson_id, week_day from links l
		    join teachers t on t.id = teacher_id
		    join subjects s on s.id = subject_id
		    join classes c on c.id = class_id
		    ');
		    $i = 0;
		    foreach ($data_links as  $object) {
		    	$data_links[$i]['week_day'] = DAYS[$object['week_day']-1];
		    	$i += 1;
		    }
		    $data = ["data"=>$data_links];
			echo json_encode($data);
		break;
	case 'rooms':
			$data_rooms = R::getAll('SELECT * FROM rooms');
			$data = ["data"=>$data_rooms];
			echo json_encode($data);
		break;

}



		
