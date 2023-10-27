<?php 
	$text = '';	
	if (isset($_COOKIE['auth'])) {
		if (logincheck()) {
			header("Location: /");
			exit;
		} else {
			setcookie('auth', null, -1, '/');
		}

	} elseif (isset($_POST['password'])) {
		if ($setcookie = userauth($_POST['password'], $_POST['login'])) {
			setcookie('auth', $setcookie, strtotime("+1 year"), "/");
			header("Location: /");
			exit;
		} else {
			$text = '<p class="error">Не верный логин или пароль</p>';
		}
	}



?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css"
 integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
</head>
<body>

<style>
* {
			font-family: 'Rubik', sans-serif;
			padding: 0;
			margin: 0;
			box-sizing: border-box;
}
body {
	display: flex;
	align-items: center;
	justify-content: center;
	height: 100vh;
}
form {
	width: 100%;
	max-width: 500px;
    margin: auto;
    padding: 40px;
    border-radius: 20px;
}
input {
    width: 100%;
    height: 40px;
    border: 1.5px solid #f2f2f2;
    border-radius: 4px;
    margin-top: 10px;
    margin-bottom: 25px;
    padding: 0 20px;
    transition: border-color 0.4s,box-shadow 0.4s;
    background: #f2f2f2;
}


input:focus {
	outline: 0;
    border-color: #fe5732;
}

h2 {
	    font-weight: 400;
    margin-bottom: 30px;
}
button {
	    display: block;
    width: 100%;
    height: 40px;
    background: #fe5732;
    color: #fff;
    font-size: 17px;
    border: 0;
    outline: 0;
    border-radius: 5px;
    cursor: pointer;
    transition: box-shadow 0.4s;
}
button:hover {
	box-shadow: 0px 0px 20px 0px #fe573285;
}
.error {
	margin-top: 10px;
    text-align: center;
    color: #ff3333;
}
h2 svg {
	    width: 25px;
    height: 25px;
}
h2 {
	display: flex;
	align-items: center;
	gap: 5px;
}
</style>

	<form action="" method="post">
		<h2><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="48px" height="48px">
		    <path fill="#fe5732" d="M2.141 34l3.771 6.519.001.001C6.656 41.991 8.18 43 9.94 43l.003 0 0 0h25.03l-5.194-9H2.141zM45.859 34.341c0-.872-.257-1.683-.697-2.364L30.977 7.319C30.245 5.94 28.794 5 27.124 5h-7.496l21.91 37.962 3.454-5.982C45.673 35.835 45.859 35.328 45.859 34.341zM25.838 28L16.045 11.038 6.252 28z"></path>
		  </svg>Panel</h2>
		<div class="form-controll">
			<p>Логин</p>
			<input type="text" name="login">
		</div>
		<div class="form-controll">
			<p>Пароль</p>
			<input type="password" name="password">
		</div>
		<button title="submit">Войти <i class="fas fa-sign-in-alt"></i></button>
		<?= $text; ?>
	</form>

</body>
</html>