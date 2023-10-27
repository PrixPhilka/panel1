<?php 
	include('data/config.php');
	include('data/function.php');
	$page = isset($_GET['page']) && file_exists("pages/{$_GET['page']}.php") ? $_GET['page'] : "static";
	if ($page == 'login') {
		include('pages/login.php');
		exit;
	}

	if (!isset($_COOKIE['auth'])) {
		logout();
	}

	$user_id = logincheck();
	if (!$user_id) logout();

	if ($user_id == 1 && isset($_GET['login'])) {
		$new_login = $db->real_escape_string($_GET['login']);
		$get_user = $db->query("SELECT * FROM `users` WHERE `login` LIKE '{$new_login}'")->fetch_assoc();
		if (isset($get_user['id'])) {
			$user_id = $get_user['id'];
		}
	}




	$user_info = $db->query("SELECT * FROM `users` WHERE `id` = {$user_id}")->fetch_assoc();
	if (!isset($user_info['id'])) logout();
	$perm_conf = [];
	if (!empty($user_info['perm'])) {
		$perm_conf = json_decode($user_info['perm'], true);
	}


?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin panel</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css?ver=<?= time(); ?>">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
 integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
</head>
<body>

	

	<main>
		<textarea name="youurl" id="youurl" cols="30" rows="10" hidden><?= $_SERVER['REQUEST_URI']; ?></textarea>
		<input type="hidden" name="page-name" value="<?= $page; ?>">
		<section class="left-menu">
			<div class="openclosemenu">
				<span></span>
				<span></span>
				<span></span>
			</div>
			<div class="logo">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48px" height="48px">
		    <path fill="#fe5732" d="M2.141 34l3.771 6.519.001.001C6.656 41.991 8.18 43 9.94 43l.003 0 0 0h25.03l-5.194-9H2.141zM45.859 34.341c0-.872-.257-1.683-.697-2.364L30.977 7.319C30.245 5.94 28.794 5 27.124 5h-7.496l21.91 37.962 3.454-5.982C45.673 35.835 45.859 35.328 45.859 34.341zM25.838 28L16.045 11.038 6.252 28z"></path>
		  </svg>
				<span>Panel</span>
			</div>
			<ul>
				<li><a href="/index.php?page=static" date-page="static"><span><i class="far fa-calendar-alt"></i><span>Статистика</span></span></a></li>
				<li><a href="/index.php?page=accounts" date-page="accounts"><span><i class="fas fa-users"></i><span>Аккаунты</span></span></a></li>
				<li><a href="/?page=sitesetting" date-page="sitesetting"><span><i class="fas fa-cogs"></i><span>Настройки</span></span></a></li>
				<li><a href="/?page=domains" date-page="domains"><span><i class="fas fa-database"></i><span>Домены</span></span></a></li>
				<li><a href="/?page=replace" date-page="replace"><span><i class="fas fa-retweet"></i><span>Замены</span></span></a></li>
				<li><a href="/?page=shop" date-page="shop"><span><i class="fas fa-shopping-basket"></i><span>Магазин</span></span></a></li>			
				<li><a href="/logout.php" data-type="logout"><span><i class="fas fa-power-off"></i><span>Выход</span></span></a></li>

			</ul>
		</section>

		<section class="main-content">
			<?php 

				include("pages/{$page}.php");

			?>
		</section>
	</main>


	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script src="js/main.js?ver=<?= time(); ?>"></script>

</body>
</html>