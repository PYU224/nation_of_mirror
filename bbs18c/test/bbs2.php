<?php
if ($_POST['subject']) {
	$force_no_id = 0;
	$force_sage = 0;
	$force_stars = 0;
	$force_normal = 0;
	$force_name = 0;
	$force_0thello = 0;
	$force_up = 0;
	if (preg_match("/(.*)!774(.*)!3(.*)/", $_POST['FROM'], $match)) {
		$_POST['FROM'] = $match[1].$match[3];
		$name_774 = $match[2];
		if (preg_match("/(.*)!force(.*)/", $name_774, $match)) {
			$name_774 = $match[1];
			$force_774 = $match[2];
			if (strstr($force_774, '!noid')) {
				$force_no_id = FORCE_NO_ID;
				$force_774 = str_replace('!noid', '', $force_774);
			}
			if (strstr($force_774, '!sage')) {
				$force_sage = FORCE_SAGE;
				$force_774 = str_replace('!sage', '', $force_774);
			}
			if (strstr($force_774, '!stars')) {
				$force_stars = FORCE_STARS;
				$force_774 = str_replace('!stars', '', $force_774);
			}
			if (strstr($force_774, '!up')) {
				$force_up = FORCE_UP;
				$force_774 = str_replace('!up', '', $force_774);
			}
		}
		if (strstr($name_774, '!normal')) {
			$force_normal = FORCE_NORMAL;
			$name_774 = str_replace('!normal', '', $name_774);
		}
		if (strstr($name_774, '!name')) {
			$force_name = FORCE_NAME;
			$name_774 = str_replace('!name', '', $name_774);
		}
		if ($name_774 == '!0thello') {
			$force_0thello = ZEROTHELO;
			$name_774 = '';
			touch($path."0thello/".$_POST['key'].".dat");
		}
	}
	if (!NAME_774) $name_774 = '';
	if (!FORCE_774) $force_774 = '';
	$fp  = fopen($PATH."threadconf.cgi", "a");
	fwrite($fp, $_POST['key'].",".$name_774.",".$force_774.",".$force_no_id.",".$force_sage.",".$force_stars.",".$force_normal.",".$force_name.",".$force_0thello.",".$force_up."\n");
	fclose($fp);
}

$fp  = fopen($PATH."threadconf.cgi", "r");
while ($vip = fgetcsv($fp, 1024)) {
	if ($vip[0] == $_POST['key']) break;
	else $vip[0] = 0;
}
fclose($fp);
$upload = 0;
if ($vip[6] == 0) {
	# �����t�H���g
	if (strstr($_POST['FROM'], '!tt')) {
		$_POST['FROM'] = str_replace('!tt', '', $_POST['FROM']);
		$_POST['MESSAGE'] = '<tt>'.$_POST['MESSAGE'].'</tt>';
	}
	# �������ύX
	if ($vip[1]) $SETTING['BBS_NONAME_NAME'] = $vip[1];
	# ����������
	if ($vip[2]) $_POST['FROM'] = $vip[2];
	# ID����
	if ($vip[3]) $SETTING['BBS_NO_ID'] = "checked";
	# ����sage
	if ($vip[4]) $sage = 1;
	# �L���b�v�ȊO���X�s��
	if ($vip[5]) $stars = 1;
	# ���O�K�{
	if ($vip[7]) $SETTING['NANASHI_CHECK'] = "checked";
	# �[���Z��
	if ($vip[8]) @include '0thello.php';
	# �摜�A�b�v
	if ($vip[9]) $upload = 1;
	$dir = "./omikuji/";
	if (OMIKUJI) {
		$omikuji_array = file($dir.'omikuji.txt');
	}
	if (BASEBALL) {
		$base_array = file($dir.'base.txt');
	}
	if (WHO_WHERE) {
		$who_array = file($dir.'who.txt');
		$where_array = file($dir.'where.txt');
		$do_array = file($dir.'do.txt');
		$food_array = file($dir.'food.txt');
	}
	$i = 0;
	while ($i < 10) {
		$j = $i;
		if (OMIKUJI) {
			$count = count($omikuji_array) - 1;
			if ((strpos($_POST['FROM'], '!omikuji') !== FALSE) and $i++ < 10) {
				$random = rand(0, $count);
				$_POST['FROM'] = preg_replace("/!omikuji/", "</b>�y".trim($omikuji_array[$random])."�z<b>", $_POST['FROM'], 1);
			}
		}
		if (BASEBALL) {
			$count = count($base_array) - 1;
			if ((strpos($_POST['MESSAGE'], '!base') !== FALSE) and $i++ < 10) {
				$random = rand(0, $count);
				$_POST['MESSAGE'] = preg_replace("/!base/", "<b>".trim($base_array[$random])."</b>", $_POST['MESSAGE'], 1);
			}
		}
		if (WHO_WHERE) {
			$count = count($who_array) - 1;
			if ((strpos($_POST['MESSAGE'], '!who') !== FALSE) and $i++ < 10) {
				$random = rand(0, $count);
				$_POST['MESSAGE'] = preg_replace("/!who/", "<b>".trim($who_array[$random])."</b>", $_POST['MESSAGE'], 1);
			}

			$count = count($where_array) - 1;
			if ((strpos($_POST['MESSAGE'], '!where') !== FALSE) and $i++ < 10) {
				$random = rand(0, $count);
				$_POST['MESSAGE'] = preg_replace("/!where/", "<b>".trim($where_array[$random])."</b>", $_POST['MESSAGE'], 1);
			}

			$count = count($do_array) - 1;
			if (((strpos($_POST['MESSAGE'], '!do') !== FALSE) or (strpos($_POST['MESSAGE'], '!action') !== FALSE)) and $i++ < 10) {
				$random = rand(0, $count);
				$_POST['MESSAGE'] = preg_replace("/!do|!action/", "<b>".trim($do_array[$random])."</b>", $_POST['MESSAGE'], 1);
			}

			$count = count($food_array) - 1;
			if (((strpos($_POST['MESSAGE'], '!food') !== FALSE) or (strpos($_POST['MESSAGE'], '!hungry') !== FALSE)) and $i++ < 10) {
				$random = rand(0, $count);
				$_POST['MESSAGE'] = preg_replace("/!food|!hungry/", "<b>".trim($food_array[$random])."</b>", $_POST['MESSAGE'], 1);
			}
		}
		if ($i == $j) break;
	}
}
#==================================================
#�@�A�b�v���[�h����
#==================================================
if (isset($_FILES['file']) and $_FILES['file']['name'] and (UPLOAD or $upload)) {
	if (filesize($_FILES['file']['tmp_name']) > MAX_BYTES) DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�t�@�C�����傫�����܂��B�B�B");
	# �A���J�[�p�̃p�X
	$a_path = "../$_POST[bbs]/";
	# GD fuction �̃`�F�b�N
	$gifread = '';
	if (GD_VERSION == 2) {
		$imagecreate = "imagecreatetruecolor";
		$imageresize = "imagecopyresampled";
	}
	else {
		$imagecreate = "imagecreate";
		$imageresize = "imagecopyresized";
	}
	if (function_exists("imagecreatefromgif")) {
		$gifread = "on";
	}
	# �A�b�v���[�h�t�@�C���̊g���q���擾
	$path_parts = pathinfo($_FILES['file']['name']);
	$tail = '.'.$path_parts['extension'];
	# �ꉞ"jpeg"��"jpe"���Ή�
	if ($tail == ".jpeg" or $tail == ".jpe") $tail = ".jpg";
	# �摜�p�J�E���^�i=���X�ԍ��j��4����
	$imgnum = sprintf("%04d", $imgnum);
	# �t�@�C�����i�摜�f�B���N�g��/�X���b�h�̃L�[�ԍ��X���b�h���̃��X�ԍ�.�g���q�j
	$file_name = $IMGPATH.$_POST['key'].$imgnum.$tail;
	# �g���q���摜�t�@�C���ŁA�摜�̃T�C�Y���擾�ł���ꍇ
	if (($tail == ".jpg" or $tail == ".gif" or $tail == ".png") and $size = getimagesize($_FILES['file']['tmp_name'])) {
		# �A�b�v���[�h�t�@�C�����摜�f�B���N�g���Ɉړ�����
		move_uploaded_file($_FILES['file']['tmp_name'], $file_name);
		chmod($file_name, 0644);
		# �摜�t�@�C���ւ̃A���J�[�^�O
		$img_ref = '<a href="'.$a_path.'img/'.$_POST['key'].$imgnum.$tail.'">';
		$W = $size[0];
		$H = $size[1];
		# �摜�T�C�Y���\���ݒ���傫���ꍇ�k��
		if ($W > MAX_W or $H > MAX_H) {
			$W2 = MAX_W / $W;
			$H2 = MAX_H / $H;
			$ratio = ($W2 < $H2) ? $W2 : $H2;
			$W = (int)($W * $ratio);
			$H = (int)($H * $ratio);
			# GD���g���ăT���l�C���쐬�i�T���l�C����jpg�ɓ���j
			if (GD_VERSION) {
				$dst_im = $imagecreate($W,$H);
				# jpg�̏ꍇ
				if ($tail == ".jpg") {
					$src_im = imagecreatefromjpeg($file_name);
					$imageresize($dst_im,$src_im,0,0,0,0,$W,$H,$size[0],$size[1]);
					imagejpeg($dst_im, $IMGPATH2.$_POST['key'].$imgnum.".jpg");
					$img_ref .= '<img src="'.$a_path.'img2/'.$_POST['key'].$imgnum.'.jpg" width="'.$W.'" height="'.$H.'" align=left></a>';
				}
				# png�̏ꍇ
				elseif ($tail == ".png") {
					$src_im = imagecreatefrompng($file_name);
					$imageresize($dst_im,$src_im,0,0,0,0,$W,$H,$size[0],$size[1]);
					imagejpeg($dst_im, $IMGPATH2.$_POST['key'].$imgnum.".jpg");
					$img_ref .= '<img src="'.$a_path.'img2/'.$_POST['key'].$imgnum.'.jpg" width="'.$W.'" height="'.$H.'" align=left></a>';
				}
				# gif��GD�œǂ߂�ꍇ
				elseif ($tail == ".gif" and $gifread == "on") {
					$src_im = imagecreatefromgif($file_name);
					$imageresize($dst_im,$src_im,0,0,0,0,$W,$H,$size[0],$size[1]);
					imagejpeg($dst_im, $IMGPATH2.$_POST['key'].$imgnum.".jpg");
					$img_ref .= '<img src="'.$a_path.'img2/'.$_POST['key'].$imgnum.'.jpg" width="'.$W.'" height="'.$H.'" align=left></a>';
				}
				# gif���ǂ߂Ȃ��ꍇ�̓T���l�C���Ȃ�
				else $img_ref .= '<img src="'.$a_path.'img/'.$_POST['key'].$imgnum.$tail.'" width="'.$W.'" height="'.$H.'" align=left></a>';
			}
			# �T���l�C�����쐬���Ȃ��ꍇ
			else $img_ref .= '<img src="'.$a_path.'img/'.$_POST['key'].$imgnum.$tail.'" width="'.$W.'" height="'.$H.'" align=left></a>';
		}
		# ���e�摜�T�C�Y���ݒ�T�C�Y��菬�����ꍇ�͂��̂܂܂̃T�C�Y�ŕ\��
		else $img_ref .= '<img src="'.$a_path.'img/'.$_POST['key'].$imgnum.$tail.'" '.$size[3].' align=left></a>';
		$_POST['MESSAGE'] = $img_ref.$_POST['MESSAGE'].'<br clear=all>';
	}
	# �摜�t�@�C���ȊO�̓G���[
	else DispError("�d�q�q�n�q�I","�d�q�q�n�q�F�A�b�v�ł��Ȃ��t�@�C���ł��B�B�B");
}
?>
