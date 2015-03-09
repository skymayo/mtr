<?php

	require_once("config.php");

	/**
	* ログインクラス
	*/
	class Authority {

		private $mysqli = null;

		/**
		* コンストラクタ
		*/
		function __construct() {

			$this->mysqli = new mysqli(self::HOST_NAME, self::USER_NAME, self::PASSWORD, self::DATABASE_NAME);

			session_start();
		}

		/**
		* 指定したユーザを新規登録します。
		*
		* @param string $username ユーザ名
		* @param string $password パスワード
		*
		* @return 成功したかどうか
		*/
		public function register($username, $password) {

			$password = $this->hash($_POST["password"]);

			$stmt = $this->mysqli->prepare("insert into users values (?, ?)");

			$stmt->bind_param('ss', $_POST["username"], $password);

			return $stmt->execute();

		}

		/**
		* 指定したユーザでログインします。
		*
		* @param string $username ユーザ名
		* @param string $password パスワード
		*
		* @return 成功したかどうか
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
		* 現在ログイン中のユーザ名を返します。
		*
		* @return ユーザ名 未ログインの場合は null
		*/
		public function getUser() {

			if(isset($_SESSION["username"])) {
				return $_SESSION["username"];
			}

			return null;
		}

		/**
		* 現在ログイン中のユーザをログアウトします。
		*/
		public function logout() {

			$_SESSION = array(); 

			session_destroy();
		}

		/**
		* ハッシュ化
		*/
		private function hash($password) {
			return md5($password . self::SALT);
		}
	}
?>
