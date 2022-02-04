<?php 

require '../connection/db.php';
require 'model.php';
/*$names = R::getAll('SELECT  t.name as name_teach, s.name as name_sub from teacher_subjects ts join teachers t on t.id = teacher_id join subjects s on s.id = subject_id');*/
if(isset($_POST['editModalTeacher'])){
	$data_teachers = R::getAll('SELECT s.id as id_sub, s.name as name_sub FROM `teachers` t
	join teacher_subjects
	ts on t.id = ts.teacher_id
	join subjects s on ts.subject_id = s.id
	WHERE t.id = ?', [$_POST['editModalTeacher']]);
	echo json_encode($data_teachers); die();
}else if(isset($_POST['newTeacher'])){
	addNewTeacher($_POST['newTeacher']);
	
}else if(isset($_POST['editTeacher'])) {
	editTeacher($_POST['editTeacher']);

	
}else{
	$data_teachers = R::getAll('SELECT t.id as id, t.name as name_teach, GROUP_CONCAT(s.name) as name_sub FROM `teachers` t
	join teacher_subjects
	ts on t.id = ts.teacher_id
	join subjects s on ts.subject_id = s.id
	group by t.name');

	$data = ["data"=>$data_teachers];
	echo json_encode($data);
}




		
