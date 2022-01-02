<?php
	require '../connection/db.php';
	require 'model.php';

	if(!array_key_exists('dataauto', $_POST)){
		echo json_encode(array('status'=> 'ERROR', 'message'=>'Null data')); die();
	}else{
		auto_save($_POST['dataauto']);
		echo json_encode(array('status'=> 'OK', 'message'=>'Автосохранение')); die();
	}


	