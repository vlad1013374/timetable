<?php
	require '../connection/db.php';
	require 'model.php';

	if(!array_key_exists('data', $_POST)){
		echo json_encode(array('status'=> 'ERROR', 'message'=>'нет данных')); die();
	}
	
	$data_json = $_POST['data'];
	
	if(rand(0,1000) <= 500){
		echo json_encode(array('status'=> 'OK'), 'message'=>'Расписание сохранено'); die();
	} else {
		echo json_encode(array('status'=> 'ERROR', 'message'=>'ошибка для теста')); die();
	}