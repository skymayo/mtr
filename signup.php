<?php

	// ハッシュ化に使用する定数
	$salt = "mwefCMEP28DjwdW3lwdS239vVS";

	$mysqli = new mysqli('localhost', 'mtr', 'mtr', 'mtr');

	$status = "none";

	if(!empty($_POST["username"]) && !empty($_POST["password"])) {

		// パスワードはハッシュ化する
		$password = md5($_POST["password"] . $salt);

		// ユーザ入力を使用するのでプリペアドステートメントを使用
		$stmt = $mysqli->prepare("INSERT INTO user VALUES (?, ?)");
		$stmt->bind_param('ss', $_POST["username"], $password);

		if($stmt->execute()) {
			$status = "ok";
		} else {
			// 既に存在するユーザ名だった場合INSERTに失敗する
			$status = "failed";
		}
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<meta charset="UTF-8" />
		<title>新規登録</title>
	</head>
	<body>
		<h1>新規登録</h1>
		<?php if($status == "ok"): ?>
			<p>登録完了</p>
		<?php elseif($status == "failed"): ?>
			<p>エラー：既に存在するユーザ名です。</p>
		<?php else: ?>
			<form method="POST" action="signup.php">
				ユーザ名：<input type="text" name="username" />
				パスワード：<input type="password" name="password" />
				<input type="submit" value="登録" />
			</form>
		<?php endif; ?>
	</body>
</html>
