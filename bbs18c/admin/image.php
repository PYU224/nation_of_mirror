<?php
require 'passcheck.php';
#��x�ɕ\�����郊�X�g
$inum = 10;
if(!is_dir("../$_GET[bbs]")) disperror("�d�q�q�n�q�I", "����Ȕ�or�X���b�h�Ȃ��ł��B");
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
require "../$_GET[bbs]/config.php";
#==================================================
#�@�t�@�C������i�摜�t�@�C�����ǂݍ��݁j
#==================================================
$img_dir = "../$_GET[bbs]/img";
if ($dir = opendir($img_dir)) {
	while (($file = readdir($dir)) !== false) {
		if ($file != '.' and $file != '..') $img_list[] = $file;
	}  
	closedir($dir);
}
@sort($img_list);
@reset($img_list);
#==================================================
#�@�摜�폜
#==================================================
if(isset($_GET['mode']) and $_GET['mode'] == "img_del") {
	if (isset($_GET['del']) and $_GET['del']) {
		foreach ($_GET['del'] as $del){
			$file_name = $img_list[$del];
			unlink("../$_GET[bbs]/img/$file_name");
			$base = preg_replace("/\D/", '', $file_name);
			if (is_file ("../$_GET[bbs]/img2/$base.jpg")) {
				unlink("../$_GET[bbs]/img2/$base.jpg");
			}
		}
	}
	$img_list = array();
	$img_dir = "../$_GET[bbs]/img";
	if ($dir = opendir($img_dir)) {
		while (($file = readdir($dir)) !== false) {
			if($file != '.' and $file != '..') {
				$img_list[] = $file;
			}
		}  
		closedir($dir);
	}
	@sort($img_list);
	@reset($img_list);
}
#==================================================
#�@�t�@�C�����\��
#==================================================
if (!isset($_GET['page']) or !$_GET['page']) $_GET['page'] = 1;
$st = ($_GET['page'] - 1) * $inum;
$num = count($img_list);
$total_page = (int)(($num+$inum-1)/$inum);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
<title>�摜�폜</title>
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>�摜�폜</h3>
<hr>
�폜�������摜�̃`�F�b�N�{�b�N�X���`�F�b�N����<b>�폜</b>�{�^���������Ă��������B<br>
<br>
<form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
<input type="hidden" name="bbs" value="<?=$_GET['bbs']?>">
<input type="hidden" name="mode" value="img_del">
<input type="submit" value="�폜">
page�F<?=$_GET['page']?><br>
<?
for ($i = 1; $i <= $total_page; $i++) {
	if ($i == $_GET['page']) echo " $i \n";
	else echo " <a class=\"item\" href=\"$_SERVER[PHP_SELF]?bbs=$_GET[bbs]&amp;page=$i\">$i</a> \n";
}
?>
<table border="1" cellspacing="0" cellpadding="2">
<tr><td>�@</td><td>�ԍ�</td><td>�t�@�C����</td><td>�摜</td></tr>
<?php
for ($i = $st; $i < $st + $inum; $i++) {
	if (!isset($img_list[$i])) break;
	$base = preg_replace("/\D/", '', $img_list[$i]);
	if (is_file("../$_GET[bbs]/img2/$base.jpg")) {
		$src = "../$_GET[bbs]/img2/$base.jpg";
		list(,,,$size) = getimagesize($src);
	}
	else {
		$src = "../$_GET[bbs]/img/$img_list[$i]";
		list($width, $height) = getimagesize($src);
		if ($width > MAX_W or $height > MAX_H) {
			$W2 = MAX_W / $width;
			$H2 = MAX_H / $height;
			$ratio = ($W2 < $H2) ? $W2 : $H2;
			$width = (int)($width * $ratio);
			$height = (int)($height * $ratio);
		}
		$size = 'width="'.$width.'" height="'.$height.'"';
	}
	echo '<tr><td><input type="checkbox" name="del[]" value="'.$i++.'"></td><td align="center">'.$i--."</td><td><a class=\"item\" href=\"../$_GET[bbs]/img/$img_list[$i]\">$img_list[$i]</a></td><td><img src=\"$src\" $size></td></tr>\n";
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