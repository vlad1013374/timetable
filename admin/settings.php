<?php

function group_by($key, $data) {
    $result = array();

    foreach($data as $val) {
        if(array_key_exists($key, $val)){
            $result[$val[$key]][] = $val;
        }else{
            $result[""][] = $val;
        }
    }

    return $result;
}

require "../connection/db.php";
$config_set = R::getAssocRow('SELECT * FROM config');
$config_group = group_by('section', $config_set);

if(isset($_POST['save-settings'])){
	foreach ($config_set as $value) {
		//echo $_POST[$value['param']]. ' - '. $value['param'] . '<br>';
		
		R::exec('UPDATE config set value = ? where param = ?', [$_POST[$value['param']], $value['param']]);
	}

	header("Location: settings.php"); die();
}
?><!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Настройки</title>
	<link rel="stylesheet" href="includes/kendo/kendo.common.min.css">
	<link rel="stylesheet" href="includes/kendo/kendo.custom.css">
	<script src="includes/js/jquery-3.6.0.js"></script>
	<script src="includes/kendo/kendo.all.min.js"></script>
	<script src="includes/kendo/kendo.culture.ru-RU.min.js"></script>
	<script src="includes/kendo/kendo.messages.ru-RU.min.js"></script>
	<script>kendo.culture("ru-RU");</script>
	<style>
		.setting-item > div {
			display:inline-block;
			width:250px;
			margin:20px;
		}
	</style>
</head>
<body>

<a href="index.php"><button>На главную</button></a>
<form method="post">
<?php
$sections = array('editor'=> 'Редактор','web'=> 'Веб-версия','print'=> 'Распечатанная версия');
foreach($config_group as $k=> $v){
echo "<h3 style='margin:20px; border-bottom:1px solid black;'>{$sections[$k]}</h3>";
foreach ($v as $config) {
	if($config['type'] == 'int'){
		echo '<div class="setting-item"><div>'.$config['param_ru']."</div> <input style='width:400px;' id='".$config['param']."' type='text' name='".$config['param']."' value='".$config['value']."'> &nbsp;<span>".$config['param_description']."</span></div><script>$('#".$config['param']."').kendoNumericTextBox({format:'n0', min:1});</script>
";
	} else if($config['type'] == 'string'){
		echo '<div class="setting-item"><div>'.$config['param_ru']."</div> <input style='width:400px;' id='".$config['param']."' class='k-textbox' type='text' name='".$config['param']."' value='".$config['value']."'> &nbsp;<span>".$config['param_description']."</span></div>
";
	} else if($config['type'] == 'text') {
		echo '<div class="setting-item"><div>'.$config['param_ru']."</div> <textarea style='width:400px;' rows=2 id='".$config['param']."' class='k-textbox' name='".$config['param']."' >".$config['value']."</textarea> &nbsp;<span>".$config['param_description']."</span></div>
";
	} else if($config['type'] == 'bool'){
		echo '<div class="setting-item"><div>'.$config['param_ru']."</div> <select style='width:400px;' id='".$config['param']."' name='".$config['param']."'><option value='1'>Да</option><option value='0'>Нет</option></select> &nbsp;<span>".$config['param_description']."</span></div><script>$('#".$config['param']."').kendoDropDownList({value:'".$config['value']."'});</script>
";
	}
}
echo '<br><br>';
}
?>
<div class="setting-item"><div></div> <input type="submit" name="save-settings" class="k-button" value="Сохранить" />

</form>


</body>
</html>