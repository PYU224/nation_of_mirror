<?php
$HOST = gethostbyaddr($_SERVER['REMOTE_ADDR']);
#====================================================
#�@���t�E������ݒ�
#====================================================
$NOWTIME = time();
$wday = array('��','��','��','��','��','��','�y');
$today = getdate($NOWTIME);
$JIKAN = $today['hours'];
$DATE = date("Y/m/d(", $NOWTIME).$wday[$today['wday']].date(") H:i:s", $NOWTIME);
#====================================================
#�@�e��o�`�s�g����
#====================================================
$PATH		= "../".$_POST['bbs']."/";
$DATPATH	= $PATH."dat/";
$TEMPPATH	= $PATH."html/";
$INDEXFILE	= $PATH."index.html";
$SUBFILE	= $PATH."subback.html";
$IMODEFILE	= $PATH."i/index.html";
$IMGPATH	= $PATH."img/";
$IMGPATH2	= $PATH."img2/";
if (!isset($_POST['subject'])) $_POST['subject'] = '';
if (!isset($_POST['FROM'])) $_POST['FROM'] = '';
if (!isset($_POST['mail'])) $_POST['mail'] = '';
if (!isset($_POST['bbs'])) $_POST['bbs'] = '';
if (!isset($_POST['key'])) $_POST['key'] = '';
if (!isset($_POST['MESSAGE'])) $_POST['MESSAGE'] = '';
#====================================================
#�@�������̎擾�i�ݒ�t�@�C���j
#====================================================
#�ݒ�t�@�C����ǂ�
$set_file = $PATH . "SETTING.TXT";
if (is_file($set_file)) {
	$set_str = file($set_file);
	foreach ($set_str as $tmp){
		$tmp = trim($tmp);
		list ($name, $value) = explode("=", $tmp);
		$SETTING[$name] = $value;
	}
}
#�ݒ�t�@�C�����Ȃ��iERROR)
else DispError("�d�q�q�n�q�I","�d�q�q�n�q�F���[�U�[�ݒ肪�������Ă��܂��I$PATH");
require $PATH.'config.php';
#====================================================
#�@���͏����擾�i�o�n�r�s�j
#====================================================
if ($_SERVER['REQUEST_METHOD'] != 'POST') DispError ("�d�q�q�n�q�I","�d�q�q�n�q�F�s���ȓ��e�ł��I");
// if (get_magic_quotes_gpc()) $_POST = array_map("stripslashes", $_POST);
// Fatal error: Uncaught Error: Call to undefined function get_magic_quotes_gpc() in ���o���̂ŏC���B
$_POST = array_map("stripslashes", $_POST);
$_POST['subject'] = str_replace('"', "&quot;", $_POST['subject']);
$_POST['subject'] = str_replace("<", "&lt;", $_POST['subject']);
$_POST['subject'] = str_replace(">", "&gt;", $_POST['subject']);
$_POST['subject'] = str_replace("'", "&#039;", $_POST['subject']);
$_POST['subject'] = str_replace(array("\r\n","\r","\n"), " ", $_POST['subject']);
$_POST['FROM'] = htmlspecialchars($_POST['FROM']);
$_POST['FROM'] = str_replace(array("\r\n","\r","\n"), " ", $_POST['FROM']);
$_POST['mail'] = htmlspecialchars($_POST['mail'], ENT_QUOTES);
$_POST['mail'] = str_replace(array("\r\n","\r","\n"), " ", $_POST['mail']);
$_POST['bbs'] = str_replace(array(".","/","|"), "", $_POST['bbs']);
$_POST['key'] = str_replace(array(".","/","|"), "", $_POST['key']);
$_POST['MESSAGE'] = rtrim($_POST['MESSAGE']);
$_POST['MESSAGE'] = str_replace('"', "&quot;", $_POST['MESSAGE']);
$_POST['MESSAGE'] = str_replace("<", "&lt;", $_POST['MESSAGE']);
$_POST['MESSAGE'] = str_replace(">", "&gt;", $_POST['MESSAGE']);
$_POST['MESSAGE'] = str_replace(array("\r\n","\r","\n"), " <br> ", $_POST['MESSAGE']);
# ���j�R�[�h�ϊ�
if($SETTING['BBS_UNICODE'] == "change"){
	$_POST['subject'] = preg_replace("/\&\#\d+\;/", "�H", $_POST['subject']);
	$_POST['MESSAGE'] = preg_replace("/\&\#\d+\;/", "�H", $_POST['MESSAGE']);
}
$_POST['MESSAGE'] = str_replace("'", "&#039;", $_POST['MESSAGE']);
# �m�f���[�h
#$_POST['FROM'] = str_replace("�Ǘ�", '"�Ǘ�"', $_POST['FROM']);
#$_POST['FROM'] = str_replace("�ǒ�", '"�ǒ�"', $_POST['FROM']);
#$_POST['FROM'] = str_replace("����", '"����"', $_POST['FROM']);
#$_POST['FROM'] = str_replace("�폜", '"�폜"', $_POST['FROM']);
#$_POST['FROM'] = str_replace("sakujyo", '"sakujyo"', $_POST['FROM']);
# �U�L���b�v�A�U�g���b�v�ϊ�
$_POST['FROM'] = str_replace("��", "��", $_POST['FROM']);
$_POST['FROM'] = str_replace("��", "��", $_POST['FROM']);
# �S�p���̃p�X�R��h�~
#�����o�O�̂���mb_ereg_replace�ɂ��悤���Ƃ��l���������~
#$_POST['FROM'] = str_replace("��", "#", $_POST['FROM']);
#$_POST['mail'] = str_replace("��", "#", $_POST['mail']);
#====================================================
#�@�z�X�g�̔���
#====================================================
$PROXY = '';
if (isset($_SERVER['HTTP_VIA']) and preg_match("/.*\s(\d+)\.(\d+)\.(\d+)\.(\d+)/", $_SERVER['HTTP_VIA'], $match)) {
	$PROXY = "$match[1].$match[2].$match[3].$match[4]";
}
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) and preg_match("/^(\d+)\.(\d+)\.(\d+)\.(\d+)(\D*).*/", $_SERVER['HTTP_X_FORWARDED_FOR'], $match)) {
	$PROXY = "$match[1].$match[2].$match[3].$match[4]";
}
if (isset($_SERVER['HTTP_FORWARDED']) and preg_match("/.*\s(\d+)\.(\d+)\.(\d+)\.(\d+)/", $_SERVER['HTTP_FORWARDED'], $match)) {
	$PROXY = "$match[1].$match[2].$match[3].$match[4]";
}
if ($PROXY) {
	$PROXY = gethostbyaddr($PROXY);
	$HOST .= "<$PROXY>";
}
#==================================================
#�@�A�N�Z�X�K��
#==================================================
# �s���o�q�n�w�x�g�p�ł����A�A�A�H
# �v���L�V�����̎��{
/**** 2ch BBQ
if(gethostbyname(join('.',array_reverse(explode( ".", $_SERVER['REMOTE_ADDR'])).'.niku.2ch.net') == "127.0.0.2") {
	$PROXY = $_SERVER['REMOTE_ADDR'];
}
#*******/
if ($PROXY and ($SETTING['BBS_PROXY_CHECK'] == "checked")) {
	DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�o�q�n�w�x�K�����I");
}
if ($SETTING['BBS_OVERSEA_PROXY'] == "checked" and !preg_match("/\.jp</i", $HOST)) {
	DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�o�q�n�w�x�K�����I");
}
if ($SETTING['BBS_OVERSEA_THREAD'] == "checked" and $_POST['subject'] and !preg_match("/\.jp$/i", $HOST) and !preg_match("/\.bbtec\.net$/", $HOST)) {
	DispError("�d�q�q�n�q�I","jp�h���C������X���b�h���ĂĂ�������");
}
#-------------------------------�A�N�Z�X���ۃ��X�g
if (is_file($PATH."uerror.cgi")){
	$IN = file($PATH."uerror.cgi");
	foreach ($IN as $tmp){
		$tmp = trim($tmp);
		if (stristr($HOST, $tmp)) DispError("�d�q�q�n�q�I","���[�U�[�ݒ肪�ُ�ł��I");
		if (stristr($_SERVER['REMOTE_ADDR'], $tmp)) DispError("�d�q�q�n�q�I","���[�U�[�ݒ肪�ُ�ł��I");
	}
}
#====================================================
#�@�V�K�X���b�h���
#====================================================
$enctype = 'application/x-www-form-urlencoded';
$file_form = '';
if (UPLOAD) {
	$enctype = 'multipart/form-data';
	$file_form = '<input type=file name=file size=50><br>';
}
if ($SETTING['BBS_TITLE_PICTURE']) $bbs_title = '<a href="'.$SETTING['BBS_TITLE_LINK'].'"><img src="'.$SETTING['BBS_TITLE_PICTURE'].'" border="0"></a>';
else $bbs_title = '<h1><font color="'.$SETTING['BBS_TITLE_COLOR'].'">'.$SETTING['BBS_TITLE'].'</font></h1>';
if (isset($_POST['new']) and $_POST['new'] == "thread") {
	header("Content-Type: text/html; charset=Shift_JIS");
	require('new_thread.php');
	exit;
}
#====================================================
#�@�N�b�L�[���s
#====================================================
#�L������������
$exptime = 24 * 60 * 60;
$exptime *= 90;	#�L���������悶��
$exptime += $NOWTIME;
$exp = date("D, j-M-Y H:i:s ", $exptime).'GMT';
$set_cookie = '<script type="text/javascript"><!-- 
';
if ($SETTING['BBS_NAMECOOKIE_CHECK']) {
	$set_cookie .= 'cookname = escape("'.addslashes($_POST['FROM']).'"); document.cookie = "NAME="+cookname+"; expires='.$exp.'; path=/"; ';
}
if ($SETTING['BBS_MAILCOOKIE_CHECK']) {
	$set_cookie .= 'cookmail = escape("'.addslashes($_POST['mail']).'"); document.cookie = "MAIL="+cookmail+"; expires='.$exp.'; path=/"; ';
}
$set_cookie .= '//--></script>';
#====================================================
#�@�V�K�X���b�h�ƕ��ʂ̏������݂̏��`�F�b�N
#====================================================
# �摜�p�̃J�E���^
$imgnum = 1;
if ($_POST['subject']) {
	do {
		#�T�u�W�F�N�g������ΐV�K�X���Ȃ̂ŃL�[�����݂ɐݒ�
		$_POST['key'] = time();
		#.dat�t�@�C���̐ݒ�
		$DATAFILE = $DATPATH.$_POST['key'].".dat";
	} while (is_file($DATAFILE)) ;
}
elseif ($_POST['key']) {
	#�L�[����������Ȃ��ꍇ�΂��΂��I
	if (preg_match("/\D/", $_POST['key'])) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�L�[��񂪕s���ł��I");
	#.dat�t�@�C���̐ݒ�
	$DATAFILE = $DATPATH.$_POST['key'].".dat";
	#.dat�����݂��ĂȂ��������Ȃ��Ȃ�΂��΂�
	if (!is_writable($DATAFILE)) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F���̃X���b�h�ɂ͏����܂���I");
	#.dat�̃T�C�Y���傫�����鎞�͂΂��΂�
	if (filesize($DATAFILE) > THREAD_BYTES) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F���̃X���b�h�� ".(int)(THREAD_BYTES/1024)."k �𒴂��Ă���̂ŏ����܂���I");
	# ���X�̑������摜�p�J�E���^��
	$fp = fopen($DATAFILE, "r");
	while ($tmp = fgets($fp)) $imgnum++;
	fclose($fp);
}
#�T�u�W�F�N�g���L�[�����݂��Ȃ��Ȃ�΂��΂�
else DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�T�u�W�F�N�g�����݂��܂���I");
#====================================================
#�@�t�B�[���h�T�C�Y�̔���
#====================================================
if (strlen($_POST['MESSAGE']) == 0) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�{��������܂���I");
if (strlen($_POST['mail']) > $SETTING['BBS_MAIL_COUNT']) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F���[���A�h���X���������܂��I");
$msg = explode("<br>", $_POST['MESSAGE']);
if (count($msg) > 50) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F���s���������܂��I");
foreach ($msg as $tmp) {
	if (strlen($tmp) > 256) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F��������s������܂��I");
}
if (strlen($_POST['MESSAGE']) > $SETTING['BBS_MESSAGE_COUNT']) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�{�����������܂��I");
if (preg_match_all("/&gt;&gt;[0-9]/", $_POST['MESSAGE'], $matches) > 16) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F���X�A���J�[�����N���������܂��I");
if (strlen($_POST['FROM']) > $SETTING['BBS_NAME_COUNT']) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F���O���������܂��I");
if (strlen($_POST['subject']) > $SETTING['BBS_SUBJECT_COUNT']) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�T�u�W�F�N�g���������܂��I");
#====================================================
#�@�������ݏ��̃`�F�b�N���⊮
#====================================================
#monazilla ����̏�������
#referer�`�F�b�N���g�т���̏�������
if (!isset($_SERVER['HTTP_REFERER']) or !$_SERVER['HTTP_REFERER']){
	#referer�������ꍇ�͌g�т���̏������݃`�F�b�N
	if (!strstr($_SERVER['HTTP_USER_AGENT'], 'DoCoMo') and
	!strstr($_SERVER['HTTP_USER_AGENT'], 'J-PHONE') and
	!strstr($_SERVER['HTTP_USER_AGENT'], 'UP.Browser'))
	DispError("�d�q�q�n�q�I","�d�q�q�n�q�F���t�@�����炢�����Ă���B");
}
/*
else {
	if (!stristr($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'])){
		DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�u���E�U�ςł����B");
	}
	if ($_SERVER['HTTP_HOST'] != $_SERVER['SERVER_NAME']){
		DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�u���E�U�ςł����B");
	}
}
*/
#====================================================
#�@�G���[���X�|���X�i���ʂ̃G���[�͂܂Ƃ߂Ă΂��΂��j
#====================================================
#�o�n�r�s���
if ($_POST['submit'] != "��������" and $_POST['submit'] != "�V�K�X���b�h�쐬" and $_POST['submit'] != "��������" and $_POST['submit'] != "��L�S�Ă��������ď�������") {
	DispError("�d�q�q�n�q�I","�d�q�q�n�q�F���[�U�[�ݒ肪�������Ă��܂��I");
}
#���Ԃ��ǂݍ��߂Ȃ�������΂��΂�
if (!$_POST['time']) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�t�H�[����񂪕s���ł��I");
#==================================================
#�@�X���b�h���Ă����`�F�b�N
#==================================================
if ($_POST['subject'] and $SETTING['BBS_THREAD_TATESUGI'] >= 2) {
	# �X�����Ď҂̃z�X�g���L�^�t�@�C����ǂݍ��ށiBBS_THREAD_TATESUGI�L�^����Ă���j
	$file = $PATH."RIP.cgi";
	$IP = array();
	if (is_file($file)) {
		$IP = file($file);
		foreach ($IP as $tmp) {
			$tmp = rtrim($tmp);
			# �L�^�t�@�C�����ɓ���z�X�g������΃G���[�B
			if ($HOST == $tmp) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�X���b�h���Ă����ł��B�B�B");
		}
	}
	array_unshift($IP, "$HOST\n");
	# �L�^�t�@�C�����̃z�X�g���� BBS_THREAD_TATESUGI �ȓ��ɒ������ĕۑ�
	while (count($IP) > $SETTING['BBS_THREAD_TATESUGI']) array_pop($IP);
	$fp = @fopen($file, "w");
	foreach($IP as $tmp) fputs($fp, "$tmp");
	fclose($fp);
}
#==================================================
#�@�N�b�L�[�H���`�F�b�N
#==================================================
if (!$_COOKIE and 
	!strstr($_SERVER['HTTP_USER_AGENT'], 'DoCoMo') and
	!strstr($_SERVER['HTTP_USER_AGENT'], 'J-PHONE') and
	!strstr($_SERVER['HTTP_USER_AGENT'], 'UP.Browser'))
	DispError("���e�m�F");
#==================================================
#�@�A�����e�K��
#==================================================
if ($SETTING['timecount'] >= 2) {
	# ���e�҂̃z�X�g���L�^�t�@�C����ǂݍ��ށitimecount�L�^����Ă���j
	$file = $PATH."timecheck.cgi";
	$IP = array();
	$count = 0;
	if (is_file($file)) {
		$IP = file($file);
		foreach($IP as $tmp){
			$tmp = rtrim($tmp);
			list($time1,$host1) = explode("<>", $tmp);
			if ($HOST == $host1) {
				# �������e�t�H�[������̏ꍇ��2�d�J�L�R�Ƃ��ăG���[
				if ($_POST['time'] == $time1) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�Q�d�J�L�R�ł����H�H");
				# �z�X�g�����������e�̐����J�E���g
				$count++;
			}
		}
	}
	# timecount �̓��e����timeclose �ȏ�̓��e������΃G���[
	if ($count >= $SETTING['timeclose']) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�A�����e�ł����H�H");
	array_unshift($IP, "$_POST[time]<>$HOST\n");
	# �L�^�t�@�C�����̃z�X�g���� timecount �ȓ��ɒ������ĕۑ�
	while (count($IP) > $SETTING['timecount']) array_pop($IP);
	$fp = @fopen($file, "w");
	foreach($IP as $tmp) fputs($fp, $tmp);
	fclose($fp);
}
#==================================================
#�@�L���b�v�A�g���b�v
#==================================================
# �h�c�𐶐�����
$idnum = substr($_SERVER['REMOTE_ADDR'], 8); 
$idcrypt = substr(crypt($idnum * $idnum, substr($DATE, 8, 2)), -8); 
$ID = " ID:".$idcrypt;
# ID�����\������Ȃ��ꍇ��mail���ɋL���������ID���B��
if ($_POST['mail'] and $SETTING['BBS_FORCE_ID'] != "checked") $ID = " ID:???";
# �L���b�v�A�g���b�v�̓N�b�L�[�H���`�F�b�N���I����Ă���ϊ����邱��
# �g���b�v
# $trip ��0thello�Ɏg�p
$trip = '';
if (preg_match("/([^\#]*)\#(.+)/", $_POST['FROM'], $match)) {
	$salt = substr($match[2]."H.", 1, 2);
	$salt = preg_replace("/[^\.-z]/", ".", $salt);
	$salt = strtr($salt,":;<=>?@[\\]^_`","ABCDEFGabcdef");
	$trip = substr(crypt($match[2], $salt),-10);
	$_POST['FROM'] = $match[1]."</b>��".$trip."<b>";
}
# �L���b�v
if (preg_match("/([^\#]*)\#(.+)/", $_POST['mail'], $cap)) {
	if (is_file("caps.cgi")) {
		$fp = fopen("caps.cgi", "r");
		while ($cap_data = fgets($fp, 1024)) {
			$cap_data = rtrim($cap_data);
			list($id1,$name1,$pass1,$color1) = explode("<>", $cap_data);
			if (crypt($cap[2], $pass1) == $pass1) {
				if ($_POST['FROM']) $_POST['FROM'] .= "��$name1 ��";
				else $_POST['FROM'] = "$name1 ��";
				if ($color1) $_POST['FROM'] = "<font color=\"$color1\">$_POST[FROM]</font>";
				$ID = " ID:???";
				break;
			}
		}
		fclose($fp);
	}
	$_POST['mail'] = $cap[1];
}
#####################################################
#�@���Ƃ�㩂�������Ȃ炱���ցB�B�B
#####################################################
$sage = 0;
$stars = 0;
@include 'bbs2.php';
if ($stars and !strpos($_POST['FROM'], '��')) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�L���b�v���Ȃ��ƃ��X�ł��܂���B�B�B");
# ���t����ID��������
$DATE_ID = $DATE;
# 'BBS_NO_ID', 'NANASHI_CHECK', 'BBS_NONAME_NAME' ��"bbs2.php"�ŕύX����Ă���\������
if ($SETTING['BBS_NO_ID'] != "checked") $DATE_ID .= $ID;
# �z�X�g�\���̏ꍇ�z�X�g����t��
if ($SETTING['BBS_DISP_IP'] == "checked") $DATE_ID .=" <font size=1>[ $HOST ]</font>";
# fusianasan�Ńz�X�g�\��
$_POST['FROM'] = str_replace("fusianasan", " </b>$HOST<b>", $_POST['FROM']);
# ���O���̓`�F�b�N�ƕ⊮
if ($SETTING['NANASHI_CHECK'] and !$_POST['FROM']) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F���O����Ă���B�B�B");
if (!$_POST['FROM']) $_POST['FROM'] = $SETTING['BBS_NONAME_NAME'];
#====================================================
#�@���X�|���X�A���J�[�i�{���j
#====================================================
$_POST['MESSAGE'] = preg_replace("/&gt;&gt;([0-9]+)(?![-\d])/", "<a href=\"../test/read.php/$_POST[bbs]/$_POST[key]/$1\" target=\"_blank\">&gt;&gt;$1</a>", $_POST['MESSAGE']);
$_POST['MESSAGE'] = preg_replace("/&gt;&gt;([0-9]+)\-([0-9]+)/", "<a href=\"../test/read.php/$_POST[bbs]/$_POST[key]/$1-$2\" target=\"_blank\">&gt;&gt;$1-$2</a>", $_POST['MESSAGE']);
#====================================================
#�@�t�@�C������i�z�X�g�L�^�j
#====================================================
# .dat�t�@�C����monazilla�֌W�Ŋی����Ȃ̂ŕʂɃz�X�g�L�^�p���O�t�@�C����p��
$fp = fopen($PATH."hostlog.cgi", "a");
flock($fp, 2);
fwrite($fp, "$_POST[FROM]<>$_POST[mail]<>$DATE $idcrypt<>".substr(strip_tags($_POST['MESSAGE']), 0, 30)."<>$_POST[subject]<>$HOST<>$_SERVER[REMOTE_ADDR]<>\n");
fclose($fp);
#====================================================
#�@�t�@�C������i�c�`�s�t�@�C���X�V�j
#====================================================
$outdat = "$_POST[FROM]<>$_POST[mail]<>$DATE_ID <> $_POST[MESSAGE] <>$_POST[subject]\n";
# $outdat�̒ǉ���html�t�@�C���̍쐬�i�߂�l��"�T�u�W�F�N�g�� (���X�̑���)"�j
require 'make_work.php';
$subtt = MakeWorkFile($_POST['bbs'], $_POST['key'], $outdat);
#====================================================
#�@�t�@�C������isubject.txt�j
#====================================================
$subjectfile = $PATH."subject.txt";
$keyfile = $_POST['key'].".dat";
$PAGEFILE = array();
# �T�u�W�F�N�g�t�@�C����ǂݍ���
# �X���b�h�L�[.dat<>�^�C�g�� (���X�̐�)\n
# $PAGEFILE = array('�X���b�h�L�[.dat',�E�E�E)
# $SUBJECT = array('�X���b�h�L�[.dat'=>'�^�C�g�� (���X�̐�)',�E�E�E)
$subr = @file($subjectfile);
if ($subr) {
	foreach ($subr as $tmp){
		$tmp = rtrim($tmp);
		list($file, $value) = explode("<>", $tmp);
		if (!$file) break;
		$filename = $DATPATH . $file;
		array_push($PAGEFILE,$file);
		$SUBJECT[$file] = $value;
	}
}
# �T�u�W�F�N�g�����擾
$FILENUM = count($PAGEFILE);
# �V�K�X���b�h�̏ꍇ��1�ǉ�
if ($_POST['subject']) $FILENUM++;
# ���O��萔�ɑ�����
if ($FILENUM > KEEPLOGCOUNT) {
	for ($start = KEEPLOGCOUNT; $start < $FILENUM; $start++) {
		$delfile = $DATPATH . $PAGEFILE[$start];
		# dat�t�@�C���폜
		unlink($delfile);
		$key = str_replace('.dat', '', $PAGEFILE[$start]);
		$delfile = $TEMPPATH . $key . ".html";
		# html�t�@�C���폜
		@unlink($delfile);
		if ($dir = @opendir($IMGPATH)) {
			while (($file = readdir($dir)) !== false) {
				# �摜�t�@�C���폜
				if (strpos($file, $key) === 0) unlink($IMGPATH.$file);
			}  
			closedir($dir);
		}
		if ($dir = @opendir($IMGPATH2)) {
			while (($file = readdir($dir)) !== false) {
				# �T���l�C���摜�t�@�C���폜
				if (strpos($file, $key) === 0) unlink($IMGPATH2.$file);
			}  
			closedir($dir);
		}
	}
	$FILENUM = KEEPLOGCOUNT;
	$PAGEFILE = array_slice($PAGEFILE, 0, $FILENUM);
}
$subtm = "$keyfile<>$subtt";
# �T�u�W�F�N�g�n�b�V��������������
$SUBJECT[$keyfile] = $subtt;
# �T�u�W�F�N�g�e�L�X�g���J��
$fp = @fopen($subjectfile, "w");
#�ꊇ��������
# sage�̎��͏オ��Ȃ�
if (!$_POST['subject'] and ($sage or strstr($_POST['mail'], 'sage'))) {
	foreach ($PAGEFILE as $tmp){
		fputs($fp, "$tmp<>$SUBJECT[$tmp]\n");
	}
}
else {
	# �オ��L�[�͈�ԍŏ��Ɏ����Ă���
	$temp[0] = $keyfile;
	$i = 1;
	fputs($fp, "$subtm\n");
	foreach ($PAGEFILE as $tmp) {
		# keyfile�͌��ݏ������݂����X���b�h�L�[�i�オ���Ă���j
		if ($tmp != $keyfile) {
			$temp[$i] = $tmp;
			$i++;
			fputs($fp, "$tmp<>$SUBJECT[$tmp]\n");
		}
	}
	$PAGEFILE = $temp;
}
fclose($fp);
#====================================================
#�@�{�g�s�l�k�f������
#====================================================
require 'make_html.php';
exit;
#====================================================
#�@�G���[��ʁi�G���[�����j�Ə������݊m�F���
#====================================================
#DispError(TITLE,TOPIC);
function DispError($title, $topic = "") {
	global $HOST, $NOWTIME;
	setcookie("PON", $HOST, $NOWTIME+3600*24*90, "/");
	header("Content-Type: text/html; charset=Shift_JIS");
	# $topic�������ꍇ�͏������݊m�F���
	if (!$topic) {
		$sbj = $_POST['subject'];
		$frm = $_POST['FROM'];
		$mml = $_POST['mail'];
		$msg = $_POST['MESSAGE'];
		$sbj = str_replace ("&amp;", "&", $sbj);
		$sbj = str_replace (" ", "\x1F", $sbj);
		$frm = str_replace ("&amp;", "&", $frm);
		$frm = str_replace (" ", "\x1F", $frm);
		$mml = str_replace ("&amp;", "&", $mml);
		$mml = str_replace (" ", "\x1F", $mml);
		$msg = str_replace (" <br> ", "\n", $msg);
		$msg = str_replace (" ", "\x1F", $msg);

		// Parse error: Unclosed '{' on line 468 ���o���̂ŏC��
		}

// Parse error: Unclosed '{' on line 463 ���o���̂ŏC��
}
		?>
<html><!-- 2ch_X:cookie --><head><title>�� �������݊m�F ��</title><meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS"></head><body bgcolor="#EEEEEE">
<font size="+1" color="#FF0000"><b>�������݁��N�b�L�[�m�F</b></font><ul><br><br><b><?=$_POST['subject']?> </b><br>���O�F<?=$_POST['FROM']?> <br>E-mail�F<?=$_POST['mail']?> <br>���e�F<br><?=$_POST['MESSAGE']?><br><br></ul>
<b>
<?=$title?><br>
�E���e�҂́A���e�Ɋւ��Ĕ�������ӔC���S�ē��e�҂ɋA�����Ƃ��������܂��B<br>
�E���e�҂́A�b��Ɩ��֌W�ȍL���̓��e�Ɋւ��āA�����̔�p���x�������Ƃ��������܂�<br>
�E���e�҂́A���e���ꂽ���e�ɂ��āA�f���^�c�҂��R�s�[�A�ۑ��A���p�A�]�ړ��̗��p���邱�Ƃ��������܂��B�܂��A�f���^�c�҂ɑ΂��āA����Ґl�i������؍s�g���Ȃ����Ƃ��������܂��B<br>
�E���e�҂́A�f���^�c�҂��w�肷���O�҂ɑ΂��āA���앨�̗��p��������؂��Ȃ����Ƃ��������܂��B
<br>

</b>
  <form method="post" action="../test/bbs.php" enctype="<?=$GLOBALS['enctype']?>">
    <input type="hidden" name="subject" value="<?=$sbj?>">
    <input type="hidden" NAME="FROM"  value="<?=$frm?>">
    <input type="hidden" NAME="mail"  value="<?=$mml?>">
    <input type="hidden" name="MESSAGE" value="<?=$msg?>"></ul>
    <input type="hidden" name="bbs" value="<?=$_POST['bbs']?>">
    <input type="hidden" name="time" value="<?=$_POST['time']?>">
    <input type="hidden" name="key" value="<?=$_POST['key']?>">

<br>
<?
if (isset($_FILES['file']['name']) and $_FILES['file']['name']) {
	echo "������x�t�@�C���̎w����s���Ă��������B<br>\n";
	echo '<input type="file" name="file" size="50"><br>';
}
?>
<input type="submit" value="��L�S�Ă��������ď�������" name="submit"><br>
</form>
�ύX����ꍇ�͖߂�{�^���Ŗ߂��ď��������ĉ������B<br><br>
<font size="-1">(cookie��ݒ肷��Ƃ��̉�ʂ͂łȂ��Ȃ�܂��B)</font><br>
</body></html>
<?
	}
	# $topic������Ƃ��̓G���[��ʕ\��
	else {
		?>
<html><head><title><?=$title?></title><meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS"></head><body bgcolor="#FFFFFF">
<font size="+1" color="#FF0000"><b><?=$topic?></b></font>
<ul><br>�z�X�g<b><?=$HOST?></b><br><b><?=$_POST['subject']?> </b><br>���O�F <?=$_POST['FROM']?><br>E-mail�F<?=$_POST['mail']?> <br>���e�F<br><?=$_POST['MESSAGE']?><br><br></ul>
<center>������Ń����[�h���Ă��������B<a href="../<?=$_POST['bbs']?>/"> GO! </a></center></body></html>
<?
	}
	exit();
}
?>