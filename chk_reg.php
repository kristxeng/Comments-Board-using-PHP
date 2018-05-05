<?php
session_start();

require_once('conn.php');

//查詢輸入的 username 及 nickname 是否已經被他人使用
$chk_stmt = $conn->prepare("SELECT username , nickname FROM $users_table" .
						" WHERE username=:username OR nickname=:nickname");
//bind parameters & execute
$chk_stmt->bindParam(':username', $_POST['username']);
$chk_stmt->bindParam(':nickname', $_POST['nickname']);
$chk_stmt->execute();
$chk_stmt->setFetchMode(PDO::FETCH_ASSOC);

//如果 username 及 nickname 沒有被他人使用，則執行註冊程序
if( $chk_stmt->rowCount() === 0 ){

	//密碼 hash 加密處理
	$hashed_password = password_hash( $_POST['password'], PASSWORD_DEFAULT );

	$reg_stmt = $conn->prepare("INSERT INTO $users_table (username, password, nickname) ".
								"VALUES (:username, :password, :nickname)");
	$param = [
		':username' => $_POST['username'],
		':password' => $hashed_password,
		':nickname' => $_POST['nickname']
	];

	//註冊成功後，設定cookie
	if( $reg_stmt->execute($param) ){

		//設定 session 中的 user_id
		$_SESSION['user_id'] = $conn->lastInsertId();

		echo 'ok';
	}

}else{

	while( $chk_row = $chk_stmt->fetch() ){

		//字串做不分大小寫比較
		if( !strcasecmp( $chk_row['username'], $_POST['username'] ) AND !strcasecmp( $chk_row['nickname'], $_POST['nickname'] ) ){
			
			echo 'both_err';  
			//重複的帳號暱稱在同一列

		}else if( !strcasecmp( $chk_row['nickname'], $_POST['nickname'] ) ){

			echo 'n_err';

		}else if( !strcasecmp( $chk_row['username'], $_POST['username'] ) ){

			echo 'u_err';
			//重複帳號暱稱若在不同列，會分別回傳n_err和u_err，用戶端要做排列組合
			
		}else{

			echo 'error';
			//測試找例外錯誤時使用
		}

	}
}

?>