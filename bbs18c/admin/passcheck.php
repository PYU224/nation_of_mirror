<?php
#=====================================
#　パスワード設定
#=====================================
#エラー画面（エラー処理）
#DispError(TITLE,TOPIC);
function disperror($title, $topic) {
	?>
<html>
<head>
<title><?=$title?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<font color="red" size="+1"><b><?=$title?></b></font><br>
<br>
<div align="left"><b><?=$topic?></b></div><br>
<br>
</body>
</html>
<?php
	exit;
}
header("Content-Type: text/html; charset=Shift_JIS");
$passfile = "passfile.cgi";
$admin_array = @file($passfile);

// https://stackoverflow.com/questions/71035322/php-deprecated-automatic-conversion-of-false-to-array-is-deprecated-adodb-mssql
if (!is_array($admin_array)) $admin_array = [];


if (!isset($admin_array[0])) $admin_array[0] = '';
$admin = rtrim($admin_array[0]);
if (!isset($_COOKIE['adminpass'])) $_COOKIE['adminpass'] = '';
if (!isset($_POST['adminpass'])) $_POST['adminpass'] = '';
if ($admin) {
	if (!$_COOKIE['adminpass'] and !$_POST['adminpass']) {
		?>
<html>
<head>
<title>パスワード認証</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<br><br>
<div align="center">
管理パスワードを入力してください。<br>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<input type="password" name="adminpass" >
<input type="submit" value="送信">
</form>
</div>
</body></html>
<?php
		exit;
	}
	// https://tadtadya.com/php-use-password-hash-function/
	if ($_COOKIE['adminpass']) {
		if (!password_verify($_COOKIE['adminpass'], $admin)) {
			disperror("ＥＲＲＯＲ！", "パスワードが違います");
			exit;
		}
	}
	if (!$_COOKIE['adminpass'] and $_POST['adminpass']) {
		if (!password_verify($_POST['adminpass'], $admin)) {
			disperror("ＥＲＲＯＲ！", "パスワードが違います");
			exit;
		}
		setcookie("adminpass",$_POST['adminpass']);
	}
}
else {
	if(!isset($_POST['setpass']) or !$_POST['setpass']) {
		?>
<html>
<head>
<title>パスワード設定</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<br><br>
<div align="center">
パスワードが登録されていません。<br>
新しいパスワードを入力してください。<br>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<input type="password" name="setpass" >
<input type="submit" value="登録">
</form>
</div>
</body></html>
<?php
		exit;
	}
	else {
		$admin = password_hash($_POST['setpass'], \PASSWORD_BCRYPT);
		$fp = @fopen($passfile, "w");
		fputs($fp, $admin);
		fclose($fp);
		setcookie("adminpass",$_POST['setpass']);
	}
}
?>