<?php
require("passcheck.php");
if (!isset($_POST['mode'])) $_POST['mode'] = '';
if(get_magic_quotes_gpc()) {
	$_POST = array_map("stripslashes", $_POST);
}
if (!isset($_REQUEST['bbs']) or !$_REQUEST['bbs']) disperror("�d�q�q�n�q�I","�d�q�q�n�q�F��������Ă���B�B�B");
if (!is_dir("../$_REQUEST[bbs]")) disperror("�d�q�q�n�q�I","�d�q�q�n�q�F����ȔȂ��ł��B");
$set_pass = "../$_REQUEST[bbs]/config.php";
$comment = '<br>';
#====================================================
#�@�ݒ�t�@�C���̏�����
#====================================================
if ($_POST['mode'] == 'set') {
	$_POST['THREAD_MAX_MSG'] = str_replace(array("\r\n", "\r", "\n"), '<br>', $_POST['THREAD_MAX_MSG']);
	$setvalue = <<<EOF
<?php
# �ݒ�t�@�C���i"SETTING.TXT"�ȊO�̐ݒ荀�ځj
# ���O�t�@�C���ێ����i�V�X�e���ݒ�j
define('KEEPLOGCOUNT', $_POST[KEEPLOGCOUNT]);
# 1�X���b�h�ɓ��e�ł��郌�X���̏��
define('THREAD_RES', $_POST[THREAD_RES]);
# ���X�I�[�o�[���̃��b�Z�[�W
define('THREAD_MAX_MSG', '$_POST[THREAD_MAX_MSG]');
# 1�X���b�h�̏���i�o�C�g�j
define('THREAD_BYTES', $_POST[THREAD_BYTES]);
# �t�@�C���A�b�v����
define('UPLOAD', $_POST[UPLOAD]);
# GD�o�[�W����
define('GD_VERSION', $_POST[GD_VERSION]);
# �A�b�v���[�h����i�o�C�g�j
define('MAX_BYTES', $_POST[MAX_BYTES]);
# �T���l�C���摜�̕�
define('MAX_W', $_POST[MAX_W]);
# �T���l�C���摜�̍���
define('MAX_H', $_POST[MAX_H]);
# ���݂����@�\
define('OMIKUJI', $_POST[OMIKUJI]);
# �싅�@�\
define('BASEBALL', $_POST[BASEBALL]);
# �ǂ��N���@�\
define('WHO_WHERE', $_POST[WHO_WHERE]);
# �ً@�\�i�������j
define('TUBO', $_POST[TUBO]);
# �����t�H���g�@�\
define('TELETYPE', $_POST[TELETYPE]);
# �X���b�h�����������ύX�@�\
define('NAME_774', $_POST[NAME_774]);
# �������֋����ύX�@�\
define('FORCE_774', $_POST[FORCE_774]);
# ID�Ȃ��@�\
define('FORCE_NO_ID', $_POST[FORCE_NO_ID]);
# sage�����@�\
define('FORCE_SAGE', $_POST[FORCE_SAGE]);
# ���X�v�L���b�v�@�\
define('FORCE_STARS', $_POST[FORCE_STARS]);
# �X���b�h��VIP�@�\����
define('FORCE_NORMAL', $_POST[FORCE_NORMAL]);
# ���O���͋����@�\
define('FORCE_NAME', $_POST[FORCE_NAME]);
# 0thelo�@�\
define('ZEROTHELO', $_POST[ZEROTHELO]);
# �A�b�v���[�h�@�\
define('FORCE_UP', $_POST[FORCE_UP]);
?>

EOF;
	$fp = fopen($set_pass, "w");
	fputs($fp, $setvalue);
	fclose($fp);
	$comment = '<font color="red">�ݒ���X�V���܂����B</font><br>';
}
#====================================================
#�@�������̎擾�i�ݒ�t�@�C���j
#====================================================
#�ݒ�t�@�C����ǂ�
if (is_file("../$_REQUEST[bbs]/SETTING.TXT")) {
	$set_str = file("../$_REQUEST[bbs]/SETTING.TXT");
	foreach ($set_str as $tmp){
		$tmp = rtrim($tmp);
		list($name, $value) = explode("=", $tmp);
		$SETTING[$name] = $value;
	}
}
else disperror("�d�q�q�n�q�I","�d�q�q�n�q�F���[�U�[�ݒ肪�������Ă��܂��I");
#�ݒ�t�@�C��2��ǂ�
if (is_file($set_pass)) {
	require $set_pass;
}
else disperror("�d�q�q�n�q�I","�d�q�q�n�q�F���[�U�[�ݒ�Q���������Ă��܂��I");
if (!is_file("../$_REQUEST[bbs]/index.html")) {
	$comment = '<font color=red>�܂��e�ݒ荀�ڂ�ύX���<b>�ݒ�X�V</b>�{�^���������A<a href="admin.php?bbs='.$_REQUEST['bbs'].'" target="_parent">����</a>���烁�j���[�̍X�V�����āA���Ƀ��j���[��<b>index.html����蒼��</b>���N���b�N���Ă��������B</font><br>';
}
$maxmsg = str_replace('<br>', "\n", THREAD_MAX_MSG);
#====================================================
#�@�f���̐ݒ�
#====================================================
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
<title>VIP�ݒ�ύX</title>
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>VIP�ݒ�ύX</h3>
<hr>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
<?=$comment?>
<input type="submit" value="�ݒ�X�V">
<input type="hidden" name="mode" value="set">
<input type="hidden" name="bbs" value="<?=$_REQUEST['bbs']?>">
<table border="2">
<tr>
<th>���O�t�@�C���ێ���</th>
<td><input type="text" size="10" name="KEEPLOGCOUNT" value="<?=KEEPLOGCOUNT?>"></td>
</tr>
<tr>
<th>1�X���b�h�ɓ��e�ł��郌�X���̏��</th>
<td><input type="text" size="10" name="THREAD_RES" value="<?=THREAD_RES?>"></td>
</tr>
<tr>
<th>���X�I�[�o�[���̃��b�Z�[�W<br>���X���̕\��������<br>&lt;NUM&gt;<br>�Ə����Ă�������<br></th>
<td><textarea cols="60" rows="5" name="THREAD_MAX_MSG"><?=$maxmsg?></textarea></td>
</tr>
<tr>
<th>1�X���b�h�̏���i�o�C�g�j</th>
<td><input type="text" size="10" name="THREAD_BYTES" value="<?=THREAD_BYTES?>"></td>
</tr>
<tr>
<th>�摜�A�b�v���[�h</th>
<td><input type="radio" name="UPLOAD" value="1"<? if (UPLOAD) echo " checked"?>>����@<input type="radio" name="UPLOAD" value="0"<? if (!UPLOAD) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>�T���l�C���쐬</th>
<td><font size=2>
<input type="radio" name="GD_VERSION" value="0"<? if (GD_VERSION == 0) echo " checked"?>>�Ȃ� |
<input type="radio" name="GD_VERSION" value="1"<? if (GD_VERSION == 1) echo " checked"?>>����(GD Ver.1) |
<input type="radio" name="GD_VERSION" value="2"<? if (GD_VERSION == 2) echo " checked"?>>����(GD Ver.2)
</font></td>
</tr>
<tr>
<th>�A�b�v���[�h����i�o�C�g�j</th>
<td><input type="text" size="10" name="MAX_BYTES" value="<?=MAX_BYTES?>">php.ini�̐ݒ�i����2M�j�ȏ�ɂ��Ă����߂ł�����</td>
</tr>
<tr>
<th>�T���l�C���摜�̕�</th>
<td><input type="text" size="10" name="MAX_W" value="<?=MAX_W?>"></td>
</tr>
<tr>
<th>�T���l�C���摜�̍���</th>
<td><input type="text" size="10" name="MAX_H" value="<?=MAX_H?>"></td>
</tr>
</table>
VIP�@�\<br>
<table border="2">
<tr>
<th>���݂����@�\</th>
<td><input type="radio" name="OMIKUJI" value="1"<? if (OMIKUJI) echo " checked"?>>����@<input type="radio" name="OMIKUJI" value="0"<? if (!OMIKUJI) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>�싅�@�\</th>
<td><input type="radio" name="BASEBALL" value="1"<? if (BASEBALL) echo " checked"?>>����@<input type="radio" name="BASEBALL" value="0"<? if (!BASEBALL) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>�N���ǂ��ŋ@�\</th>
<td><input type="radio" name="WHO_WHERE" value="1"<? if (WHO_WHERE) echo " checked"?>>����@<input type="radio" name="WHO_WHERE" value="0"<? if (!WHO_WHERE) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>�ً@�\�i�����ĂȂ��̂Ŗ������j</th>
<td><input type="radio" name="TUBO" value="1"<? if (TUBO) echo " checked"?>>����@<input type="radio" name="TUBO" value="0"<? if (!TUBO) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>�����t�H���g�@�\</th>
<td><input type="radio" name="TELETYPE" value="1"<? if (TELETYPE) echo " checked"?>>����@<input type="radio" name="TELETYPE" value="0"<? if (!TELETYPE) echo " checked"?>>�Ȃ�</td>
</tr>
</table>
�X�����Ď��ɂ�����@�\<br>
<table border="2">
<tr>
<th>�X���b�h�����������ύX�@�\�u!774�i����!3�v</th>
<td><input type="radio" name="NAME_774" value="1"<? if (NAME_774) echo " checked"?>>����@<input type="radio" name="NAME_774" value="0"<? if (!NAME_774) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>�������֋����ϊ��@�\�u!774!force�i����!3�v</th>
<td><input type="radio" name="FORCE_774" value="1"<? if (FORCE_774) echo " checked"?>>����@<input type="radio" name="FORCE_774" value="0"<? if (!FORCE_774) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>ID�Ȃ��@�\�u!774!force!noid!3�v</th>
<td><input type="radio" name="FORCE_NO_ID" value="1"<? if (FORCE_NO_ID) echo " checked"?>>����@<input type="radio" name="FORCE_NO_ID" value="0"<? if (!FORCE_NO_ID) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>����sage�@�\�u!774!force!sage!3�v</th>
<td><input type="radio" name="FORCE_SAGE" value="1"<? if (FORCE_SAGE) echo " checked"?>>����@<input type="radio" name="FORCE_SAGE" value="0"<? if (!FORCE_SAGE) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>���X���L���b�v�K�{�@�\�u!774!force!stars!3�v</th>
<td><input type="radio" name="FORCE_STARS" value="1"<? if (FORCE_STARS) echo " checked"?>>����@<input type="radio" name="FORCE_STARS" value="0"<? if (!FORCE_STARS) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>�X���b�h��VIP�@�\�����u!774!normal!3�v</th>
<td><input type="radio" name="FORCE_NORMAL" value="1"<? if (FORCE_NORMAL) echo " checked"?>>����@<input type="radio" name="FORCE_NORMAL" value="0"<? if (!FORCE_NORMAL) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>���O���͋����@�\�u!774!name!3�v</th>
<td><input type="radio" name="FORCE_NAME" value="1"<? if (FORCE_NAME) echo " checked"?>>����@<input type="radio" name="FORCE_NAME" value="0"<? if (!FORCE_NAME) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>0thelo�@�\�u!774!0thello!3#tripkey�v</th>
<td><input type="radio" name="ZEROTHELO" value="1"<? if (ZEROTHELO) echo " checked"?>>����@<input type="radio" name="ZEROTHELO" value="0"<? if (!ZEROTHELO) echo " checked"?>>�Ȃ�</td>
</tr>
<tr>
<th>�A�b�v���[�h�@�\�u!774!force!up!3�v</th>
<td><input type="radio" name="FORCE_UP" value="1"<? if (FORCE_UP) echo " checked"?>>����@<input type="radio" name="FORCE_UP" value="0"<? if (!FORCE_UP) echo " checked"?>>�Ȃ�</td>
</tr>
</table>
<input type="submit" value="�ݒ�X�V"><br>
</form>
<br>
</body></html>
