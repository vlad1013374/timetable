<?php 

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

}

if($_GET['dtype'] == 'teachers'){
	$data_teachers = R::getAll('SELECT t.id as id, GROUP_CONCAT(s.id) as sub_id, t.name as name_teach, GROUP_CONCAT(s.name) as name_sub FROM `teachers` t
	join teacher_subjects
	ts on t.id = ts.teacher_id
	join subjects s on ts.subject_id = s.id
	group by t.name');

	$data = ["data"=>$data_teachers];
	echo json_encode($data); die();
}else if($_GET['dtype'] == 'subjects'){
	$data_subjects = R::getAll('SELECT * FROM subjects');
	$data = ["data"=>$data_subjects];
	echo json_encode($data); die();
}else if($_GET['dtype'] == 'rooms'){
	$data_rooms = R::getAll('SELECT * FROM rooms');
	$data = ["data"=>$data_rooms];
	echo json_encode($data); die();
}




		
