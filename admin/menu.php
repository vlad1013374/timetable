<?php 
function endsWith($string, $endString)
{
    $len = strlen($endString);
    if ($len == 0) {
        return true;
    }
    return (substr($string, -$len) === $endString);
}
?>

<div class="menu">
    <a class="a-menu <?= endsWith($_SERVER['SCRIPT_NAME'], '/index.php') ? 'a-menu-here':'' ?>" href="index.php">Список недель</a>
    <a class="a-menu <?= endsWith($_SERVER['SCRIPT_NAME'], '/data-set.php') ? 'a-menu-here':'' ?>" href="data-set.php">Справочники</a>
    <a class="a-menu <?= endsWith($_SERVER['SCRIPT_NAME'], '/reports.php') ? 'a-menu-here':'' ?>" href="reports.php">Отчеты</a>
</div>