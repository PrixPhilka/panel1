<?php 
include($_SERVER['DOCUMENT_ROOT'].'/data/config.php');
$sql  = new SQL();
header("Content-Disposition: attachment; filename={$_GET['q']}.txt");
$list = $sql->fetchAll("SELECT * FROM `accounts` WHERE `site_name` LIKE ?", [$_GET['q']]);

foreach ($list as $key => $value) {
	echo $value['uniq']."\r\n";
}
?>