<?php
require("passcheck.php");
if (!is_dir("../$_GET[bbs]")) disperror("�d�q�q�n�q�I", "����Ȕ�or�X���b�h�Ȃ��ł��B");
#====================================================
#�@�������̎擾�i�ݒ�t�@�C���j
#====================================================
#�ݒ�t�@�C����ǂ�
$set_pass = "../$_GET[bbs]/SETTING.TXT";
if (is_file($set_pass)) {
	$set_str = file($set_pass);
	foreach ($set_str as $tmp){
		$tmp = trim($tmp);
		list($name, $value) = explode("=", $tmp);
		$SETTING[$name] = $value;
	}
}
else disperror("�d�q�q�n�q�I","�d�q�q�n�q�F���[�U�[�ݒ肪�������Ă��܂��I");
#==================================================
#�@�A�N�Z�X����
#==================================================
if(isset($_GET['mode']) and $_GET['mode'] == "deny") {
	$_GET['list'] = str_replace("�@", " ", $_GET['list']);
	$_GET['list'] = preg_replace("/\s+/", " ", $_GET['list']);
	$_GET['list'] = trim($_GET['list']);
	$deny_array = explode(' ', $_GET['list']);
	$fp = fopen("../$_GET[bbs]/uerror.cgi", "a");
	foreach ($deny_array as $deny) {
		if ($deny) fputs($fp, $deny."\n");
	}
	fclose($fp);
}
#�A�N�Z�X�������X�g�ǂݍ���
if (is_file("../$_GET[bbs]/uerror.cgi")) $deny_array = file("../$_GET[bbs]/uerror.cgi");
else $deny_array = array();
$deny_array = array_map("trim", $deny_array);
#==================================================
#�@�A�N�Z�X����
#==================================================
if(isset($_GET['mode']) and $_GET['mode'] == "allow") {
	if (!isset($_GET['allow'])) $_GET['allow'] = array();
	$deny_array = array_diff($deny_array, $_GET['allow']);
	$fp = fopen("../$_GET[bbs]/uerror.cgi", "w");
	foreach ($deny_array as $deny) fputs($fp, $deny."\n");
	fclose($fp);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
<title>�A�N�֏���</title>
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>�A�N�֏���</h3>
<hr>
<font size="-1">�A�N�Z�X�֎~����z�X�g���܂���IP�A�h���X�i�S���܂��͈ꕔ�j���L�����Ă��������B<br>
�����ݒ肷��ꍇ�̓X�y�[�X�ŋ�؂��Ă�������<br>
��F�@<b>127.0.0.1�@201.105�@.go.jp�@YahooBB123456789</b><br></font>
<form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method="GET">
<input type="hidden" name="bbs" value="<?=$_GET['bbs']?>">
<input type="hidden" name="mode" value="deny">
<input type="text" name="list" size="50">
<input type="submit" value="�A�N��">
</form>
<hr>
�A�N�Z�X�֎~�������������ꍇ�̓`�F�b�N�{�b�N�X�Ƀ`�F�b�N����<b>����</b>�{�^���������Ă��������B
<form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method="GET">
<input type="hidden" name="bbs" value="<?=$_GET['bbs']?>">
<input type="hidden" name="mode" value="allow">
<input type="submit" value="����">
<table border="1" cellspacing="0" cellpadding="2">
<?
foreach ($deny_array as $deny) {
	echo '<tr><td><input type="checkbox" name="allow[]" value="'.$deny.'"></td><td>'.$deny."</td></tr>\n";
}
?>
</table>
</form>
</body>
</html>
