<?php
require("passcheck.php");
#=============================================
if (!$_GET['bbs']) disperror("�d�q�q�n�q�I","�d�q�q�n�q�F��������Ă���B�B�B");
if (!is_dir("../$_GET[bbs]")) disperror("�d�q�q�n�q�I","�d�q�q�n�q�F����ȔȂ��ł��B");
#====================================================
#�@�������̎擾�i�ݒ�t�@�C���j
#====================================================
#�ݒ�t�@�C����ǂ�
$set_pass = "../$_GET[bbs]/SETTING.TXT";
if (is_file($set_pass)) {
	$set_str = file($set_pass);
	foreach ($set_str as $tmp){
		$tmp = rtrim($tmp);
		list($name, $value) = explode("=", $tmp);
		$SETTING[$name] = $value;
	}
}
else disperror("�d�q�q�n�q�I","�d�q�q�n�q�F���[�U�[�ݒ肪�������Ă��܂��I");
#=====================================
#�@�܂邲�Ƃ��ځ[��
#=====================================
if(isset($_GET['del']) and $_GET['del'] == 'ok') {
	$handle = opendir("../$_GET[bbs]");
	while (false !== ($file = readdir($handle))) { 
		if($file != '.' and $file != '..') {
			if (is_dir("../$_GET[bbs]/$file")) {
				$handle2 = opendir("../$_GET[bbs]/$file");
				while (false !== ($file2 = readdir($handle2))) { 
					if ($file2 != '.' and $file2 != '..') @unlink("../$_GET[bbs]/$file/$file2");
				}
				closedir($handle2);
				@rmdir("../$_GET[bbs]/$file");
			}
			else @unlink("../$_GET[bbs]/$file");
		}
	}
	closedir($handle);
	@rmdir("../$_GET[bbs]");
	?>
<html>
<head>
<title>�f����</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>�f����</h3>
<hr>
�폜���܂���<br>
<a class="item" href="admin.php?bbs=<?=$_GET['bbs']?>" target="_parent">����</a>���烁�j���[�̍X�V�����Ă�������<br>
<br>
</body>
</html><?php
	exit;
}
?>
<html>
<head>
<title>�f����</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>
<h1 class="title"><?=$SETTING['BBS_TITLE']?></h1>
<h3>�f����</h3>
<hr>
�f�B���N�g��<q><?=$_GET['bbs']?></q>�ȉ��̃t�@�C����S�č폜���܂��B<br>
<br>
<a class="item" href="<?=$_SERVER['PHP_SELF']?>?del=ok&bbs=<?=$_GET['bbs']?>">�폜</a>�@<a class="item" href="admin.php?bbs=<?=$_GET['bbs']?>" target="_parent">��߂�</a><br>
</body></html>
