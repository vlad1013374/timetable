<?php
require '../connection/db.php';
require "model.php";

$json_week = $_POST['week'];
if (isset($json_week)) {
	/*add_week(json_decode($json_week));*/
	if(add_week($json_week)){
		echo "Всё ок"; die();
	}else{
		echo "Уже существует"; die();
	}
	
}