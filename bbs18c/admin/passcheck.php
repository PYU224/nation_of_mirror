<?php
#=====================================
#�@�p�X���[�h�ݒ�
#=====================================
#�G���[��ʁi�G���[�����j
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
<title>�p�X���[�h�F��</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<br><br>
<div align="center">
�Ǘ��p�X���[�h����͂��Ă��������B<br>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<input type="password" name="adminpass" >
<input type="submit" value="���M">
</form>
</div>
</body></html>
<?php
		exit;
	}
	// https://tadtadya.com/php-use-password-hash-function/
	if ($_COOKIE['adminpass']) {
		if (!password_verify($_COOKIE['adminpass'], $admin)) {
			disperror("�d�q�q�n�q�I", "�p�X���[�h���Ⴂ�܂�");
			exit;
		}
	}
	if (!$_COOKIE['adminpass'] and $_POST['adminpass']) {
		if (!password_verify($_POST['adminpass'], $admin)) {
			disperror("�d�q�q�n�q�I", "�p�X���[�h���Ⴂ�܂�");
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
<title>�p�X���[�h�ݒ�</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<br><br>
<div align="center">
�p�X���[�h���o�^����Ă��܂���B<br>
�V�����p�X���[�h����͂��Ă��������B<br>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<input type="password" name="setpass" >
<input type="submit" value="�o�^">
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