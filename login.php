<!DOCTYPE html>
<html lang="zh-Hant-TW">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>登入</title>
		<meta name="description" content="This is Mentor Program Week7 hw3" />
		<!--  Bootstrap StyleSheet by BootWatch  -->
		<link rel="stylesheet" type="text/css" href="./style/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="./style/style.css" />
		<!--  jQuery  -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!--  Bootstrap JS -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="./script/login.js"></script>
	</head>
	<body>
		<div class="background-image"></div>
		<div class="login-container container-fluid d-flex justify-content-center">
			<div class="col-lg-5 col-md-7">
				<div class="title mt-5">登入</div>
				<div class="cmmt-box bg-white p-5">

					<div class="login__title">請輸入您的使用者名稱：</div>
					<input class="login__input" name="username" type="text" placeholder="帳號" />

					<div class="login__title">請輸入您的密碼：</div>
					<input class="login__input" name="password" type="password" placeholder="密碼" />

					<div class="login__warning-toggle"></div>
					<input class="cmmt__btn login__btn btn btn-primary" type="button" value="Sign In" />

					<div class="login__title login__title--centered text-primary"><a href="reg.php">還沒有帳號？ 請按此註冊</a></div>
					
				</div>
			</div>
		</div>

	</body>
</html>