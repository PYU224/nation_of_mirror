<?php
require("passcheck.php");
#=====================================
#�@�e�L�X�g�ҏW
#=====================================
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
# index.txt�̓ǂݍ���
list($header,) = explode('<CUT>', implode('', file("../test/index.txt")));
$header = str_replace("<BBS_TITLE>", $SETTING['BBS_TITLE'], $header);
$header = str_replace("<BBS_TEXT_COLOR>", $SETTING['BBS_TEXT_COLOR'], $header);
$header = str_replace("<BBS_MENU_COLOR>", $SETTING['BBS_MENU_COLOR'], $header);
$header = str_replace("<BBS_LINK_COLOR>", $SETTING['BBS_LINK_COLOR'], $header);
$header = str_replace("<BBS_ALINK_COLOR>", $SETTING['BBS_ALINK_COLOR'], $header);
$header = str_replace("<BBS_VLINK_COLOR>", $SETTING['BBS_VLINK_COLOR'], $header);
$header = str_replace("<BBS_BG_COLOR>", $SETTING['BBS_BG_COLOR'], $header);
$header = str_replace("<BBS_BG_PICTURE>", $SETTING['BBS_BG_PICTURE'], $header);
$header = str_replace("<BBS_TITLE_NAME>", '<h1 class="title">'.$SETTING['BBS_TITLE'].'</h1>
<h3>�e�L�X�g�ҏW</h3>', $header);
$headad = implode('', file("../test/headad.txt"));
if (isset($_REQUEST['mode']) and $_REQUEST['mode'] == 'view') {
	$head = implode('', file("../$_REQUEST[bbs]/head.txt"));
	$header = str_replace("<GUIDE>", $head, $header);
	$option = implode('', file("../test/option.txt"));
	$header = str_replace("<OPTION>", $option, $header);
	$putad = implode('', file("../test/putad.txt"));
	$header = str_replace("<PUTAD>", $putad, $header);
	echo $header;
	$headad = implode('', file("../test/headad.txt"));
	if ($headad) {
		echo '<table border="1" cellspacing="7" cellpadding="3" width="95%" bgcolor="'.$SETTING['BBS_MENU_COLOR']."\" align=\"center\">\n <tr>\n  <td>\n";
		echo $headad;
		echo "\n  </td>\n </tr>\n</table><br>\n";
	}
	exit;
}
if (isset($_REQUEST['file'])) {
	if ($_REQUEST['file'] == 'option' or $_REQUEST['file'] == 'putad' or $_REQUEST['file'] == 'headad')
			$file_name = "../test/$_REQUEST[file].txt";
	elseif ($_REQUEST['file'] == 'head') $file_name = "../$_REQUEST[bbs]/head.txt";
	else disperror("�d�q�q�n�q�I", "�t�@�C�������s���ł��B");
	if (!is_file($file_name)) disperror("�d�q�q�n�q�I", "�t�@�C���i".$file_name."�j������܂���B�t�@�C�����A�b�v���[�h���Ă��������B");
	if (!is_writable($file_name)) disperror("�d�q�q�n�q�I", "�t�@�C���i".$file_name."�j�ɏ������ݑ���������܂���B�p�[�~�b�V������606��666�ɂ��Ă��������B");
	$comment = '';
	if (isset($_POST['text']) and $_POST['mode'] == 'write') {
		if (get_magic_quotes_gpc()) $_POST['text'] = stripslashes($_POST['text']);
		$fp = fopen($file_name, "w");
		fputs($fp, $_POST['text']);
		fclose($fp);
		$comment = "�t�@�C�������������܂����B���j���[��<b>index.html����蒼��</b>���N���b�N���Ă�������";
	}
	$text = implode('', file($file_name));
	?>
<html>
<head>
<title>�e�L�X�g�ҏW</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>�e�L�X�g�ҏW</h3>
<hr>
<font color="red"><?=$comment?></font><br>
<b><?=$file_name?></b>��ҏW���Ă��܂��@�@�@�@�@
<a class="item" href="edit.php?bbs=<?=$_REQUEST['bbs']?>">�ҏW���j���[</a>�@�@�@�@�@<a class="item" href="edit.php?mode=view&amp;bbs=<?=$_REQUEST['bbs']?>" target="_blank">�m�F</a><br>
<br>
<form action="./edit.php" method="post">
<input type="hidden" name="mode" value="write">
<input type="hidden" name="bbs" value="<?=$_REQUEST['bbs']?>">
<input type="hidden" name="file" value="<?=$_REQUEST['file']?>">
<input type="submit" name="submit" value="�ύX">
<input type="reset" name="reset" value="���Z�b�g"><br>
<textarea rows="30" cols="80" name="text"><?=$text?></textarea>
</form>
</body>
</html>
<?php
	exit;
}
$form = '<form action="edit.php" method="post"><input type="hidden" name="bbs" value="'.$_REQUEST['bbs'].'"><input type="hidden" name="file" value="head"><input type="submit" value="head.txt��ҏW����"></form>';
$header = str_replace("<GUIDE>", $form, $header);
$form = '<form action="edit.php" method="post"><input type="hidden" name="bbs" value="'.$_REQUEST['bbs'].'"><input type="hidden" name="file" value="option"><input type="submit" value="option.txt��ҏW����"></form>';
$header = str_replace("<OPTION>", $form, $header);
$form = '<form action="edit.php" method="post"><input type="hidden" name="bbs" value="'.$_REQUEST['bbs'].'"><input type="hidden" name="file" value="putad">�@�@�@�@<input type="submit" value="putad.txt��ҏW����"></form>';
$header = str_replace("<PUTAD>", $form, $header);
$css = '<link rel="stylesheet" href="main.css" type="text/css">
</head>
';
$header = str_replace("</head>", $css, $header);
echo $header;
?>
<table border="1" cellspacing="7" cellpadding="3" width="95%" bgcolor="<?=$SETTING['BBS_MENU_COLOR']?>" align="center">
 <tr>
  <td>
<form action="edit.php" method="post"><input type="hidden" name="bbs" value="<?=$_REQUEST['bbs']?>"><input type="hidden" name="file" value="headad"><input type="submit" value="headad.txt��ҏW����"></form>
  </td>
 </tr>
</table><br>
�f���𕡐��ݒu���Ă���ꍇoption.txt,putad.txt,headad.txt�͑S�Ă̌f���œ������̂��g�p����܂��B<br>
head.txt�݂̂��f�����ƂɕύX�ł��܂��B<br>
<a class="item" href="edit.php?mode=view&amp;bbs=<?=$_REQUEST['bbs']?>" target="_blank">�m�F</a><br>
</body></html>
