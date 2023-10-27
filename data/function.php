<?php 
function Pagination ($db, $sql, $num, $pageformat, $page){

	$result = $db->query("SELECT COUNT(*) ". $sql)->fetch_row();
	$total = intval(($result[0] - 1) / $num) + 1;
	$page = intval($page);
	if(empty($page) or $page < 0) $page = 1;
	if($page > $total) $page = $total;
	$start = $page * $num - $num;
	$getinfo = $db->query("SELECT * " .$sql. " LIMIT $start, $num");
	$info = $getinfo->fetch_all(MYSQLI_ASSOC);


	if ($result[0] <= $num) {
		$pagination = '';	
	} else {
		preg_match('/(?<a>.*?){pagenum}(?<b>.*?)/', $pageformat, $pg);
		if ($page != 1) $pervpage = "<a href={$pg['a']}1{$pg['b']}><<</a><a href={$pg['a']}".($page - 1)." {$pg['b']}><</a>";
		if ($page != $total) $nextpage = ' <a href='.$pg['a']. ($page + 1) .$pg['b'].'>></a><a href='.$pg['a'] .$total. $pg['b'].'>>></a>';
		if($page - 2 > 0) $page2left = ' <a href='.$pg['a']. ($page - 2).$pg['b'].'>'. ($page - 2) .'</a> ';
		if($page - 1 > 0) $page1left = '<a href='.$pg['a']. ($page - 1).$pg['b'].'>'. ($page - 1) .'</a> ';
		if($page + 2 <= $total) $page2right = '  <a href='.$pg['a']. ($page + 2).$pg['b'] .'>'. ($page + 2).'</a>';
		if($page + 1 <= $total) $page1right = '  <a href='.$pg['a']. ($page + 1).$pg['b'].'>'. ($page + 1).'</a>';
		$pagination =  $pervpage.$page2left.$page1left.'<a class="active" href="#">'.$page.'</a>'.$page1right.$page2right.$nextpage;	
	}



	$result = array('pagination' => $pagination,'result' => $info);

	return $result;
}

function logincheck(){
	global $db;
	if (empty($_COOKIE['auth'])) return false;
	$hash = $db->real_escape_string($_COOKIE['auth']);

	$check_hash = $db->query("SELECT * FROM `auth` WHERE `user_hash` LIKE '{$hash}'")->fetch_assoc();
	if (isset($check_hash['id'])) {
		$check_last = $db->query("SELECT * FROM `auth` WHERE `user_id` = {$check_hash['user_id']} ORDER BY `id` DESC LIMIT 1")->fetch_assoc();
		if ($check_last['id'] == $check_hash['id'] /* && $_SERVER['REMOTE_ADDR'] == $check_hash['user_ip']*/) {
			return $check_hash['user_id'];
		} else {
			return false;
		}
	} else {
		return false;
	}

}
function userauth($password, $login){
	global $db;
	$date = date('Y-m-d h:m:s');
	$login = $db->real_escape_string($login);
	$check_user = $db->query("SELECT * FROM `users` WHERE `login` LIKE '{$login}'")->fetch_assoc();
	if (isset($check_user['login'])) {
		if (password_verify($password, $check_user['password'])) {
			$user_ip = $db->real_escape_string($_SERVER['REMOTE_ADDR']);
			$user_hash = md5( password_hash($user_ip, PASSWORD_DEFAULT) );
			$db->query("INSERT INTO `auth` (`id`, `user_id`, `user_ip`, `user_hash`, `date`) VALUES (NULL, '{$check_user['id']}', '{$user_ip}', '{$user_hash}', '{$date}');");
			return $user_hash;
		} else {
			return false;
		}

	} else {
		return false;
	}
}



function useraddd(){
	global $db;	

	$login = $db->real_escape_string($_POST['login']);
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$date = date('Y-m-d');
	$nepost = $_POST;
	unset($nepost['login'], $nepost['password']);
	$perm = $db->real_escape_string(json_encode($nepost));
	$user_add_sql = $db->query("INSERT INTO `users` (`id`, `login`, `password`, `perm`, `status`, `date`) VALUES (NULL, '{$login}','{$password}', '{$perm}', '2', '{$date}')");
	$insert_id = $db->insert_id;

	if ($insert_id) {
		return true;
	} else {
		return false;
	}
	
}

function logout() {
	setcookie('auth', null, -1, '/');
	header("Location: /?page=login");
	exit;
}

function checkbox($config){
	if ($config) return 'checked';
	return '';
	
}

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); 
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass);
}