<?php

	require_once("config.php");

	/**
	* ���O�C���N���X
	*/
	class Authority {

		private $mysqli = null;

		/**
		* �R���X�g���N�^
		*/
		function __construct() {

			$this->mysqli = new mysqli(self::HOST_NAME, self::USER_NAME, self::PASSWORD, self::DATABASE_NAME);

			session_start();
		}

		/**
		* �w�肵�����[�U��V�K�o�^���܂��B
		*
		* @param string $username ���[�U��
		* @param string $password �p�X���[�h
		*
		* @return �����������ǂ���
		*/
		public function register($username, $password) {

			$password = $this->hash($_POST["password"]);

			$stmt = $this->mysqli->prepare("insert into users values (?, ?)");

			$stmt->bind_param('ss', $_POST["username"], $password);

			return $stmt->execute();

		}

		/**
		* �w�肵�����[�U�Ń��O�C�����܂��B
		*
		* @param string $username ���[�U��
		* @param string $password �p�X���[�h
		*
		* @return �����������ǂ���
		*/
		public function login($username, $password) {

			$password = $this->hash($_POST["password"]);

			$stmt = $this->mysqli->prepare("select * from users where username = ? and password = ?");

			$stmt->bind_param('ss', $_POST["username"], $password);

			$stmt->execute();

			$stmt->store_result();

			if($stmt->num_rows == 1) {
				$_SESSION["username"] = $_POST["username"];
				return true;
			}

			return false;
		}

		/**
		* ���݃��O�C�����̃��[�U����Ԃ��܂��B
		*
		* @return ���[�U�� �����O�C���̏ꍇ�� null
		*/
		public function getUser() {

			if(isset($_SESSION["username"])) {
				return $_SESSION["username"];
			}

			return null;
		}

		/**
		* ���݃��O�C�����̃��[�U�����O�A�E�g���܂��B
		*/
		public function logout() {

			$_SESSION = array(); 

			session_destroy();
		}

		/**
		* �n�b�V����
		*/
		private function hash($password) {
			return md5($password . self::SALT);
		}
	}
?>
