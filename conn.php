<?php

$servername = "localhost";
$username = "";
$password = "";
$dbname = "mentor_program_db";
$cmmts_table = "kristxeng_comments2";
$users_table = "kristxeng_users";

//用 PDO 方式改寫

try{

	$conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
	//設定錯誤顯示時，會拋出異常
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//echo "Connected Success";
}

catch(PDOException $e){

	echo "Connected Failed: " . $e->getMessage();
}

?>
