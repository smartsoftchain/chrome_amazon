<?php

// ���O�o�̓f�B���N�g���A�v���t�B�b�N�X�̐ݒ肪�����ꍇ�́A���̐ݒ肪�L���ɂȂ�
if (!defined("LOG_PRIFIX")) {
	// ���O�t�@�C���v���t�B�b�N�X
	define("LOG_PRIFIX", "");
}
if (!defined("LOG_DIR")) {
	// ���O�t�@�C���o�̓f�B���N�g��
	//define("LOG_DIR",  "../log/");
	define("LOG_DIR",  dirname(__FILE__) . "/../log/");
}

// ���O���x���̐錾
/*
define("LOG_FATAL", 1);	// �v���I�G���[
define("LOG_ERROR", 3);	// �G���[
define("LOG_WARNING", 5);	// �x��
define("LOG_INFO", 7);	// ��ʏ��
define("LOG_DEBUG", 9);	// �f�o�b�O���
*/
class ErrLog {
	var $logfile = null;
	var $loglevel = 0;
	/**
	 * @name ErrLog
	 * @desc �R���X�g���N�^
	 * @param $logfile ���O�o�̓t�@�C�����i�ȗ��A�ȗ����͔N�����j
	 * @param $loglevel ���O�o�̓��x���i�ȗ��A�ȗ����̓G���[�A�v���I�G���[�̂ݏo�́j
	 **/
	function ErrLog($logfile=null, $loglevel=3) {
		//
		$this->logfile = $logfile;
		$this->loglevel = $loglevel;
		// �t�@�C�������w�肳��Ȃ��ꍇ�̃f�t�H���g�i�N�����j
		if ($logfile == null) {
			$cur = getdate(time());
			$this->logfile = sprintf(LOG_PRIFIX . "%04d%02d%02d.log", $cur["year"], $cur["mon"], $cur["mday"]);
		}
	}
    /**
     * @name getInstance
	 * @desc �C���X�^���X�쐬
     */
    function &getInstance($logfile=null, $loglevel=1) {
        static $instance = null;
        if ($instance == null) {
            $instance = new ErrLog($logfile, $loglevel);
        }
        return $instance;
    }

	/**
	 * @name ErrLog_Write
	 * @desc ���O�t�@�C���o�͏���
	 * @param $loglevel ���O�o�̓��x��
	 * @param $logmsg �o�̓��b�Z�[�W�i�P�Ƃ̕�����A�܂��͕�����̔z��j
	 * @param $file �G���[�����t�@�C���i�ȗ��j
	 * @param $line �G���[�����s���i�ȗ��A$file�Ɨ���������Ă��Ȃ��Əo�͂��Ȃ��j
	 **/
	function ErrLog_Write($loglevel, $logmsg, $file=null, $line=null) {
		if ($this->loglevel >= $loglevel) {
			if (($fp = fopen(LOG_DIR . $this->logfile, "a"))) {
				if ($file && $line) {
					$msg = "File: " . $file . " Line: " . $line . "\r\n";
					fputs($fp, $msg);
				}
				if (is_array($logmsg)) {
					$msg = implode("\r\n", $logmsg);
				} else {
					$msg = $logmsg;
				}
				$cur = getdate(time());
				$buf = sprintf("%02d:%02d %d %s\r\n", $cur["hours"], $cur["minutes"], $loglevel, $msg);
				fputs($fp, $buf);
				fclose($fp);
if (FALSE!==strpos($_SERVER[HTTP_USER_AGENT] , "sada test")) {
	if (preg_match("/(update|insert|delete)/i", $logmsg)) {
		echo "<div style='border:red 3px double;'><em>LogMessage</em><br>{$logmsg}<br></div>";
	}
	else {
		if (FALSE!==strpos($_SERVER[HTTP_USER_AGENT] , "session") AND !defined("SADA_DISP_SESSION")) {
			echo "<pre>";
			print_r($_SESSION);
			echo "</pre>";
			define("SADA_DISP_SESSION", "1");
		}
		if (FALSE!==strpos($_SERVER[HTTP_USER_AGENT] , "detail")) {
			echo "<div style='border:gray 3px double;'><em>LogMessage</em><br>{$logmsg}<br></div>";
		}
	}
}
			}
		}
	}

	/**
	 * @name ErrLog_Screen
	 * @desc ���O��ʏo�͏���
	 * @param $logmsg �o�̓��b�Z�[�W
	 **/
	function ErrLog_Screen($logmsg) {
		// ������
		echo "������<br>\n";
	}
}
?>