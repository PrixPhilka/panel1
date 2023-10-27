<?php 
	$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
	$page_num = isset($_GET['page-num']) ? intval($_GET['page-num']) : 1;


	$sql = 'FROM `statistic`';

	if (isset($_GET['date'])) {
		$date_start = $db->real_escape_string( date('Y-m-d', strtotime($_GET['date'])) );
		$date_end = $db->real_escape_string( date('Y-m-d', strtotime($_GET['date-od'])) );

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



	if (isset($_GET['auth']) && !empty($_GET['auth'])) {
		if ($_GET['auth'] == 'DESC') {
			$order['auth'] = '`auth` DESC';
			$auth_i = '<i class="fas fa-chevron-down"></i>';
			$auth_input = '<input type="hidden" name="auth" value="DESC">';
		} else {
			$order['auth'] = '`auth` ASC';
			$auth_i = '<i class="fas fa-chevron-up"></i>';
			$auth_input = '<input type="hidden" name="auth" value="ASC">';
		}
	}

	if (isset($_GET['goodauth']) && !empty($_GET['goodauth'])) {
		if ($_GET['goodauth'] == 'DESC') {
			$order['goodauth'] = '`goodauth` DESC';
			$goodauth_i = '<i class="fas fa-chevron-down"></i>';
			$goodauth_input = '<input type="hidden" name="goodauth" value="DESC">';
		} else {
			$order['goodauth'] = '`goodauth` ASC';
			$goodauth_i = '<i class="fas fa-chevron-up"></i>';
			$goodauth_input = '<input type="hidden" name="goodauth" value="ASC">';
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


	if (!isset($_GET['date-table'])) {
		$order['date-table'] = '`date` DESC';
	}

	if (isset($where)) {
		$where .= "AND `user_id` = {$user_id} ";
	} else {
		$where .= "WHERE `user_id` = {$user_id} ";
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
			$tr .= "<tr>
						<td aria-label='Дата'>{$value['date']}</td>
						<td aria-label='Авторизаций'>{$value['auth']}</td>
						<td aria-label='Успешных авторизаций'>{$value['goodauth']}</td>
						<td aria-label='Общий баланс'>{$value['balance']} BTC</td>
					</tr>";
		}
	}



?>


<h2>Статистика</h2>



	<form action="/?page=static" method="get" id="statisticform">
						
		<div class="filterblock">
				<div class="date">
					<p>Периуд:</p>
				    <div class="dateblock">
					    <?= $od_date_input; ?>
				    </div>
				</div>
				    <div class="refresh">
				    	<a href="javascript:;">Обновить</a>
				    	<a href="javascript:;">Очистить</a>
				    </div>
			</div>


			<div class="selectblock">
				<p>Отображать на странице:</p>
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

			<?= $auth_input; ?>
			<?= $goodauth_input; ?>
			<?= $balance_input; ?>
			<?= $date_input; ?>
			<input type="hidden" name="page" value="static">
			

<div class="tableblock">
				<table>
				<thead>
					<tr>
						<td data-type="date-table">Дата <span><?= $date_i; ?></span></td>
						<td data-type="auth">Авторизаций <span><?= $auth_i; ?></span></td>
						<td data-type="goodauth">Успешных авторизаций <span><?= $goodauth_i; ?></span></td>
						<td data-type="balance">Общий баланс <span><?= $balance_i; ?></span></td>
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