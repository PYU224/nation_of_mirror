<?php
function DispError($msg) {
	echo "<HTML><BODY>$msg</BODY></HTML>";
	exit;
}
#====================================================
#�@�������̎擾�i�ݒ�t�@�C���j
#====================================================
#�ݒ�t�@�C����ǂ�
$set_pass = "../SETTING.TXT";
if (is_file($set_pass)) {
	$set_str = file($set_pass);
	foreach ($set_str as $tmp){
		$tmp = chop($tmp);
		list ($name, $value) = explode("=", $tmp);
		$SETTING[$name] = $value;
	}
}
else DispError("�d�q�q�n�q�F���[�U�[�ݒ肪�������Ă��܂��I");

$kakolog = file("kako.txt");
@sort($kakolog);
@reset($kakolog);
?>
<html>
<head>
<title><?=$SETTING['BBS_TITLE']?>�@�ߋ����O�q��</title>
</head>
<body>
<a href="..">���f���ɖ߂遡</a><p>
���V�����f�[�^�`��(teri�̃^�C�v)�̃X���b�h
<p>
<?php
if ($kakolog) {
	foreach ($kakolog as $tmp) {
		echo $tmp;
	}
}
?>
</body>
</html>