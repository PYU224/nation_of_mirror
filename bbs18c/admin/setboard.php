<?php
require("passcheck.php");
if (!isset($_POST['mode'])) $_POST['mode'] = '';

// ���̊֐��� PHP 7.4.0 �� �񐄏� �ɂȂ�APHP 8.0.0 �� �폜 ����܂����B���̊֐��ɗ���Ȃ����Ƃ������������܂��B
// https://www.php.net/manual/ja/function.get-magic-quotes-gpc.php
/*
if(get_magic_quotes_gpc()) {
	$_POST = array_map("stripslashes", $_POST);
}
*/
	$_POST = array_map("stripslashes", $_POST);

if (!isset($_REQUEST['bbs']) or !$_REQUEST['bbs']) disperror("�d�q�q�n�q�I","�d�q�q�n�q�F��������Ă���B�B�B");
if (!is_dir("../$_REQUEST[bbs]")) disperror("�d�q�q�n�q�I","�d�q�q�n�q�F����ȔȂ��ł��B");
$set_pass = "../$_REQUEST[bbs]/SETTING.TXT";
$comment = '<br>';
#====================================================
#�@�������̏����݁i�ݒ�t�@�C���j
#====================================================
if ($_POST['mode'] == 'set') {
	error_reporting(E_ALL ^ E_NOTICE);
	$setvalue = <<<EOF
BBS_TITLE=$_POST[BBS_TITLE]
BBS_TITLE_PICTURE=$_POST[BBS_TITLE_PICTURE]
BBS_TITLE_COLOR=$_POST[BBS_TITLE_COLOR]
BBS_TITLE_LINK=$_POST[BBS_TITLE_LINK]
BBS_BG_COLOR=$_POST[BBS_BG_COLOR]
BBS_BG_PICTURE=$_POST[BBS_BG_PICTURE]
BBS_NONAME_NAME=$_POST[BBS_NONAME_NAME]
BBS_MAKETHREAD_COLOR=$_POST[BBS_MAKETHREAD_COLOR]
BBS_MENU_COLOR=$_POST[BBS_MENU_COLOR]
BBS_THREAD_COLOR=$_POST[BBS_THREAD_COLOR]
BBS_TEXT_COLOR=$_POST[BBS_TEXT_COLOR]
BBS_NAME_COLOR=$_POST[BBS_NAME_COLOR]
BBS_LINK_COLOR=$_POST[BBS_LINK_COLOR]
BBS_ALINK_COLOR=$_POST[BBS_ALINK_COLOR]
BBS_VLINK_COLOR=$_POST[BBS_VLINK_COLOR]
BBS_THREAD_NUMBER=$_POST[BBS_THREAD_NUMBER]
BBS_CONTENTS_NUMBER=$_POST[BBS_CONTENTS_NUMBER]
BBS_LINE_NUMBER=$_POST[BBS_LINE_NUMBER]
BBS_MAX_MENU_THREAD=$_POST[BBS_MAX_MENU_THREAD]
BBS_SUBJECT_COLOR=$_POST[BBS_SUBJECT_COLOR]
BBS_PASSWORD_CHECK=$_POST[BBS_PASSWORD_CHECK]
BBS_UNICODE=$_POST[BBS_UNICODE]
BBS_DELETE_NAME=$_POST[BBS_DELETE_NAME]
BBS_NAMECOOKIE_CHECK=$_POST[BBS_NAMECOOKIE_CHECK]
BBS_MAILCOOKIE_CHECK=$_POST[BBS_MAILCOOKIE_CHECK]
BBS_SUBJECT_COUNT=$_POST[BBS_SUBJECT_COUNT]
BBS_NAME_COUNT=$_POST[BBS_NAME_COUNT]
BBS_MAIL_COUNT=$_POST[BBS_MAIL_COUNT]
BBS_MESSAGE_COUNT=$_POST[BBS_MESSAGE_COUNT]
BBS_NEWSUBJECT=$_POST[BBS_NEWSUBJECT]
BBS_THREAD_TATESUGI=$_POST[BBS_THREAD_TATESUGI]
BBS_AD2=$_POST[BBS_AD2]
SUBBBS_CGI_ON=$_POST[SUBBBS_CGI_ON]
NANASHI_CHECK=$_POST[NANASHI_CHECK]
timecount=$_POST[timecount]
timeclose=$_POST[timeclose]
BBS_PROXY_CHECK=$_POST[BBS_PROXY_CHECK]
BBS_OVERSEA_THREAD=$_POST[BBS_OVERSEA_THREAD]
BBS_OVERSEA_PROXY=$_POST[BBS_OVERSEA_PROXY]
BBS_RAWIP_CHECK=$_POST[BBS_RAWIP_CHECK]
BBS_SLIP=$_POST[BBS_SLIP]
BBS_DISP_IP=$_POST[BBS_DISP_IP]
BBS_FORCE_ID=$_POST[BBS_FORCE_ID]
BBS_NO_ID=$_POST[BBS_NO_ID]

EOF;
	$fp = fopen($set_pass, "w");
	fputs($fp, $setvalue);
	fclose($fp);
	$comment = '<font color="red">�ݒ���X�V���܂����B���j���[��<b>index.html����蒼��</b>���N���b�N���Ă��������B</font><br>';
}
#====================================================
#�@�������̎擾�i�ݒ�t�@�C���j
#====================================================
#�ݒ�t�@�C����ǂ�
if (is_file($set_pass)) {
	$set_str = file($set_pass);
	foreach ($set_str as $tmp){
		$tmp = rtrim($tmp);
		list($name, $value) = explode("=", $tmp);
		$SETTING[$name] = $value;
	}
}
else disperror("�d�q�q�n�q�I","�d�q�q�n�q�F���[�U�[�ݒ肪�������Ă��܂��I");
if (!is_file("../$_REQUEST[bbs]/index.html")) {
	$comment = '<font color=red>�܂��e�ݒ荀�ڂ�ύX���<b>�ݒ�X�V</b>�{�^���������A<a href="admin.php?bbs='.$_REQUEST['bbs'].'" target="_parent">����</a>���烁�j���[�̍X�V�����āA���Ƀ��j���[��<b>index.html����蒼��</b>���N���b�N���Ă��������B</font><br>';
}
$sel_pass = $sel_change = "";
if ($SETTING['BBS_UNICODE'] == "pass") $sel_pass = "selected";
if ($SETTING['BBS_UNICODE'] == "change") $sel_change = "selected";
#====================================================
#�@�f���̐ݒ�
#====================================================
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
<title>�ݒ�ύX</title>
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>�ݒ�ύX</h3>
<hr>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<?=$comment?>
<input type="submit" value="�ݒ�X�V">
<input type="hidden" name="mode" value="set">
<input type="hidden" name="bbs" value="<?=$_REQUEST['bbs']?>">
<table border="2">
<tr>
<th>�f���̃^�C�g��</th>
<td><input type="text" size="30" name="BBS_TITLE" value="<?=$SETTING['BBS_TITLE']?>"></td>
</tr>
<tr>
<th>�^�C�g���摜�̃p�X</th>
<td><input type="text" size="30" name="BBS_TITLE_PICTURE" value="<?=$SETTING['BBS_TITLE_PICTURE']?>"></td>
</tr>
<tr>
<th>�^�C�g���̃����NURL</th>
<td><input type="text" size="30" name="BBS_TITLE_LINK" value="<?=$SETTING['BBS_TITLE_LINK']?>"></td>
</tr>
<tr>
<th>�w�i�摜�̃p�X</th>
<td><input type="text" size="30" name="BBS_BG_PICTURE" value="<?=$SETTING['BBS_BG_PICTURE']?>"></td>
</tr>
<tr>
<th>����������̖��O</th>
<td><input type="text" size="30" name="BBS_NONAME_NAME" value="<?=$SETTING['BBS_NONAME_NAME']?>"></td>
</tr>
<tr>
<th>�폜���b�Z�[�W</th>
<td><input type="text" size="30" name="BBS_DELETE_NAME" value="<?=$SETTING['BBS_DELETE_NAME']?>"></td>
</tr>
<tr>
<th><font color=<?=$SETTING['BBS_TITLE_COLOR']?>>��</font>�^�C�g���̐F</th>
<td><input type="text" size="30" name="BBS_TITLE_COLOR" value="<?=$SETTING['BBS_TITLE_COLOR']?>"></td>
</tr>
<tr>
<th><font color=<?=$SETTING['BBS_BG_COLOR']?>>��</font>�w�i�F</th>
<td><input type="text" size="30" name="BBS_BG_COLOR" value="<?=$SETTING['BBS_BG_COLOR']?>"></td>
</tr>
<tr>
<th><font color=<?=$SETTING['BBS_MAKETHREAD_COLOR']?>>��</font>�X���b�h�V�K�쐬��ʂ̐F</th>
<td><input type="text" size="30" name="BBS_MAKETHREAD_COLOR" value="<?=$SETTING['BBS_MAKETHREAD_COLOR']?>"></td>
</tr>
<tr>
<th><font color=<?=$SETTING['BBS_MENU_COLOR']?>>��</font>�X�����j���[�\�����̔w�i�F</th>
<td><input type="text" size="30" name="BBS_MENU_COLOR" value="<?=$SETTING['BBS_MENU_COLOR']?>"></td>
</tr>
<tr>
<th><font color=<?=$SETTING['BBS_THREAD_COLOR']?>>��</font>�X���b�h�\�����̔w�i�F</th>
<td><input type="text" size="30" name="BBS_THREAD_COLOR" value="<?=$SETTING['BBS_THREAD_COLOR']?>"></td>
</tr>
<tr>
<th><font color=<?=$SETTING['BBS_SUBJECT_COLOR']?>>��</font>�X���^�C�g���̐F</th>
<td><input type="text" size="30" name="BBS_SUBJECT_COLOR" value="<?=$SETTING['BBS_SUBJECT_COLOR']?>"></td>
</tr>
<tr>
<th><font color=<?=$SETTING['BBS_TEXT_COLOR']?>>��</font>���e���̐F</th>
<td><input type="text" size="30" name="BBS_TEXT_COLOR" value="<?=$SETTING['BBS_TEXT_COLOR']?>"></td>
</tr>
<tr>
<th><font color=<?=$SETTING['BBS_NAME_COLOR']?>>��</font>���O�̐F</th>
<td><input type="text" size="30" name="BBS_NAME_COLOR" value="<?=$SETTING['BBS_NAME_COLOR']?>"></td>
</tr>
<tr>
<th><font color=<?=$SETTING['BBS_LINK_COLOR']?>>��</font>LINK�̐F</th>
<td><input type="text" size="30" name="BBS_LINK_COLOR" value="<?=$SETTING['BBS_LINK_COLOR']?>"></td>
</tr>
<tr>
<th><font color=<?=$SETTING['BBS_ALINK_COLOR']?>>��</font>ALINK�̐F</th>
<td><input type="text" size="30" name="BBS_ALINK_COLOR" value="<?=$SETTING['BBS_ALINK_COLOR']?>"></td>
</tr>
<tr>
<th><font color=<?=$SETTING['BBS_VLINK_COLOR']?>>��</font>VLINK�̐F</th>
<td><input type="text" size="30" name="BBS_VLINK_COLOR" value="<?=$SETTING['BBS_VLINK_COLOR']?>"></td>
</tr>
<tr>
<th>index.html �ɕ\������X���b�h��</th>
<td><input type="text" size="30" name="BBS_THREAD_NUMBER" value="<?=$SETTING['BBS_THREAD_NUMBER']?>"></td>
</tr>
<tr>
<th>1�X���b�h�ɕ\�����郌�X��</th>
<td><input type="text" size="30" name="BBS_CONTENTS_NUMBER" value="<?=$SETTING['BBS_CONTENTS_NUMBER']?>"></td>
</tr>
<tr>
<th>1���X�ɕ\������s��</th>
<td><input type="text" size="30" name="BBS_LINE_NUMBER" value="<?=$SETTING['BBS_LINE_NUMBER']?>"></td>
</tr>
<tr>
<th>���j���[�ɕ\������X���b�h��</th>
<td><input type="text" size="30" name="BBS_MAX_MENU_THREAD" value="<?=$SETTING['BBS_MAX_MENU_THREAD']?>"></td>
</tr>
<tr>
<th>�X���^�C�g���̍ő啶�����i�o�C�g�j</th>
<td><input type="text" size="30" name="BBS_SUBJECT_COUNT" value="<?=$SETTING['BBS_SUBJECT_COUNT']?>"></td>
</tr>
<tr>
<th>���O�̍ő啶�����i�o�C�g�j</th>
<td><input type="text" size="30" name="BBS_NAME_COUNT" value="<?=$SETTING['BBS_NAME_COUNT']?>"></td>
</tr>
<tr>
<th>���[���̍ő啶�����i�o�C�g�j</th>
<td><input type="text" size="30" name="BBS_MAIL_COUNT" value="<?=$SETTING['BBS_MAIL_COUNT']?>"></td>
</tr>
<tr>
<th>�{���̍ő啶�����i�o�C�g�j</th>
<td><input type="text" size="30" name="BBS_MESSAGE_COUNT" value="<?=$SETTING['BBS_MESSAGE_COUNT']?>"></td>
</tr>
<tr>
<th>UNICODE����</th>
<td><select name="BBS_UNICODE"><option value="pass" <?=$sel_pass?>>pass<option value="change" <?=$sel_change?>>change</select></td>
</tr>
<tr>
<th>�z�X�g���\��</th>
<td>����<input type="checkbox" name="BBS_DISP_IP" value="checked" <?=$SETTING['BBS_DISP_IP']?>></td>
</tr>
<tr>
<th>ID�\��</th>
<td>���Ȃ�<input type="checkbox" name="BBS_NO_ID" value="checked" <?=$SETTING['BBS_NO_ID']?>></td>
</tr>
<tr>
<th>ID�����\��</th>
<td>����<input type="checkbox" name="BBS_FORCE_ID" value="checked" <?=$SETTING['BBS_FORCE_ID']?>>��ID�\�����Ȃ����`�F�b�N����Ă���Ƃ����炪�D�悳��܂�</td>
</tr>
<tr>
<th>���O���͕K�{</th>
<td>����<input type="checkbox" name="NANASHI_CHECK" value="checked" <?=$SETTING['NANASHI_CHECK']?>></td>
</tr>
<tr>
<th>�X���b�h���Ă���</th>
<td><input type="text" size="3" name="BBS_THREAD_TATESUGI" value="<?=$SETTING['BBS_THREAD_TATESUGI']?>"> �Ԋu</td>
</tr>
<tr>
<th>�A�����e����</th>
<td><input type="text" size="3" name="timecount" value="<?=$SETTING['timecount']?>">��<input type="text" size="3" name="timeclose" value="<?=$SETTING['timeclose']?>">��Ő���</td>
</tr>
<tr>
<th>Cookie(NAME)�쐬</th>
<td>����<input type="checkbox" name="BBS_NAMECOOKIE_CHECK" value="checked" <?=$SETTING['BBS_NAMECOOKIE_CHECK']?>></td>
</tr>
<tr>
<th>Cookie(MAIL)�쐬</th>
<td>����<input type="checkbox" name="BBS_MAILCOOKIE_CHECK" value="checked" <?=$SETTING['BBS_MAILCOOKIE_CHECK']?>></td>
</tr>
<tr>
<th>PROXY����</th>
<td>����<input type="checkbox" name="BBS_PROXY_CHECK" value="checked" <?=$SETTING['BBS_PROXY_CHECK']?>>���ϐ��Ŕ��f����̂œ���PROXY�͐����ł��܂���</td>
</tr>
<tr>
<th>�C�OPROXY����</th>
<td>����<input type="checkbox" name="BBS_OVERSEA_PROXY" value="checked" <?=$SETTING['BBS_OVERSEA_PROXY']?>></td>
</tr>
<tr>
<th>.JP�h���C���ȊO����̃X�����ċ���</th>
<td>����<input type="checkbox" name="BBS_OVERSEA_THREAD" value="checked" <?=$SETTING['BBS_OVERSEA_THREAD']?>>bbtec.net(YahooBB)�͋��ۂ���܂���</td>
</tr>
</table>
<input type="submit" value="�ݒ�X�V"><br>
</form>
<br>
</body></html>
