<?php
$version = "r.php ver2.5 (2005/03/28)";
$res_count = 10; // 1��ʂɕ\�����郌�X�̐��B
#==================================================
#�@���N�G�X�g���
#==================================================
$st = $to = 0;
$nofirst = '';
$ls = 0;
extract ($_GET);
// PATH INFO����p�����[�^�����o���B
if (!empty($_SERVER['PATH_INFO'])) {
	$pairs = explode('/',$_SERVER['PATH_INFO']);
	$bbs = $pairs[1];
	$key = $pairs[2];
}
if (!file_exists("../$bbs/dat/$key.dat")) {
	if (file_exists("../$bbs/kako/$key.html")) echo"�ߋ�۸ޑq�ɂł��B<BR>�g�тł͌����܂���B";
	else echo("�گ�ނ�������܂���B");
	exit;
}
$log = file("../$bbs/dat/$key.dat");
$linenum = count($log);
if (!empty($pairs[3])) { // ���X�w������擾
	if (strstr($pairs[3], "n")) {
		$to = $linenum;
		$st = $to - $res_count + 1;
		$nofirst = 'true';
		$pairs[3] = str_replace("n", "", $pairs[3]);
	}
	if (substr($pairs[3], 0, 1) == 'l'){
		$ls = substr($pairs[3],1);
		if(!$ls) {$ls = $res_count;}
		$to = $linenum;
		$st = $to - $ls + 1;
		$nofirst = 'true';
	}
	elseif (preg_match("/\-/",$pairs[3])){
		$nofirst = 'true';
		list($st, $to) = explode('-',$pairs[3]);
		if (!$st) {$st = $to >= $res_count ? $to-$res_count+1 : 1; }
		if (!$to) {$to = $st+$res_count-1; }
	}
	elseif ($pairs[3]){
		$st = $pairs[3];
		$to = $pairs[3];
		$nofirst = 'true';
	}
	else {
		$to = $linenum;
		$st = $to - $res_count + 1;
	}
}
else {
	$to = $linenum;
	$st = $to - $res_count + 1;
}
if ($st < 1) $st = 1;
if ($nofirst != "true") $st++;
if ($st > $linenum) $st = $linenum;
if ($to > $linenum) $to = $linenum;
list(,,,,$subject) = explode("<>",$log[0]);
$subject = chop($subject);
list(,,$tmp) = explode("<>", $log[$linenum-1]);
if (preg_match("/Over 1000 Thread|�X�g�b�p|��~/", $tmp)) $stop = 1;
if ($st > 1) {
	$i = $st - 1;
	$link = "<a href=-".$i.">�O</a> ";
}
else $link = '';
?>
<html><head><title><?=$subject?></title></head><body><hr><?=$link?><a href=<?=$to+1?>->��</a> <a href=n>�V10</a> <a href=1->1-</a> <a href=../../../../<?=$bbs?>/i/>��</a> <a href=w>����</a><hr><?php
if ($nofirst != "true" or $st == 1 or strstr($pairs[3], "w")) {
	if ($st == 1) {$st++;}
	chop($log[0]);
	list($name,,$time,$message) = explode("<>",$log[0]);
	$name = str_replace(array("<b>","</b>"), "", $name);
	if (!$message) {
		$name='';
		$time='[�������Ă܂�]';
		$message='[�������Ă܂�]';
	}
	$message = preg_replace("/(https?):\/\/(www\d*\.|)([\da-zA-Z\-\.]{1,10})([\x21-\x7E]*|)/i","<a href=$1://$2$3$4>$3</a>",$message);
	$read = str_replace("r.php", "read.php", $_SERVER['SCRIPT_NAME']);
	$message = str_replace("http://".$_SERVER['HTTP_HOST'].$read, $_SERVER['SCRIPT_NAME'], $message);
	$message = str_replace("../test/read.php/$bbs/$key/",'',$message);
	$message = preg_replace("/<a.+<img src=\"(.+\.)(\w+)\"[^>]*>/", "<a href=\"../../../$1$2\"> $2 ", $message);
	$msgline = substr_count($message, "<br>") + 1;
	if ($msgline > 6 and $to != 1) {
		preg_match("/(.*) <br>.*/U", $message, $match);
		$message = $match[1]."<a href=1><br> ��".$msgline."</a>";
	}
	echo $subject."[1]$name $time<br>$message<hr>";
}
if (strstr($pairs[3], "w")) {
	?>
<form method=post action=../../../bbs.php>NAME�F<input name=FROM>MAIL�F<input name=mail istyle=3><input type=hidden name=bbs value=<?=$bbs?>><input type=hidden name=key value=<?=$key?>><input type=hidden name=time value=<?=time()?>><textarea name=MESSAGE></textarea><input type=submit value="��������" name=submit></form><br><?=$version?></body><?php
	exit;
}
for ($s = $st; $s <= $to; $s++){
	$line = chop($log[$s-1]);
	list ($name,,$time,$message) = explode("<>", $line);
	$name = str_replace(array("<b>","</b>"), "", $name);
	if (!$message) {
		$name='';
		$time='[�������Ă܂�]';
		$message='[�������Ă܂�]';
	}
	$message = preg_replace("/(https?):\/\/(www\d*\.|)([\da-zA-Z\-\.]{1,10})([\x21-\x7E]*|)/i","<a href=$1://$2$3$4>$3</a>",$message);
	$read = str_replace("r.php", "read.php", $_SERVER['SCRIPT_NAME']);
	$message = str_replace("http://".$_SERVER['HTTP_HOST'].$read, $_SERVER['SCRIPT_NAME'], $message);
	$message = str_replace("../test/read.php/$bbs/$key/",'',$message);
	$message = preg_replace("/<a.+<img src=\"(.+\.)(\w+)\"[^>]+>/", "<a href=\"../../../$1$2\"> $2 ", $message);
	$msgline = substr_count($message, "<br>") + 1;
	if ($msgline > 6 and $st != $to) {
		preg_match("/(.*) <br>.*/U", $message, $match);
		$message = $match[1]."<a href=".$s."><br> ��".$msgline."</a>";
	}
	print "[$s]$name $time<br>$message<hr>";
}
echo $link;
?>
<a href=<?=$to+1?>->��</a> <a href=w>����</a><hr><?=$version?></body></html>
