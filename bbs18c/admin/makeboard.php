<?php
require 'passcheck.php';
#====================================================
#�@�e��o�`�s�g����
#====================================================
if (!isset($_GET['bbs'])) $_GET['bbs'] = '';
if (!isset($_GET['mode'])) $_GET['mode'] = '';
$PATH = "../" . $_GET['bbs'] . '/';
$DATPATH = $PATH . "dat/";
$TEMPPATH = $PATH . "html/";
$INDEXFILE = $PATH . "index.html";
$SUBFILE = $PATH . "subback.html";
$IMODEFILE = $PATH."i/index.html";
if ($_GET['bbs']) require $PATH.'config.php';
#====================================================
#�@�f���V�K�쐬
#====================================================
if ($_GET['mode'] == 'make') {
/*
	if (is_dir($PATH)) disperror("�d�q�q�n�q�I", "�������O�̃f�B���N�g�������݂��܂�");
	mkdir($PATH, 0755);
	mkdir($DATPATH, 0755);
	mkdir($TEMPPATH, 0755);
	mkdir($PATH."kako", 0755);
	mkdir($PATH."temp", 0755);
	mkdir($PATH."i", 0755);
	mkdir($PATH."img", 0755);
	mkdir($PATH."img2", 0755);
	mkdir($PATH."0thello", 0755);
	copy("SETTING.TXT", $PATH."SETTING.TXT");
	copy("kako_index.txt", $PATH."kako/index.php");
	copy("config.php", $PATH."config.php");
	touch($PATH."kako/kako.txt");
	touch($PATH."head.txt");
	touch($PATH."subject.txt");
	touch($PATH."hostlog.cgi");
	touch($PATH."uerror.cgi");
	touch($PATH."timecheck.cgi");
	touch($PATH."RIP.cgi");
	touch($PATH."threadconf.cgi");
	header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME'])."/setboard.php?bbs=$_GET[bbs]");
	exit;
*/
}
#====================================================
#�@index.html����蒼��
#====================================================
elseif ($_GET['mode'] == 'remake') {
	#====================================================
	#�@�������̎擾�i�ݒ�t�@�C���j
	#====================================================
	#�ݒ�t�@�C����ǂ�
	$set_pass = $PATH . "SETTING.TXT";
	if (is_file($set_pass)) {
		$set_str = file($set_pass);
		foreach ($set_str as $tmp){
			$tmp = trim($tmp);
			list ($name, $value) = explode("=", $tmp);
			$SETTING[$name] = $value;
		}
	}
	else disperror("�d�q�q�n�q�I", "�d�q�q�n�q�F���[�U�[�ݒ肪�������Ă��܂��I");
	if (!is_dir($PATH)) disperror("�d�q�q�n�q�I","����ȔȂ��ł��I");
	if (isset($_GET['check']) and $_GET['check'] == 'check') {
		# �T�u�W�F�N�g�t�@�C����ǂݍ���
		$PAGEFILE = array();
		$subjectfile = $PATH."subject.txt";
		$subr = @file($subjectfile);
		$subbak = '';
		if ($subr) {
			foreach($subr as $tmp){
				$tmp = chop($tmp);
				list ($file, $value) = explode("<>", $tmp);
				if (!$file) break;
				$filename = "$DATPATH/$file";
				if (is_file($filename)){ # dat�����݂���ꍇ���Ԃɒǉ�
					array_push($PAGEFILE,$file);
					$subbak .= $tmp."\n";
					$SUBJECT[$file] = $value;
				}
			}
			$fp = fopen($PATH."subject.txt", "w");
			fputs($fp, $subbak);
			fclose($fp);
		}
		$NOWTIME = time();
		if (GD_VERSION) {
			$enctype = 'multipart/form-data';
			$file_form = '<input type=file name=file size=50><br>';
		}
		else {
			$enctype = 'application/x-www-form-urlencoded';
			$file_form = '';
		}
		if ($SETTING['BBS_TITLE_PICTURE']) $bbs_title = '<a href="'.$SETTING['BBS_TITLE_LINK'].'"><img src="'.$SETTING['BBS_TITLE_PICTURE'].'" border="0"></a>';
		else $bbs_title = '<h1><font color="'.$SETTING['BBS_TITLE_COLOR'].'">'.$SETTING['BBS_TITLE'].'</font></h1>';
		$set_cookie = '';
		$HOST = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		require('../test/make_html.php');
		exit;
	}
	// Parse error: Unclosed '{' ���������邽�߃R�����g�A�E�g
	//else {
	}
		?>
<html>
<head>
<title>index.html�쐬</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>index.html�쐬</h3>
<hr>
<form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
<input type="hidden" name="mode" value="remake">
<input type="hidden" name="bbs" value="<?=$_GET['bbs']?>">
<input type="hidden" name="check" value="check">
index.html����蒼���܂��B<br>
��낵���ł����B<br>
<br>
<input type="submit" value="��蒼��">
</form>
</body></html>
<?
		exit;
	}
}
?>
<html>
<head>
<title>�f���쐬</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<h3>�f���쐬</h3>
<hr>
<form action="<?=$_SERVER['PHP_SELF']?>" method="GET">
<input type="hidden" name="mode" value="make">
�V�����f���̃f�B���N�g���������߂Ă��������B<br>
�i�G���[���o����A�t�@�C�������Ȃ��Ƃ��́A�蓮�Ńt�@�C�����A�b�v���Ă��������B�j<br><br>
<input type="text" name="bbs" size="10">
<input type="submit" value="����">
</form>
</body></html>
