<?php
#�@�X�����ėp�t�H�[��
$version = "b.php ver1.2 (2004/06/25)";
#==================================================
#�@���N�G�X�g���
#==================================================
# PATH INFO����p�����[�^�����o���B
if(!isset($_SERVER['PATH_INFO']) or !$_SERVER['PATH_INFO']){echo("ERR - $version");exit;}
$buffer = $_SERVER['PATH_INFO'];
$pairs = explode('/',$buffer);
$bbs = $pairs[1];
if (!is_dir("../$bbs")) { echo("ERR - $version");exit; }
#==================================================
#�@�t�H�[���o��
#==================================================
?>
<body><form method=POST action="../../bbs.php">����<input name=subject>NAME�F<input name=FROM>MAIL�F<input name=mail istyle=3><input type=hidden name=bbs value=<?=$bbs?>><input type=hidden name=time value=<?=time()?>><textarea name=MESSAGE></textarea><input type=submit value="��������" name=submit></form><br><?=$version?></body>
