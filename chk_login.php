<?php
session_start();

require_once('conn.php');


//使用 PDO & Prepared Statement 操作 MySQL
$stmt = $conn->prepare("SELECT id, username, password FROM $users_table " .
					"WHERE username = :username");
$stmt->bindParam(':username', $_POST['username']);
$stmt->execute();

//設定 fetch mode 為 fetch_assoc
$stmt->setFetchMode(PDO::FETCH_ASSOC);

//如果有找到 usernam，則比對 password
if( $stmt->rowCount() === 1 ){

	$row = $stmt->fetch();

	if( password_verify( $_POST['password'], $row['password'] ) ){

		//設定 session 中的 user_id
		$_SESSION['user_id'] = $row['id'];

		echo 'ok';

	}else{

		 echo 'error';
	}
	
}else{

	echo 'error';
}

?>