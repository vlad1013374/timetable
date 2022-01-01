<?php
	require '../connection/db.php';
	require 'model.php';
	
	

	if(!array_key_exists('data', $_POST)){
		echo json_encode(array('status'=> 'ERROR', 'message'=>'нет данных')); die();
	}else{
		save($_POST['data']);
		echo json_encode(array('status'=> 'OK', 'message'=>'Расписание сохранено')); die();
	}



	