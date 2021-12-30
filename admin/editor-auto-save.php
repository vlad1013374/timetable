<?php
	require '../connection/db.php';
	require 'model.php';

	if(!array_key_exists('dataauto', $_POST)){
		echo json_encode(array('status'=> 'ERROR', 'message'=>'Null data')); die();
	}else{
		$data_json_auto = $_POST['dataauto'];
		auto_save(json_decode($data_json_auto));
		echo json_encode(array('status'=> 'OK', 'message'=>'Автосохранение')); die();
	}
	
	
	
	if(rand(0,1000) <= 500){
		echo json_encode(array('status'=> 'OK', 'message'=>'Автосохранение')); die();
	} else {
		echo json_encode(array('status'=> 'ERROR', 'message'=>'Ошибка для теста')); die();
	}


	