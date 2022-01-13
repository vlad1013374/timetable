<?php
require '../connection/db.php';
require "model.php";

$json_week = $_POST['week'];
$week_active = $_POST['weekActive'];
$week_delete = $_POST['weekDelete'];
$week_edit = $_POST['weekEdit'];
if (isset($json_week)) {
	/*add_week(json_decode($json_week));*/
	if(add_week($json_week)){
		echo "Всё ок"; die();
	}else{
		echo "Уже существует"; die();
	}
	
}elseif(isset($week_active)){
	if(add_active_week($week_active)){
		echo "Всё ок"; die();
	}
	
}elseif(isset($week_delete)){
	if(delete_week($week_delete)){
		echo "Всё ок"; die();
	}
	
}elseif(isset($week_edit)){
	if(edit_week($week_edit)){
		echo "Всё ок"; die();
	}
	
}

