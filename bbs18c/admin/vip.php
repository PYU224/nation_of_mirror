<?php
$thread=20;
require("passcheck.php");
$comment = '<br>';
#====================================================
#�@�������̎擾�i�ݒ�t�@�C���j
#====================================================
#�ݒ�t�@�C����ǂ�
$set_pass = "../$_REQUEST[bbs]/SETTING.TXT";
if (is_file($set_pass)) {
	$set_str = file ($set_pass);
	foreach ($set_str as $tmp){
		$tmp = chop($tmp);
		list ($name, $value) = explode("=", $tmp);
		$SETTING[$name] = $value;
	}
}
else disperror("�d�q�q�n�q�I","�d�q�q�n�q�F���[�U�[�ݒ肪�������Ă��܂��I");
$threadconf = array();
$fp = fopen("../$_REQUEST[bbs]/threadconf.cgi", "r");
while ($list = fgetcsv($fp, 1024)) {
	$threadconf[$list[0]] = $list;
}
#==================================================
#�@�t�@�C������i�T�u�W�F�N�g�t�@�C���ǂݍ��݁j
#==================================================
#�T�u�W�F�N�g�t�@�C����ǂݍ���
$subfile = "../$_REQUEST[bbs]/subject.txt";
#�T�u�W�F�N�g�t�@�C����ǂݍ���
$SUBJECTLIST = @file($subfile);
#�T�u�W�F�N�g���e���n�b�V���Ɋi�[
$PAGEFILE = array();
if ($SUBJECTLIST) {
	foreach ($SUBJECTLIST as $tmp) {
		$tmp = rtrim($tmp);
		list($file, $value) = explode("<>", $tmp);
		$filename = "../$_REQUEST[bbs]/dat/$file";
		if (is_file($filename)) {
			#dat�����݂���ꍇ�̂ݍŌ�ɒǉ�
			preg_match("/(\d+)/", $file, $match);
			$file = $match[1];
			array_push($PAGEFILE,$file);
			$SUBJECT[$file] = $value;
		}
	}
}
#==================================================
#�@�ݒ�ύX
#==================================================
if (isset($_POST['mode']) and $_POST['mode'] == "set") {
	$target = "../$_POST[bbs]/dat/$_POST[key].dat";
	if (!is_file($target)) disperror("�d�q�q�n�q�I", "����Ȕ�or�X���b�h�Ȃ��ł��B");
	if (!isset($_POST['name_774'])) $_POST['name_774'] = '';
	if (!isset($_POST['force_774'])) $_POST['force_774'] = '';
	if (!isset($_POST['no_id'])) $_POST['no_id'] = 0;
	if (!isset($_POST['sage'])) $_POST['sage'] = 0;
	if (!isset($_POST['stars'])) $_POST['stars'] = 0;
	if (!isset($_POST['normal'])) $_POST['normal'] = 0;
	if (!isset($_POST['name'])) $_POST['name'] = 0;
	if (!isset($_POST['zerothello'])) $_POST['zerothello'] = 0;
	if (!isset($_POST['up'])) $_POST['up'] = 0;
	if (!isset($_POST['maxmsg'])) $_POST['maxmsg'] = '';
	$threadconf[$_POST['key']][0] = $_POST['key'];
	$threadconf[$_POST['key']][1] = $_POST['name_774'];
	$threadconf[$_POST['key']][2] = $_POST['force_774'];
	$threadconf[$_POST['key']][3] = $_POST['no_id'];
	$threadconf[$_POST['key']][4] = $_POST['sage'];
	$threadconf[$_POST['key']][5] = $_POST['stars'];
	$threadconf[$_POST['key']][6] = $_POST['normal'];
	$threadconf[$_POST['key']][7] = $_POST['name'];
	$threadconf[$_POST['key']][8] = $_POST['zerothello'];
	$threadconf[$_POST['key']][9] = $_POST['up'];
	$threadconf[$_POST['key']][10] = $_POST['maxmsg'];
	$fp = fopen("../$_POST[bbs]/threadconf.cgi", "w");
	foreach($threadconf as $tmp) {
		fwrite($fp, implode(',', $tmp)."\n");
	}
	fclose($fp);
	$comment = '<font color="red">VIP�@�\��ύX���܂����B</font><br>';
}
#==================================================
#�@���j���[
#==================================================
if (!isset($_GET['page']) or !$_GET['page']) $_GET['page'] = 1;
$st = ($_GET['page'] - 1) * $thread;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
<title>VIP�@�\�ύX</title>
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>VIP�@�\�ύX</h3>
<hr>
<?=$comment?>
<br>
page�F<?=$_GET['page']?><br>
<?
$total = count($PAGEFILE) + $thread - 1;
$total_page = (int)($total/$thread);
for ($i = 1; $i <= $total_page; $i++) {
	if ($i == $_GET['page']) echo " $i \n";
	else echo " <a class=\"item\" href=\"$_SERVER[PHP_SELF]?bbs=$_REQUEST[bbs]&amp;page=$i\">$i</a> \n";
}
?>
<table border="1" cellspacing="0" cellpadding="2">
<tr><th>�X���b�h�L�[</th><th>�^�C�g��</th><th>�������ύX</th><th>����������</th><th>ID�Ȃ�</th><th>����sage</th><th>�L���b�v�̂݃��X��</th><th>VIP�@�\����</th><th>���O�K�{</th><th>�[���Z��</th><th>�A�b�v���[�h</th><th>�@</th></tr>
<?php
for ($i = $st; $i < $st+$thread; $i++) {
	if (!isset($PAGEFILE[$i])) break;
	$tmp = $PAGEFILE[$i];
	if (!isset($threadconf[$tmp])) $threadconf[$tmp] = array($tmp,'','',0,0,0,0,0,0,0);
	?>
<tr>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<td> <a class="item" href="../test/read.php/<?=$_REQUEST['bbs']."/".$tmp?>/l50">#<?=$_REQUEST['bbs'].$tmp?></a> </td>
<td><?=$SUBJECT[$tmp]?></td>
<td><input type="text" size="10" name="name_774" value="<?=$threadconf[$tmp][1]?>"></td>
<td><input type="text" size="10" name="force_774" value="<?=$threadconf[$tmp][2]?>"></td>
<td><input type="checkbox" name="no_id"<? if ($threadconf[$tmp][3]) echo " checked"; ?> value="1"></td>
<td><input type="checkbox" name="sage"<? if ($threadconf[$tmp][4]) echo " checked"; ?> value="1"></td>
<td><input type="checkbox" name="stars"<? if ($threadconf[$tmp][5]) echo " checked"; ?> value="1"></td>
<td><input type="checkbox" name="normal"<? if ($threadconf[$tmp][6]) echo " checked"; ?> value="1"></td>
<td><input type="checkbox" name="name"<? if ($threadconf[$tmp][7]) echo " checked"; ?> value="1"></td>
<td><input type="checkbox" name="zerothello"<? if ($threadconf[$tmp][8]) echo " checked"; ?> value="1"></td>
<td><input type="checkbox" name="up"<? if ($threadconf[$tmp][9]) echo " checked"; ?> value="1"></td>
<td>
 <input type="hidden" name="bbs" value="<?=$_REQUEST['bbs']?>">
 <input type="hidden" name="key" value="<?=$tmp?>">
 <input type="hidden" name="mode" value="set">
 <input type="submit" value="�ݒ�ύX">
</td>
</form>
</tr>
<?php
}
echo "</table>\n";
for ($i = 1; $i <= $total_page; $i++) {
	if ($i == $_GET['page']) echo " $i \n";
	else echo " <a class=\"item\" href=\"$_SERVER[PHP_SELF]?bbs=$_REQUEST[bbs]&amp;page=$i\">$i</a> \n";
}
echo "</body></html>";
exit;
?>