<?php
require("passcheck.php");
if (!isset($_GET['bbs'])) $_GET['bbs'] = '';
?>
<html>
<head>
<title>�Ǘ��l�̕���</title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
</head>
<frameset cols="170,*" border="1" frameborder="1" framespacing="0">
  <frame src="menu.php?bbs=<?=$_GET['bbs']?>" name="menu" frameborder="0">
  <frame src="main.php" name="main" frameborder="0">
  <noframes>
    <body>
    <p><b>�t���[��</b>���T�|�[�g�����u���E�U�ł̗��p�������߂��܂��B</p>
    <a href="menu.php">menu.php</a>
    </body>
  </noframes>
</frameset>
</html>
