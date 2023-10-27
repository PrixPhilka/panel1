<?php 
	if (count($_POST)) {
		$chto = $db->real_escape_string($_POST['chto']);
		$na = $db->real_escape_string($_POST['na']);
		$domain = $db->real_escape_string($_POST['domain']);
		$site_name = $db->real_escape_string($_POST['site-name']);

		if ($db->query("INSERT INTO `replace_h` (`id`, `user_id`, `user_login`, `chto`, `na`, `domain`, `site_name`) VALUES (NULL,'{$user_id}', '{$user_info['login']}', '{$chto}', '{$na}', '$domain', '{$site_name}')")) {
			$save_text = '<p class="alert">Замена добавлена</p>';
		} else {
			$save_text = '<p class="alert error">Произошла ошибка</p>';
		}
	} elseif(isset($_GET['delete'])) {
		$delete_id = intval($_GET['delete']);
		$get_delete_domain = $db->query("SELECT * FROM `replace_h` WHERE `id` = {$delete_id}")->fetch_assoc();
		if (isset($get_delete_domain['id']) && $get_delete_domain['user_id'] == $user_id) {
			$db->query("DELETE FROM `replace_h` WHERE `id` = {$delete_id}");
			$save_text = '<p class="alert">Замена удалена</p>';
		} else {
			$save_text = '<p class="alert error">Произошла ошибка при удалении замены</p>';
		}
	} else {
		$save_text = '';
	}
?>

<h2>Замены</h2>
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

	$get_replace_list = $db->query("SELECT * FROM `replace_h` WHERE `user_id` = {$user_id} AND `site_name` = 'omg'")->fetch_all(true);
	$replace_list = '';
	if (count($get_replace_list)) {
		foreach ($get_replace_list as  $value) {
			$replace_list .= "<tr>
					<td aria-label='Найти'><textarea  readonly>{$value['chto']}</textarea></td>
					<td aria-label='Заменить'><textarea readonly>{$value['na']}</textarea></td>
					<td aria-label='Домен'>{$value['domain']}</td>
					<td aria-label='Удалить'><a href=\"/?page=replace&delete={$value['id']}\">Удалить</a></td>
				</tr>";
		}
	}
	

	$get_domain_list = $db->query("SELECT * FROM `domains` WHERE `user_id` = {$user_id} AND `site_name` = 'omg'")->fetch_all(true);
	$domains = '';
	if (count($get_domain_list)) {
		foreach ($get_domain_list as $key => $value) {
			$domains .= "<option value='{$value['domain']}'>{$value['domain']}</option>";
		}
	}

?>


<form action="/?page=replace" method="post">
	<input type="hidden" name="site-name" value="omg">
	<div class="form-controll">
		<p>Что</p>
		<textarea name="chto" id="" cols="30" rows="10"></textarea>
	</div>
	<div class="form-controll">
		<p>На что</p>
		<textarea name="na" id="" cols="30" rows="10"></textarea>
	</div>
	<div class="form-controll">
		<p>Выберите домен</p>
		<select name="domain" class="beatu-select">
			<option value="">--для всех--</option>
			<?= $domains; ?>
		</select>
	</div>
	<br>
	<div class="form-controll">
		<button type="submit">Добавить</button>
	</div>
	<div class="replacelists">
<?php 
if ($replace_list) {
?>

		<table>
			<thead>
				<tr>
					<td>Найти</td>
					<td>Заменить</td>
					<td>Домен</td>
					<td>Удалить</td>
				</tr>
			</thead>
			<tbody>
				<?= $replace_list; ?>
			</tbody>
		</table>
<?php 
} else {
	echo '<p class="alert error">Список замен отсутсвует</p>';
}

?>
	</div>
</form>

<?php 
endif;
?>








<?php 
if (isset($perm_conf['blacksprut'])):

	$get_replace_list = $db->query("SELECT * FROM `replace_h` WHERE `user_id` = {$user_id} AND `site_name` = 'blacksprut'")->fetch_all(true);
	$replace_list = '';
	if (count($get_replace_list)) {
		foreach ($get_replace_list as  $value) {
			$replace_list .= "<tr>
					<td aria-label='Найти'><textarea  readonly>{$value['chto']}</textarea></td>
					<td aria-label='Заменить'><textarea readonly>{$value['na']}</textarea></td>
					<td aria-label='Домен'>{$value['domain']}</td>
					<td aria-label='Удалить'><a href=\"/?page=replace&delete={$value['id']}\">Удалить</a></td>
				</tr>";
		}
	}
	

	$get_domain_list = $db->query("SELECT * FROM `domains` WHERE `user_id` = {$user_id} AND `site_name` = 'blacksprut'")->fetch_all(true);
	$domains = '';
	if (count($get_domain_list)) {
		foreach ($get_domain_list as $key => $value) {
			$domains .= "<option value='{$value['domain']}'>{$value['domain']}</option>";
		}
	}

?>


<form action="/?page=replace" method="post">
	<input type="hidden" name="site-name" value="blacksprut">
	<div class="form-controll">
		<p>Что</p>
		<textarea name="chto" id="" cols="30" rows="10"></textarea>
	</div>
	<div class="form-controll">
		<p>На что</p>
		<textarea name="na" id="" cols="30" rows="10"></textarea>
	</div>
	<div class="form-controll">
		<p>Выберите домен</p>
		<select name="domain" class="beatu-select">
			<option value="">--для всех--</option>
			<?= $domains; ?>
		</select>
	</div>
	<br>
	<div class="form-controll">
		<button type="submit">Добавить</button>
	</div>
	<div class="replacelists">
<?php 
if ($replace_list) {
?>

		<table>
			<thead>
				<tr>
					<td>Найти</td>
					<td>Заменить</td>
					<td>Домен</td>
					<td>Удалить</td>
				</tr>
			</thead>
			<tbody>
				<?= $replace_list; ?>
			</tbody>
		</table>
<?php 
} else {
	echo '<p class="alert error">Список замен отсутсвует</p>';
}

?>
	</div>
</form>

<?php 
endif;
?>










<?php 
if (isset($perm_conf['mega'])):

	$get_replace_list = $db->query("SELECT * FROM `replace_h` WHERE `user_id` = {$user_id} AND `site_name` = 'mega'")->fetch_all(true);
	$replace_list = '';
	if (count($get_replace_list)) {
		foreach ($get_replace_list as  $value) {
			$replace_list .= "<tr>
					<td aria-label='Найти'><textarea  readonly>{$value['chto']}</textarea></td>
					<td aria-label='Заменить'><textarea readonly>{$value['na']}</textarea></td>
					<td aria-label='Домен'>{$value['domain']}</td>
					<td aria-label='Удалить'><a href=\"/?page=replace&delete={$value['id']}\">Удалить</a></td>
				</tr>";
		}
	}
	

	$get_domain_list = $db->query("SELECT * FROM `domains` WHERE `user_id` = {$user_id} AND `site_name` = 'mega'")->fetch_all(true);
	$domains = '';
	if (count($get_domain_list)) {
		foreach ($get_domain_list as $key => $value) {
			$domains .= "<option value='{$value['domain']}'>{$value['domain']}</option>";
		}
	}

?>


<form action="/?page=replace" method="post">
	<input type="hidden" name="site-name" value="mega">
	<div class="form-controll">
		<p>Что</p>
		<textarea name="chto" id="" cols="30" rows="10"></textarea>
	</div>
	<div class="form-controll">
		<p>На что</p>
		<textarea name="na" id="" cols="30" rows="10"></textarea>
	</div>
	<div class="form-controll">
		<p>Выберите домен</p>
		<select name="domain" class="beatu-select">
			<option value="">--для всех--</option>
			<?= $domains; ?>
		</select>
	</div>
	<br>
	<div class="form-controll">
		<button type="submit">Добавить</button>
	</div>
	<div class="replacelists">
<?php 
if ($replace_list) {
?>

		<table>
			<thead>
				<tr>
					<td>Найти</td>
					<td>Заменить</td>
					<td>Домен</td>
					<td>Удалить</td>
				</tr>
			</thead>
			<tbody>
				<?= $replace_list; ?>
			</tbody>
		</table>
<?php 
} else {
	echo '<p class="alert error">Список замен отсутсвует</p>';
}

?>
	</div>
</form>

<?php 
endif;
?>











<?php 
if (isset($perm_conf['kraken'])):

	$get_replace_list = $db->query("SELECT * FROM `replace_h` WHERE `user_id` = {$user_id} AND `site_name` = 'kraken'")->fetch_all(true);
	$replace_list = '';
	if (count($get_replace_list)) {
		foreach ($get_replace_list as  $value) {
			$replace_list .= "<tr>
					<td aria-label='Найти'><textarea  readonly>{$value['chto']}</textarea></td>
					<td aria-label='Заменить'><textarea readonly>{$value['na']}</textarea></td>
					<td aria-label='Домен'>{$value['domain']}</td>
					<td aria-label='Удалить'><a href=\"/?page=replace&delete={$value['id']}\">Удалить</a></td>
				</tr>";
		}
	}
	

	$get_domain_list = $db->query("SELECT * FROM `domains` WHERE `user_id` = {$user_id} AND `site_name` = 'kraken'")->fetch_all(true);
	$domains = '';
	if (count($get_domain_list)) {
		foreach ($get_domain_list as $key => $value) {
			$domains .= "<option value='{$value['domain']}'>{$value['domain']}</option>";
		}
	}

?>


<form action="/?page=replace" method="post">
	<input type="hidden" name="site-name" value="kraken">
	<div class="form-controll">
		<p>Что</p>
		<textarea name="chto" id="" cols="30" rows="10"></textarea>
	</div>
	<div class="form-controll">
		<p>На что</p>
		<textarea name="na" id="" cols="30" rows="10"></textarea>
	</div>
	<div class="form-controll">
		<p>Выберите домен</p>
		<select name="domain" class="beatu-select">
			<option value="">--для всех--</option>
			<?= $domains; ?>
		</select>
	</div>
	<br>
	<div class="form-controll">
		<button type="submit">Добавить</button>
	</div>
	<div class="replacelists">
<?php 
if ($replace_list) {
?>

		<table>
			<thead>
				<tr>
					<td>Найти</td>
					<td>Заменить</td>
					<td>Домен</td>
					<td>Удалить</td>
				</tr>
			</thead>
			<tbody>
				<?= $replace_list; ?>
			</tbody>
		</table>
<?php 
} else {
	echo '<p class="alert error">Список замен отсутсвует</p>';
}

?>
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