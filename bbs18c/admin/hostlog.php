<?php
#��x�ɕ\�����郊�X�g
$inum = 10;
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
#�@�t�@�C������i�z�X�g�t�@�C�����ǂݍ��݁j
#==================================================
$log_file = "../$_GET[bbs]/hostlog.cgi";
if (!is_file($log_file)) disperror("�d�q�q�n�q�I","�d�q�q�n�q�F$log_file ������܂���");
if (!is_writable($log_file)) disperror("�d�q�q�n�q�I","�d�q�q�n�q�F$log_file �ɏ������ݑ���������܂���");
$host_log = file($log_file);
#==================================================
#�@�A�N�Z�X����
#==================================================
if(isset($_GET['mode']) and $_GET['mode'] == "ban") {
	list(,,,,,,$ipaddr) = explode('<>', $host_log[$_GET['id']]);
	$fp = fopen("../$_GET[bbs]/uerror.cgi", "a");
	fputs($fp, $ipaddr."\n");
	fclose($fp);
}
#�A�N�Z�X�������X�g�ǂݍ���
if (is_file("../$_GET[bbs]/uerror.cgi")) $deny_array = file("../$_GET[bbs]/uerror.cgi");
else $deny_array = array();
#==================================================
#�@���O�폜
#==================================================
if(isset($_GET['mode']) and $_GET['mode'] == "log_del") {
	if (isset($_GET['del']) and $_GET['del']) {
		foreach ($_GET['del'] as $del) unset($host_log[$del]);
	}
	$fp = fopen($log_file, "w");
	foreach ($host_log as $log) fputs($fp,$log);
	fclose($fp);
	$host_log = file($log_file);
}
#==================================================
#�@���O�\��
#==================================================
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
<title>�z�X�g���O�Ǘ�</title>
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>�z�X�g���O�Ǘ�</h3>
<hr>
���O���폜����ꍇ�́A�폜���������O�̃`�F�b�N�{�b�N�X���`�F�b�N����<b>�폜</b>�{�^���������Ă��������B<br>
<b>�A�N��</b>���N���b�N����Ƃ��̓��e�҂�IP�A�h���X�����e���ۃ��X�g�ɒǉ�����܂��B<br>
�A�N�ւ���������ꍇ��IP�A�h���X���m�F���ă��j���[��<b>�A�N�֏���</b>����������Ă��������B<br>
<br>
<form name="form1" action="<?=$_SERVER['PHP_SELF']?>" method="GET">
<input type="hidden" name="bbs" value="<?=$_GET['bbs']?>">
<input type="hidden" name="mode" value="log_del">
<input type="submit" value="�폜">
<?
if (!isset($_GET['page']) or !$_GET['page']) $_GET['page'] = 1;
echo "page�F$_GET[page]<br>\n";
$st = ($_GET['page'] - 1) * $inum;
$num = count($host_log);
$total_page = (int)(($num+$inum-1)/$inum);
for ($i = 1; $i <= $total_page; $i++) {
	if ($i == $_GET['page']) echo " $i \n";
	else echo " <a class=\"item\" href=\"$_SERVER[PHP_SELF]?bbs=$_GET[bbs]&amp;page=$i\">$i</a> \n";
}
?>
<table border="1" cellspacing="0" cellpadding="2">
<?php
for ($i = $st; $i < $st + $inum; $i++) {
	if (!isset($host_log[$i])) break;
	list($name,$mail,$date,$comment,$subject,$host,$ipaddr) = explode('<>', $host_log[$i]);
	if (!$mail) $mail = '&nbsp;';
	if (!$subject) $subject = '&nbsp;';
	$comment = htmlspecialchars($comment);
	$deny_flag = '<a class="item" href="'.$_SERVER['PHP_SELF'].'?bbs='.$_GET['bbs'].'&amp;page='.$_GET['page'].'&amp;mode=ban&amp;id='.$i.'">�A�N��</a>';
	foreach ($deny_array as $deny) {
		$deny = trim($deny);
		if (stristr($host, $deny)) {
			$host = str_replace($deny, '<font color="red"><b>'.$deny.'</b></font>', $host);
			$deny_flag = '�A�N�֍�';
			break;
		}
		if (stristr($ipaddr, $deny)) {
			$ipaddr = str_replace($deny, '<font color="red"><b>'.$deny.'</b></font>', $ipaddr);
			$deny_flag = '�A�N�֍�';
			break;
		}
	}
// Parse error: Unclosed '{' on line 86 ���o���̂ōǂ�
}
	?>
<tr>
<td rowspan="2"><input type="checkbox" name="del[]" value="<?=$i++?>"></td>
<td rowspan="2" align="center"><?=$i--?></td>
<td colspan="2"><font color="<?=$SETTING['BBS_NAME_COLOR']?>"><b><?=$name?></b></font> [<?=$mail?>] (<?=$date?>)�@<font color="<?=$SETTING['BBS_SUBJECT_COLOR']?>"><?=$subject?></font></td><td rowspan="2"><?=$deny_flag?></td></tr>
<tr>
<td><?=$comment?> </td><td><?=$host?> (<?=$ipaddr?>)</td>
</tr>
<?
}
?>
</table>
<?php
for ($i = 1; $i <= $total_page; $i++) {
	if ($i == $_GET['page']) echo " $i \n";
	else echo " <a class=\"item\" href=\"$_SERVER[PHP_SELF]?bbs=$_GET[bbs]&amp;page=$i\">$i</a> \n";
}
?>
<br>
<input type="submit" value="�폜">
</form>
</body></html>
<?
exit;
?>