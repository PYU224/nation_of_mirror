<?php
# 0thello ����
$fp = fopen($PATH.'0thello/'.$_POST['key'].'.dat', "r+");
flock($fp, LOCK_EX);
if ($_POST['subject']) {
	// �X�����Ă��l�̃g���b�v��o�^
	if ($trip) fwrite($fp, $trip."\n");
	// �g���b�v�t���Y��Ȃ�f�[�^�t�@�C���폜
	else {
		fclose($fp);
		unlink($PATH.'0thello/'.$_POST['key'].'.dat');
		DispError("�d�q�q�n�q�I", "VIP-0THELLO: �g���b�v�����Ă������� ");
	}
}
elseif ($trip and $white = fgets($fp, 1024) and strstr($_POST['FROM'], '!0thello')) {
	$white = rtrim($white);
	$game_board = array();
	$tmp_board = array();
	// �����o�^����Ă���
	$black = rtrim(fgets($fp, 1024));
	if ($black) {
		// ���̎�Ԃ̃g���b�v
		list($game_count, $now) = fgetcsv($fp, 1024);
		if ($trip == $now) {
			// �Ֆʃf�[�^�̓ǂݍ���
			for ($i = 0; $i < 8; $i++) {
				$game_board[$i] = explode('�b', rtrim(fgets($fp, 1024)));
			}
			$game_count++;
			if (preg_match("/(.*)!0thello!x(\d+)!y(\d+)<\/b>��/", $_POST['FROM'], $match)) {
				if ($match[2] < 1 or $match[2] > 9) DispError("�d�q�q�n�q�I", "VIP-0THELLO: X�����w�肵�Ă������� ");
				if ($match[3] < 1 or $match[3] > 9) DispError("�d�q�q�n�q�I", "VIP-0THELLO: Y�����w�肵�Ă������� ");
				$next = ($trip == $white) ? $black : $white;
				if ($next == $white) {
					$p1 = "��";
					$p2 = "��";
				}
				else {
					$p1 = "��";
					$p2 = "��";
				}
				$x = $match[2] - 1;
				$y = $match[3] - 1;
				if ($game_board[$y][$x] != "�@") DispError ("�d�q�q�n�q�I","VIP-0THELLO�F�����ɋ��u�����Ƃ͂ł��܂���");
				function reverse(&$board, $board2, $x, $y, $dx, $dy, $p1, $p2) {
					$count = 0;
					$temp = $board;
					$x += $dx;
					$y += $dy;
					while ($x >= 0 and $x < 8 and $y >= 0 and $y < 8 and $board2[$y][$x] == $p2) {
						$board[$y][$x] = $p1;
						$x += $dx;
						$y += $dy;
						$count++;
					}
					if ($count and $board2[$y][$x] != $p1) {
						$board = $temp;
						$count = 0;
					}
					return $count;
				}
				$tmp_board = $game_board;
				$reverse  = reverse($game_board, $tmp_board, $x, $y, -1, -1, $p1, $p2);
				$reverse += reverse($game_board, $tmp_board, $x, $y, -1, 0, $p1, $p2);
				$reverse += reverse($game_board, $tmp_board, $x, $y, -1, 1, $p1, $p2);
				$reverse += reverse($game_board, $tmp_board, $x, $y, 0, -1, $p1, $p2);
				$reverse += reverse($game_board, $tmp_board, $x, $y, 0, 1, $p1, $p2);
				$reverse += reverse($game_board, $tmp_board, $x, $y, 1, -1, $p1, $p2);
				$reverse += reverse($game_board, $tmp_board, $x, $y, 1, 0, $p1, $p2);
				$reverse += reverse($game_board, $tmp_board, $x, $y, 1, 1, $p1, $p2);
				if ($reverse == 0) DispError ("�d�q�q�n�q�I","VIP-0THELLO�F�����ɋ��u�����Ƃ͂ł��܂���");
				$game_board[$y][$x] = $p1;
				fseek($fp, 0, SEEK_SET);
				fwrite($fp, $white."\n".$black."\n".$game_count.",".$next."\n");
				for ($i = 0; $i < 8; $i++) {
					fwrite($fp, implode('�b', $game_board[$i])."\n");
				}
				ftruncate($fp, ftell($fp));
				$_POST['MESSAGE'] .= '<pre>------------------------------------- <br> ';
				$_POST['MESSAGE'] .= '�@�W�@�V�@�U�@�T�@�S�@�R�@�Q�@�P <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[0])).'�b��@�@�����(��'.$white.') <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[1])).'�b��@�@�����(��'.$black.') <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[2])).'�b�O�@�@'.$p1.$game_count.": ".$match[2].", ".$match[3].' <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[3])).'�b�l�@�@����'.$p2.'�̔Ԃł� <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[4])).'�b�� <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[5])).'�b�Z <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[6])).'�b�� <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[7])).'�b�� <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� </pre> ';
				$_POST['FROM'] = $match[1]."</b>��".$trip."<b>";
			}
			// �p�X
			elseif (preg_match("/(.*)!0thello!pass<\/b>��/", $_POST['FROM'], $match)) {
				fseek($fp, 0, SEEK_SET);
				fwrite($fp, $white."\n".$black."\n".$game_count.",".$next."\n");
				for ($i = 0; $i < 8; $i++) {
					fwrite($fp, implode('�b', $game_board[$i])."\n");
				}
				ftruncate($fp, ftell($fp));
				$_POST['MESSAGE'] .= '<pre>------------------------------------- <br> ';
				$_POST['MESSAGE'] .= '�@�W�@�V�@�U�@�T�@�S�@�R�@�Q�@�P <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[0])).'�b��@�@�����(��'.$white.') <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[1])).'�b��@�@�����(��'.$black.') <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[2])).'�b�O�@�@'.$p1.$game_count.': �p�X <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[3])).'�b�l�@�@����'.$p2.'�̔Ԃł� <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[4])).'�b�� <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[5])).'�b�Z <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[6])).'�b�� <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
				$_POST['MESSAGE'] .= '�b'.implode('�b', array_reverse($game_board[7])).'�b�� <br> ';
				$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� </pre> ';
				$_POST['FROM'] = $match[1]."</b>��".$trip."<b>";
			}
		}
	}
	// ���̂ݓo�^�̏ꍇ���̓o�^�Ǝ��̎�ԁi���j����������
	else {
		fwrite($fp, $trip."\n"."0,".$white."\n");
		// �Ղ̏�����
		for ($i = 0; $i < 8; $i++) {
			for ($j = 0; $j < 8; $j++) {
				$game_board[$i][$j] = '�@';
			}
		}
		$game_board[3][3] = "��";
		$game_board[4][4] = "��";
		$game_board[3][4] = "��";
		$game_board[4][3] = "��";
		for ($i = 0; $i < 8; $i++) {
			fwrite($fp, implode('�b', $game_board[$i])."\n");
		}
		$_POST['MESSAGE'] .= '<pre>------------------------------------- <br> ';
		$_POST['MESSAGE'] .= '�@�W�@�V�@�U�@�T�@�S�@�R�@�Q�@�P <br> ';
		$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
		$_POST['MESSAGE'] .= '�b�@�b�@�b�@�b�@�b�@�b�@�b�@�b�@�b��@�@�����(��'.$white.') <br> ';
		$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
		$_POST['MESSAGE'] .= '�b�@�b�@�b�@�b�@�b�@�b�@�b�@�b�@�b��@�@�����(��'.$trip.') <br> ';
		$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
		$_POST['MESSAGE'] .= '�b�@�b�@�b�@�b�@�b�@�b�@�b�@�b�@�b�O�@�@���́��̔Ԃł� <br> ';
		$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
		$_POST['MESSAGE'] .= '�b�@�b�@�b�@�b���b���b�@�b�@�b�@�b�l�@�@ <br> ';
		$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
		$_POST['MESSAGE'] .= '�b�@�b�@�b�@�b���b���b�@�b�@�b�@�b�� <br> ';
		$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
		$_POST['MESSAGE'] .= '�b�@�b�@�b�@�b�@�b�@�b�@�b�@�b�@�b�Z <br> ';
		$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
		$_POST['MESSAGE'] .= '�b�@�b�@�b�@�b�@�b�@�b�@�b�@�b�@�b�� <br> ';
		$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� <br> ';
		$_POST['MESSAGE'] .= '�b�@�b�@�b�@�b�@�b�@�b�@�b�@�b�@�b�� <br> ';
		$_POST['MESSAGE'] .= '���\���\���\���\���\���\���\���\�� </pre> ';
	}
}
fclose($fp);
$_POST['FROM'] = str_replace('!0thello', '', $_POST['FROM'])
?>
