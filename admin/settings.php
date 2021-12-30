<?php

require "../connection/db.php";
$config_set = R::getAssocRow('SELECT * FROM config');
?>

<a href="index.php"><button>На главную</button></a>
<form method="post">
<?php
foreach ($config_set as $config) {
	echo '<div>'.$config['param_ru'].":<input type='text' name='".$config['param']."' value='".$config['value']."'></div>";
}
?>

<input type="submit" name="save-settings" >
</form>

<?php

if(isset($_POST['save-settings'])){
	foreach ($config_set as $value) {
		echo $_POST[$value['param']]. ' - '. $value['param'] . '<br>';
		
		R::exec('UPDATE config set value = ? where param = ?', [$_POST[$value['param']], $value['param']]);
	}


}

?>