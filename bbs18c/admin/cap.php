<?php
#====================================================
#�@�L���b�v
#====================================================
require("passcheck.php");
#=============================================
$cap_file = "../test/caps.cgi";
$list = @file($cap_file);
$fll = 0;
if (!isset($_POST['name'])) $_POST['name'] = '';
if (!isset($_POST['password'])) $_POST['password'] = '';
if (!isset($_POST['passold'])) $_POST['passold'] = '';
if (get_magic_quotes_gpc()) {
	$_POST['name'] = stripslashes($_POST['name']);
	$_POST['password'] = stripslashes($_POST['password']);
}
#---------------�ǉ�
if (isset($_POST['mode']) and $_POST['mode'] == "add") {
	$fll = 1;
	if (!$_POST['name']) disperror("�d�q�q�n�q�I","���O�����͂���Ă��܂���I");
	if(!$_POST['password']) disperror("�d�q�q�n�q�I","�p�X�����͂���Ă��܂���I");
	$time = time();
	if ($list) {
		foreach ($list as $tmp) {
			$tmp = trim($tmp);
			list($id2,$name2,$pass2) = explode("<>", $tmp);
			if ($_POST['name'] == $name2) disperror("�d�q�q�n�q�I","���̖��O�͊��Ɏg�p����Ă��܂��I");
			if (crypt($_POST['password'], $pass2) == $pass2) disperror("�d�q�q�n�q�I","���̃p�X�͊��Ɏg�p����Ă��܂��I");
		}
	}
	$_POST['password'] = crypt($_POST['password']);
	$fp = @fopen($cap_file, "a");
	fputs($fp, "$time<>$_POST[name]<>$_POST[password]<>$_POST[color]\n");
	fclose($fp);
}
#---------------�ҏW
elseif (isset($_POST['mode']) and $_POST['mode'] == "�ύX"){
	$fll = 1;
	$caplist = '';
	foreach ($list as $tmp){
		list($id2,$name2,$pass2) = explode("<>", $tmp);
		if ($_POST['id'] == $id2) {
			if (crypt($_POST['passold'], $pass2) != $pass2) disperror("�d�q�q�n�q�I","�p�X���[�h���Ⴂ�܂��I");
			if ($_POST['passnew']) $pass2 = crypt($_POST['passnew']);
			$tmp="$_POST[id]<>$_POST[name]<>$pass2<>$_POST[color]\n";
		}
		$caplist .= $tmp;
	}
	$fp = fopen($cap_file, "w");
	fputs($fp, $caplist);
	fclose($fp);
}
#---------------�폜
elseif (isset($_POST['mode']) and $_POST['mode'] == "�폜"){
	$fll = 1;
	$caplist = '';
	foreach ($list as $tmp){
		list($id2,$name2,$pass2) = explode("<>", $tmp);
		if($_POST['id'] == $id2){
			if (crypt($_POST['passold'], $pass2) != $pass2) disperror("�d�q�q�n�q�I","�p�X���[�h���Ⴂ�܂��I");
			else continue;
		}
		$caplist .= $tmp;
	}
	$fp = fopen($cap_file, "w");
	fputs($fp, $caplist);
	fclose($fp);
}
#---------------����ȊO
if($fll == 1){
	header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]");
	exit;
}
#########�L���b�v�Ǘ����j���[
?>
<html>
<head>
<title>�L���b�v�Ǘ�</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<h3>�L���b�v�Ǘ�</h3>
<hr>
���e���Ƀ��[������#�ɑ����ăp�X���[�h����͂���ƁA�����œo�^�������O���L���b�v�}�[�N���t���ŕ\������܂��B<br>
�p�X���[�h��abcd�������烁�[������#abcd�ƋL�����܂��B<br>
sage#abcd�̂悤��sage�@�\�ƕ��p���o���܂��Bscript@s16.xrea.com#abcd�ƃ��[���A�h���X�������܂��B<br>
���e���ɂ͖��O���͖��L���ł��o�^�����L���b�v�����\������܂����A���O���L�����ē��e�����<b>���O���L���b�v�� ��</b>�ƕ\������܂��B<br>
�F��html�Ŏg����`�ŏ����Ă��������B#C06000�̂悤��#���L�����܂��Bred,green,blue�Ȃǂ̃u���E�U���Ή����Ă����ʓI�ȐF�����g���܂��B<br>
<hr>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<input type="hidden" name="mode" value="add">
�V�K�L���b�v�o�^<br>
���O�F<input type="text" name="name">
�p�X���[�h�F<input type="password" name="password">
�F�F<input type="text" name="color">
<input type="submit" value="�ǉ�">
</form>
<hr>
�L���b�v�ύX�폜<font size="-1">�i�o�^���̃p�X���[�h���K�v�ł��j</font><br>
<table border=1 cellspacing=2 cellpadding=3>
<tr><th>�L���b�vID</th><th>���O</th><th>�p�X���[�h�i�K�{�j</th><th>�F</th><th>�p�X���[�h�ύX</th><th>�@</th><th>�@</th></tr>
<?php
if ($list) {
	foreach ($list as $tmp) {
		$tmp = chop($tmp);
		list($id2,$name2,$pass2,$color) = explode("<>", $tmp);
?><form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<tr><td><?=$id2?></td>
<td>
<input type="text" name="name" value="<?=$name2?>">
</td>
<td>
<input type="password" name="passold" value="">
<input type="hidden" name="id" value="<?=$id2?>">
</td>
<td>
<input type="text" name="color" value="<?=$color?>">
</td>
<td>
<input type="password" name="passnew" value="">
</td>
<td>
<input type="submit" name="mode" value="�ύX">
</td>
<td>
<input type="submit" name="mode" value="�폜">
</td>
</tr>
</form>
<?php
	}
}
?></table>
<hr>
</body></html>
<?php
exit;
?>
