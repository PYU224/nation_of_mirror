<?php
$thread=20;
require("passcheck.php");
$comment = '';
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
#�@�X���b�h�폜
#==================================================
if (isset($_POST['mode']) and $_POST['mode'] == "del") {
	if (!is_file("../$_POST[bbs]/dat/$_POST[key].dat")) disperror("�d�q�q�n�q�I", "����Ȕ�or�X���b�h�Ȃ��ł��B");
	if (!isset($_POST['chk']) or $_POST['chk'] != "ok") {
		preg_match("/(.*)(\(\d+\))$/", $SUBJECT[$_POST['key']], $match);
		?>
<html>
<head>
<title>�X���b�h�폜</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>�X���b�h�폜</h3>
<hr>
�X���b�h�F<a class="item" href="../test/read.php/<?=$_POST['bbs']."/".$_POST['key']?>/l50">#<?=$_POST['bbs'].$_POST['key']?></a><br>
�^�C�g���F<font color="<?=$SETTING['BBS_SUBJECT_COLOR']?>"><b><?=$match[1]?></b></font><br>
���X���F<?=$match[2]?><br><br><br>
���̃X���b�h���폜���܂��B<br><br>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
 <input type=hidden name="bbs" value="<?=$_POST['bbs']?>">
 <input type=hidden name="key" value="<?=$_POST['key']?>">
 <input type=hidden name="mode" value="del">
 <input type=hidden name="chk" value="ok">
 <input type=submit value="�������Ⴆ�I">
</form>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
 <input type=hidden name="bbs" value="<?=$_POST['bbs']?>">
 <input type=submit value="���b�p��߂�">
</form>
</body></html>
<?php
		exit;
	}
	else {
		$delfile = "../$_POST[bbs]/dat/$_POST[key].dat";
		@chmod($delfile, 0644);
		unlink($delfile);
		@unlink("../$_POST[bbs]/html/$_POST[key].html");
		#�T�u�W�F�N�g���e���X�V
		$PAGEFILE = array();
		$new = '';
		if ($SUBJECTLIST) {
			foreach($SUBJECTLIST as $tmp){
				$tmp = rtrim($tmp);
				list($file, $value) = explode("<>", $tmp);
				if ("$_POST[key].dat" != $file) $new .= $tmp."\n";
				$filename = "../$_POST[bbs]/dat/$file";
				if (is_file($filename)) {
					#dat�����݂���ꍇ�̂ݍŌ�ɒǉ�
					if (preg_match("/(\d+)\.dat$/", $file, $match)) {
						array_push($PAGEFILE,$match[1]);
						$SUBJECT[$match[1]] = $value;
					}
				}
			}
		}
		$fp = fopen($subfile, "w");
		fputs($fp, $new);
		fclose($fp);
		$dir = opendir("../$_POST[bbs]/img");
		while (false !== ($file = readdir($dir))) {
			if ($file != "." and $file != "..") {
				// �g���q���Œ�łȂ��̂ŃL�[�őI��
				if (strstr($file, $_POST['key'])) {
					unlink ("../$_POST[bbs]/img/$file");
				}
			}
		}
		closedir($dir);
		$dir = opendir("../$_POST[bbs]/img2");
		while (false !== ($file = readdir($dir))) {
			if ($file != "." and $file != "..") {
				if (strstr($file, $_POST['key'])) {
					unlink ("../$_POST[bbs]/img2/$file");
				}
			}
		}
		closedir($dir);
		#0thello �t�@�C���폜
		@unlink("../$_POST[bbs]/0thello/$_POST[key].dat");
		#threadconf ���O�폜
		$threadconf = file("../$_POST[bbs]/threadconf.cgi");
		$fp = fopen("../$_POST[bbs]/threadconf.cgi", "w");
		foreach($threadconf as $key=>$val) {
			if (!strstr($val, $_POST['key'])) fwrite($fp, $val);
		}
		fclose($fp);
		$comment = '<font color="red">�X���b�h���폜���܂����B���j���[��<b>index.html����蒼��</b>���N���b�N���Ă��������B</font><br>';
	}
}
#==================================================
#�@�X���b�h�ړ�
#==================================================
if (isset($_POST['mode']) and $_POST['mode'] == "mov") {
	if (!is_file("../$_POST[bbs]/dat/$_POST[key].dat")) disperror("�d�q�q�n�q�I", "����Ȕ�or�X���b�h�Ȃ��ł��B");
	if (!isset($_POST['chk']) or $_POST['chk'] != "html") {
		preg_match("/(.*)(\(\d+\))$/", $SUBJECT[$_POST['key']], $match);
		?>
<html>
<head>
<title>�X���b�h�ړ�</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>�X���b�h�ړ�</h3>
<hr>
�X���b�h�F<a class="item" href="../test/read.php/<?=$_POST['bbs']."/".$_POST['key']?>/l50">#<?=$_POST['bbs'].$_POST['key']?></a><br>
�^�C�g���F<font color="<?=$SETTING['BBS_SUBJECT_COLOR']?>"><b><?=$match[1]?></b></font><br>
���X���F<?=$match[2]?><br><br><br>
���̃X���b�h���ړ����܂��B<br><br>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
 <input type=hidden name="bbs" value="<?=$_POST['bbs']?>">
 <input type=hidden name="key" value="<?=$_POST['key']?>">
 <input type=hidden name="mode" value="mov">
 <input type=hidden name="chk" value="html">
 <input type=submit value="HTML�����ĉߋ����O�q�ɂ�">
</form>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
 <input type=hidden name="bbs" value="<?=$_POST['bbs']?>">
 <input type=submit value="���b�p��߂�">
</form>
</body></html>
<?php
		exit;
	#=========================
	#�@�ߋ����O�q��
	#=========================
	}
	else {
		$log = file("../$_POST[bbs]/dat/$_POST[key].dat");
		list(,,,,$subject) = explode("<>", $log[0]);
		$html = <<<EOF
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>$subject</title>
<style type=text/css><!--
img {border:0;}
--></style>
</head>
<body text="$SETTING[BBS_TEXT_COLOR]" bgcolor="$SETTING[BBS_THREAD_COLOR]" link="$SETTING[BBS_LINK_COLOR]" alink="$SETTING[BBS_ALINK_COLOR]" vlink="$SETTING[BBS_VLINK_COLOR]">
<a href="../">���f���ɖ߂遡</a>
<dl>
<font size=+1 color="$SETTING[BBS_SUBJECT_COLOR]">$subject</font></b>
EOF;
		$fp = @fopen("../$_POST[bbs]/kako/$_POST[key].html", "w");
		fputs($fp, $html);
		$s = 1;
		foreach($log as $tmp) {
			$tmp = chop($tmp);
			list($name,$mail,$time,$message,$subject) = explode("<>", $tmp);
			if (!$message) {
				$name='';
				$mail='';
				$time='[�������Ă܂�]';
				$message='[�������Ă܂�]';
			}
			$message = preg_replace("/(https?|ftp):\/\/([\x21-\x7E]+)/",'<a href="$1://$2" target="_blank">$1://$2</a>',$message);
			$message = str_replace("../test/read.php/$_POST[bbs]/$_POST[key]/",'#', $message);
			$message = preg_replace("|<([^>]+\"\.\./)$_POST[bbs]/|", "<$1", $message);
			$mailto = $mail ? "<a href=\"mailto:$mail\"><b> $name </b></a>" : "<font color=\"$SETTING[BBS_NAME_COLOR]\"><b>$name</b></font>";
			fputs($fp, "<dt><a name=\"$s\">$s</a> ���O�F $mailto ���e���F $time<br><dd> $message <br><br><br>\n");
			$s++;
		}
		fputs($fp, "</dl>\n<p>\n<hr>\n</body>\n</html>");
		fclose($fp);
		@chmod("../$_POST[bbs]/dat/$_POST[key].dat", 0644);
		unlink("../$_POST[bbs]/dat/$_POST[key].dat");
		@unlink("../$_POST[bbs]/html/$_POST[key].html");
		$fp = fopen("../$_POST[bbs]/kako/kako.txt", "a");
		fputs($fp, "$_POST[key] �� <a href=\"$_POST[key].html\">".$SUBJECT[$_POST['key']]."</a><br>\n");
		fclose($fp);
		#�T�u�W�F�N�g���e���X�V
		$PAGEFILE = array();
		$new = '';
		if ($SUBJECTLIST) {
			foreach($SUBJECTLIST as $tmp) {
				$tmp = rtrim($tmp);
				list($file, $value) = explode("<>", $tmp);
				if ("$_POST[key].dat" != $file) $new .= $tmp."\n";
				$filename = "../$_POST[bbs]/dat/$file";
				if (is_file($filename)) {
					#dat�����݂���ꍇ�̂ݍŌ�ɒǉ�
					if (preg_match("/(\d+)\.dat$/", $file, $match)) {
						array_push($PAGEFILE,$match[1]);
						$SUBJECT[$match[1]] = $value;
					}
				}
			}
		}
		#�T�u�W�F�N�g�t�@�C���X�V
		$fp = fopen($subfile, "w");
		fputs($fp, $new);
		fclose($fp);
		#0thello �t�@�C���폜
		@unlink("../$_POST[bbs]/0thello/$_POST[key].dat");
		$threadconf = file("../$_POST[bbs]/threadconf.cgi");
		foreach($threadconf as $key=>$val) {
			if (strstr($val, $_POST['key'])) unset($threadconf[$key]);
		}
		$fp = fopen("../$_POST[bbs]/threadconf.cgi", "w");
		fwrite($fp, implode("\n", $threadconf));
		fclose($fp);
		$comment = '<font color="red">�X���b�h���ړ����܂����B���j���[��<b>index.html����蒼��</b>���N���b�N���Ă��������B</font><br>';
	}
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
<title>�X���b�h�폜/�ړ�</title>
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>�X���b�h�폜/�ړ�</h3>
<hr>
<?=$comment?>
<b>�X���b�h�폜</b>�͂��̃X���b�h��S�č폜���܂��B<br>
<b>�X���b�h�ړ�</b>�̓X���b�h��HTML�����ĉߋ����O�f�B���N�g���Ɉړ����܂��B<br>
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
<tr><th>�X���b�h�L�[</th><th>�^�C�g��</th><th>�@</th><th>�@</th></tr>
<?php
for ($i = $st; $i < $st+$thread; $i++) {
	if (!isset($PAGEFILE[$i])) break;
	$tmp = $PAGEFILE[$i];
	?>
<tr><td> <a class="item" href="../test/read.php/<?=$_REQUEST['bbs']."/".$tmp?>/l50">#<?=$_REQUEST['bbs'].$tmp?></a> </td><td><?=$SUBJECT[$tmp]?></td>
<td>
 <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
 <input type=hidden name="bbs" value="<?=$_REQUEST['bbs']?>">
 <input type=hidden name="key" value="<?=$tmp?>">
 <input type=hidden name="mode" value="del">
 <input type=submit value="�X���b�h�폜">
 </form>
</td>
<td>
 <form action="threadm.php" method="POST">
 <input type=hidden name="bbs" value="<?=$_REQUEST['bbs']?>">
 <input type=hidden name="key" value="<?=$tmp?>">
 <input type=hidden name="mode" value="mov">
 <input type=submit value="�X���b�h�ړ�">
 </form>
</td>
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