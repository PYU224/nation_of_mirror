<?php
require("passcheck.php");
$method = "POST";
#��x�ɕ\������
$thread = 50;
$res = 30;
$comment = '';
$stopper_array = array('(�P�[�P)��د','(�M��֥�L) �����','�i�L�E�ցE�M�jӷ�');
if (!is_dir("../$_REQUEST[bbs]")) disperror("�d�q�q�n�q�I", "����Ȕ�or�X���b�h�Ȃ��ł��B");
#====================================================
#�@�������̎擾�i�ݒ�t�@�C���j
#====================================================
#�ݒ�t�@�C����ǂ�
$set_pass = "../$_REQUEST[bbs]/SETTING.TXT";
if (is_file($set_pass)) {
	$set_str = file($set_pass);
	foreach ($set_str as $tmp){
		$tmp = chop($tmp);
		list ($name, $value) = explode("=", $tmp);
		$SETTING[$name] = $value;
	}
}
else disperror("�d�q�q�n�q�I","�d�q�q�n�q�F���[�U�[�ݒ肪�������Ă��܂��I");
require("../$_REQUEST[bbs]/config.php");
if (!empty($_POST['key'])) {
	$fp  = fopen("../$_POST[bbs]/threadconf.cgi", "r");
	while ($vip = fgetcsv($fp, 1024)) {
		if ($vip[0] == $_POST['key']) break;
		else $vip[0] = 0;
	}
	fclose($fp);
}
#==================================================
#�@�t�@�C������i�T�u�W�F�N�g�t�@�C���ǂݍ��݁j
#==================================================
#�T�u�W�F�N�g�t�@�C���X�V
#�T�u�W�F�N�g�t�@�C����ǂݍ���
$subfile = "../$_REQUEST[bbs]/subject.txt";
$SUBJECTLIST = @file($subfile);
#�T�u�W�F�N�g���e���n�b�V���Ɋi�[
$PAGEFILE = array();
foreach($SUBJECTLIST as $tmp){
	$tmp = rtrim($tmp);
	list($file, $value) = explode("<>", $tmp);
	$filename = "../$_REQUEST[bbs]/dat/$file";
	if(is_file($filename)){
		#dat�����݂���ꍇ�̂ݍŌ�ɒǉ�
		$file = str_replace('.dat', '', $file);;
		array_push($PAGEFILE,$file);
		$SUBJECT[$file] = $value;
	}
}
#==================================================
#�@���X���ځ[��
#==================================================
if (isset($_POST['mode']) and $_POST['mode'] == "res_del" and isset($_POST['del'])) {
	if (!is_file("../$_POST[bbs]/dat/$_POST[key].dat")) disperror("�d�q�q�n�q�I", "����Ȕ�or�X���b�h�Ȃ��ł��B");
	$datafile = "../$_POST[bbs]/dat/$_POST[key].dat";
	if (!is_writable($datafile)) disperror("�d�q�q�n�q�I", "���̃X���b�h�ɂ͏������߂܂���I");
	$temp = file($datafile);
	$num = count($temp);
	function res_num($num, $del) {
		if (strstr($num, '-')) {
			list($num1, $num2) = explode('-', $num);
			if ($num1 > $del) $num1--;
			if ($num2 > $del) $num2--;
			return $num1.'-'.$num2;
		}
		else return ($num <= $del) ? $num : --$num;
	}
	foreach ($_POST['del'] as $del) {
		$imgnum = sprintf("%04d", $del);
		$del--;
		if (!$_POST['mes']) $line = '';
		else $line = "$_POST[mes]<>$_POST[mes]<>$_POST[mes]<>$_POST[mes]<>$_POST[mes]\n";
		$temp[$del] = $line;
		if (is_file("../$_POST[bbs]/img/$_POST[key]$imgnum.jpg")) {
			unlink("../$_POST[bbs]/img/$_POST[key]$imgnum.jpg");
			@unlink("../$_POST[bbs]/img2/$_POST[key]$imgnum.jpg");
		}
		elseif (is_file("../$_POST[bbs]/img/$_POST[key]$imgnum.gif")) {
			unlink("../$_POST[bbs]/img/$_POST[key]$imgnum.gif");
			@unlink("../$_POST[bbs]/img2/$_POST[key]$imgnum.jpg");
		}
		elseif (is_file("../$_POST[bbs]/img/$_POST[key]$imgnum.png")) {
			unlink("../$_POST[bbs]/img/$_POST[key]$imgnum.png");
			@unlink("../$_POST[bbs]/img2/$_POST[key]$imgnum.jpg");
		}
		if (!$_POST['mes']) {
			foreach ($temp as $key=>$line) {
				$line = preg_replace("!<a href=\"\.\./test/read\.php/$_POST[bbs]/$_POST[key]/([\d|\-]+)\" target=\"_blank\">&gt;&gt;([\d|\-]+)</a>!e", "'<a href=\"../test/read.php/$_POST[bbs]/$_POST[key]/'.res_num('$1',$del).'\" target=\"_blank\">&gt;&gt;'.res_num('$2',$del).'</a>'", $line);
				$temp[$key] = $line;
			}
		}
	}
	$fp = fopen($datafile, "w");
	foreach ($temp as $tmp) fputs($fp, $tmp);
	fclose($fp);
	require '../test/make_work.php';
	$sub_txt = MakeWorkFile($_POST['bbs'], $_POST['key']);
	if (!$_POST['mes']) {
		$fp = fopen("../$_POST[bbs]/subject.txt", "w");
		foreach ($PAGEFILE as $line) {
			if ($line == $_POST['key']) {
				$SUBJECT[$line] = $sub_txt;
			}
			fputs($fp, "$line.dat<>$SUBJECT[$line]\n");
		}
		fclose($fp);
	}
	$comment = "���X���ځ[�񂵂܂����B���j���[��<b>index.html����蒼��</b>���N���b�N���Ă��������B<br>";
}
#==================================================
#�@���X�\��
#==================================================
if(isset($_REQUEST['mode']) and ($_REQUEST['mode'] == "view" or $_REQUEST['mode'] == "res_del")) {
	if (!is_file("../$_REQUEST[bbs]/dat/$_REQUEST[key].dat")) disperror("�d�q�q�n�q�I", "����Ȕ�or�X���b�h�Ȃ��ł��B");
	$datafile = "../$_REQUEST[bbs]/dat/$_REQUEST[key].dat";
	$temp = file($datafile);
	$num = count($temp);
	if (!isset($_GET['page']) or !$_GET['page']) $_GET['page'] = 1;
	$st = ($_GET['page'] - 1) * $res;
	$total_page = (int)(($num+$res-1)/$res);
	list($name,$mail,$date,$message,$subject) = explode("<>",$temp[0]);
	$subject = trim($subject);
	?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
<title>���ځ[��</title>
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>���ځ[��</h3>
<hr>
�X���b�h �F <a class="item" href="../test/read.php/<?=$_REQUEST['bbs']?>/<?=$_REQUEST['key']?>/">#<?=$_REQUEST['bbs'].$_REQUEST['key']?></a><br>
�^�C�g�� �F <font color="red"><b><?=$subject?></b></font><br>
<br>
<font color="red"><?=$comment?></font>
<font size="-1">�폜���b�Z�[�W�͕ύX�ł��܂��B�󗓂ɂ���Ɠ����폜�ɂȂ背�X���̂��̂��폜����܂��B<br>
���̏ꍇ�A����X���b�h���̃��X�A���J�[�����N�͏C������܂���������̃����N���������ꍇ���X�ԍ�������邱�Ƃ�����܂��B<br>
�܂��摜���e���ɕs����N����\��������܂��̂ŁA�摜���e���̏ꍇ�͏o���邾���ʏ�폜���������߂��܂��B<br>
�폜���b�Z�[�W�̋K��l��<b>�Ǘ����j���[</b>��<b>�ݒ�ύX</b>�ŕύX�ł��܂��B<br></font>
<br>
<table border="0" cellspacing="0" cellpadding="0"><tr><td>
<form action="<?=$_SERVER['PHP_SELF']?>" method="<?=$method?>">
<input type=hidden name="bbs" value="<?=$_REQUEST['bbs']?>">
<input type=hidden name="key" value="<?=$_REQUEST['key']?>">
<input type=hidden name="mode" value="res_del">
�폜���b�Z�[�W�@�F�@<input type=text name="mes" value="<?=$SETTING['BBS_DELETE_NAME']?>">
<input type=submit value="���s"></td></tr>
</table>
<?
	echo "page�F$_GET[page]<br>\n";
	for ($i = 1; $i <= $total_page; $i++) {
		if ($i == $_GET['page']) echo " $i \n";
		else echo " <a class=\"item\" href=\"$_SERVER[PHP_SELF]?bbs=$_REQUEST[bbs]&amp;key=$_REQUEST[key]&amp;mode=view&amp;page=$i\">$i</a> \n";
	}
	?>
<table border="1" cellspacing="0" cellpadding="2">
<tr><td>�@</td><td>�ԍ�</td><td>���O</td><td>���e��</td><td>���e</td><td>�摜</td></tr>
<?php
	$n = $st;
	for ($i = $st; $i < $st + $res; $i++) {
		if (!isset($temp[$i])) break;
		$tmp = $temp[$i];
		list($name,$mail,$date,$message,$subject) = explode("<>", $tmp);
		$date = preg_replace("/ ID:(.+)$/", "", $date);
		preg_match("|<a href=\"(.+)\"><img src=\"(.+)\" width=\"(\d+)\" height=\"(\d+)\"|", $message, $match);
		if ($match) {
			$image = '<a href="'.$match[1].'"><img src="'.$match[2].'" width="'.$match[3].'" height="'.$match[4].'">';
		}
		else $image = '�摜�Ȃ�';
		$message = strip_tags($message);
		$message = substr($message,0,30);
		$n++;
		echo "<tr><td><input type=\"checkbox\" name=\"del[]\" value=\"$n\"></td><td align=\"center\">$n</td><td><font color=\"$SETTING[BBS_NAME_COLOR]\"><b>$name</b></font>[$mail]</td><td>$date</td><td>$message </td><td>$image</td></tr>\n";
	}
	echo "</table></form>\n";
	for ($i = 1; $i <= $total_page; $i++) {
		if ($i == $_GET['page']) echo " $i \n";
		else echo " <a class=\"item\" href=\"$_SERVER[PHP_SELF]?bbs=$_REQUEST[bbs]&amp;key=$_REQUEST[key]&amp;mode=view&amp;page=$i\">$i</a> \n";
	}
	echo "</body></html>";
	exit;
}
#==================================================
#�@�X���b�h�X�g�b�p�[
#==================================================
elseif (isset($_POST['mode']) and $_POST['mode'] == "stop") {
	if (!is_file("../$_POST[bbs]/dat/$_POST[key].dat")) disperror("�d�q�q�n�q�I", "����Ȕ�or�X���b�h�Ȃ��ł��B");
	$dattemp = "../$_POST[bbs]/dat/$_POST[key].dat";
	$workfile = "../$_POST[bbs]/html/$_POST[key].html";
	if (!is_writable($dattemp)) disperror("�d�q�q�n�q�I", "���łɏ������ݏo���܂���B");
	else {
		$fp = fopen($dattemp, "a");
		$stopper = $stopper_array[$_POST['stopper']];
		fputs($fp, "��~���܂����B�B�B<>��~<>��~<>�^�E�X���b�h�X�g�b�p�[�B�B�B$stopper<>\n");
		fclose($fp);
		chmod($dattemp, 0444);
		require '../test/make_work.php';
		MakeWorkFile($_POST['bbs'], $_POST['key']);
		$comment = "�X���b�h�X�g�b�v���܂����B���j���[��<b>index.html����蒼��</b>���N���b�N���Ă��������B";
	}
}
#==================================================
#�@
#==================================================
if (!isset($_GET['page']) or !$_GET['page']) $_GET['page'] = 1;
$st = ($_GET['page'] - 1) * $thread;
$total = count($PAGEFILE)+$thread-1;
$total_page = (int)($total/$thread);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
<title>�X���b�h�X�g�b�v</title>
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>�X���b�h�X�g�b�v</h3>
<hr>
<font color="red"><?=$comment?></font><br>
�X���b�h�X�g�b�p�[��<b>�X�g�b�v</b>�{�^���������Ƃ��̃X���b�h�͏������߂Ȃ��Ȃ�܂��B<br>
���X���폜�������Ƃ��́A<b>���X�\��</b>�{�^���������č폜������ʂɈړ����Ă��������B<br>
<br>
page�F<?=$_GET['page']?><br>
<?php
for ($i = 1; $i <= $total_page; $i++) {
	if ($i == $_GET['page']) echo " $i \n";
	else echo " <a class=\"item\" href=\"$_SERVER[PHP_SELF]?bbs=$_REQUEST[bbs]&amp;page=$i\">$i</a> \n";
}
?>
<table border="1" cellspacing="0" cellpadding="2">
<tr><th>�X���b�h�L�[</th><th>�^�C�g���i���X���j</th><th>�X���b�h�X�g�b�p�[</th><th>�@</th></tr>
<?php
for ($i = $st; $i < $st+$thread; $i++) {
	if (!isset($PAGEFILE[$i])) break;
	$tmp = $PAGEFILE[$i];
	?><tr><td> <a class="item" href="../test/read.php/<?=$_REQUEST['bbs']."/".$tmp?>/l50">#<?=$_REQUEST['bbs'].$tmp?></a> </td><td><?=$SUBJECT[$tmp]?></td>
<td>
<?php
clearstatcache();
if (is_writable("../$_REQUEST[bbs]/dat/$tmp.dat")) {
?> <form action="<?=$_SERVER['PHP_SELF']?>" method="<?=$method?>">
 <select name="stopper">
<? foreach($stopper_array as $key=>$stopper) echo ' <option value="'.$key.'">'.$stopper."\n";?>
 </select>��
 <input type="submit" value="�X�g�b�v">
 <input type="hidden" name="bbs" value="<?=$_REQUEST['bbs']?>">
 <input type="hidden" name="key" value="<?=$tmp?>">
 <input type="hidden" name="mode" value="stop">
 </form>
<?php
}
else echo "<s>�X�g�b�v</s>";
?></td>
<td>
 <form action="<?=$_SERVER['PHP_SELF']?>" method="<?=$method?>">
 <input type="submit" value="���X�\��">
 <input type="hidden" name="bbs" value="<?=$_REQUEST['bbs']?>">
 <input type="hidden" name="key" value="<?=$tmp?>">
 <input type="hidden" name="mode" value="view">
 </form>
</td>
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
