<?php
require("passcheck.php");
if (!isset($_GET['bbs'])) $_GET['bbs'] = '';
#=====================================
#�@�Ǘ����j���[
#=====================================
$board = array();
$handle = opendir('../');
while (false !== ($file = readdir($handle))) { 
    if($file != 'admin' and $file != 'test' and $file != '.' and $file != '..') {
    	if (is_dir("../$file") and is_file("../$file/SETTING.TXT") and !is_file("../$file/admin.php")) array_push($board, $file);
    }
}
closedir($handle);
$make_board = '';
#if (!ini_get("safe_mode")) $make_board = '<a class="menu" href="makeboard.php"><b>�f���쐬</b></a><br>'."\n<hr>\n";
if (isset($_GET['bbs'])and $_GET['bbs']) $func = "func('$_GET[bbs]');";
else $func = '';
?>
<html>
<head>
<title>�Ǘ����j���[</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" href="menu.css" type="text/css">
<script type="text/javascript">
<!--
function init() {
  if (!document.getElementsByTagName) { return; }
  var objs = document.getElementsByTagName("div");
  for (i = 0; i < objs.length; i++) {
    if (objs[i].className == "titem") {
      objs[i].style.display = "none";
    }
  }
}
function func(id) {
  if (!document.getElementsByTagName) { return false; }
  var obj = document.getElementById(id);
  if (obj.style.display == "block") {
    obj.style.display = "none";
  } else {
    obj.style.display = "block";
  }
  return false;
}
// -->
</script>
<base target="main">
<!--nobanner-->
</head>
<body onload="init();<?=$func?>">
<div class="menu"><b>�Ǘ����j���[</b></div>
<hr>
<a class="menu" href="main.php"><b>�g�b�v</b></a><br>
<hr>
<?=$make_board?>
<a class="menu" href="cap.php"><b>�L���b�v�Ǘ�</b></a><br>
<hr>
<?php
$i = 0;
foreach ($board as $dir) {
	#====================================================
	#�@�������̎擾�i�ݒ�t�@�C���j
	#====================================================
	#�ݒ�t�@�C����ǂ�
	$set_pass = "../$dir/SETTING.TXT";
	$set_str = file($set_pass);
	foreach ($set_str as $tmp){
		$tmp = chop($tmp);
		list ($name, $value) = explode("=", $tmp);
		$SETTING[$name] = $value;
	}
	$i++;
	?>
<a class="title" href="../<?=$dir?>/"><b><?=$SETTING['BBS_TITLE']?></b></a><br>
<a class="dir" href="#" target="menu" onclick="return func('<?=$dir?>')">�f�B���N�g���F<?=$dir?></a><br>
<div class="titem" id="<?=$dir?>">
  <ul>
    <li><a class="item" href="setboard.php?bbs=<?=$dir?>"> �ݒ�ύX </a></li>
    <li><a class="item" href="setboard2.php?bbs=<?=$dir?>"> VIP�ݒ�ύX </a></li>
    <li><a class="item" href="vip.php?bbs=<?=$dir?>"> VIP�@�\�ύX </a></li>
    <li><a class="item" href="abon.php?bbs=<?=$dir?>"> ���ځ[��/�X���X�g </a></li>
    <li><a class="item" href="threadm.php?bbs=<?=$dir?>"> �X���b�h�폜/�ړ� </a></li>
    <li><a class="item" href="deleboard.php?bbs=<?=$dir?>"> �f���� </a></li>
    <li><a class="item" href="image.php?bbs=<?=$dir?>"> �摜�폜 </a></li>
    <li><a class="item" href="hostlog.php?bbs=<?=$dir?>"> �z�X�g���O�Ǘ� </a></li>
    <li><a class="item" href="deny.php?bbs=<?=$dir?>"> �A�N�֏��� </a></li>
    <li><a class="item" href="edit.php?bbs=<?=$dir?>"> �e�L�X�g�ҏW </a></li>
    <li><a class="item" href="makeboard.php?mode=remake&bbs=<?=$dir?>">index.html����蒼��</a></li>
  </ul>
</div>
<hr class="sub">
<?php
}
?>
</body></html>
