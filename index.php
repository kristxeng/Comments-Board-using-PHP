<?php session_start(); ?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Kris's Comment Board</title>
		<meta name="description" content="This is Mentor Program Week7 HW3" />

		<!--  Bootstrap StyleSheet by BootWatch  -->
		<link rel="stylesheet" type="text/css" href="./style/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="./style/style.css" />

		<!--  jQuery  -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!--  Bootstrap JS -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="./script/index.js"></script> 
	</head>
	<body>

		<div class="background-image"></div>
		<div class="container-fluid">
			
			<div class="title col-lg-6 col-sm-10 mx-auto mt-5">留言板</div>

			
			<!--  主要留言的撰寫框 START  -->
			<div class="cmmt-box col-lg-6 col-sm-10 mx-auto mb-2 p-4">

			<?php
				require_once('conn.php');
				require_once('convert_time.php');


				//以確認 session 中是否有user_id，來認定用戶是否登入
				if( isset($_SESSION['user_id']) ){

					//用 session 中的 user_id 尋找登入者的nickname
					$user_stmt = $conn->prepare("SELECT nickname FROM $users_table WHERE id = :user_id");
					$user_stmt->bindParam(':user_id', $_SESSION['user_id']);
					$user_stmt->execute();
					$user_stmt->setFetchMode(PDO::FETCH_ASSOC);
					$user_row = $user_stmt->fetch();
			?>
						
						<div>
							<div class="cmmt__nickname">
								<?php echo htmlspecialchars($user_row['nickname']) ?>
								<span class="cmmt__logout">[ 登出 ]</span>
							</div>
							<textarea class="cmmt__textarea" name="content" placeholder="留言內容" required></textarea>
							<input type="hidden" name="parent_id" value='0' />
							<input class="cmmt__btn btn btn-primary" type="submit" value="送 出" />
						</div>
				
				
			<?php					
				}else{  //如果未登入，顯示登入框
			?> 

					<input class="cmmt__btn btn btn-primary" type="button" value="登入以使用留言功能" onclick="location.href='login.php'" />
		
			<?php
				}
			?>

			</div>
			<!--  主要留言的撰寫框END  -->

			
			<?php
				
				//查詢主要留言筆數
				$pages_stmt = $conn->prepare("SELECT COUNT(parent_id) AS datanum FROM $cmmts_table  WHERE parent_id = 0");
				$pages_stmt->execute();
				$pages_stmt->setFetchMode(PDO::FETCH_ASSOC);
				$pages_row = $pages_stmt->fetch();

				//$pagesnum 總頁數
				$pagesnum = (int)ceil( $pages_row['datanum'] / 10 );

				//$page 目前頁碼，如果沒有 $_GET，或是 $_GET 非數字，則 $page=1
				if( !isset( $_GET['page']) OR !intval($_GET['page'])) $page=1;
				else $page =  intval( $_GET['page'] );

				//計算本頁顯示的第一筆留言起始值
				$cmmt_start_num = ($page-1)*10;

				//查詢目前頁面需要的十筆主留言
				$cmmt_stmt = $conn->prepare("SELECT c.id AS cmmt_id, user_id, nickname, created_by, content FROM $cmmts_table AS c INNER JOIN" . 
					" $users_table ON parent_id = 0 AND user_id = $users_table.id ORDER BY created_by DESC LIMIT $cmmt_start_num, 10");
				
				$cmmt_stmt->execute();
				$cmmt_stmt->setFetchMode(PDO::FETCH_ASSOC);
				while( $cmmt_row = $cmmt_stmt->fetch() ){
			?>

					<!--  主留言外框 START  -->
					<div class="cmmt-box col-lg-6 col-sm-10 mx-auto mb-2 p-4">

						<!--  顯示主留言 START  -->
						<div class="cmmt__header">
							<div class="cmmt__nickname"><?php echo htmlspecialchars($cmmt_row["nickname"]) ?></div>
							<div>
								<div class="cmmt__time"><?php echo convert_time( $cmmt_row["created_by"] ) ?></div>
								<div class="cmmt__edit-delete">

			<?php  //如果已登入，且這條留言的user_id等於當前用戶的 user_id，則顯示編輯/刪除按鈕
					if( isset($_SESSION['user_id']) AND $cmmt_row['user_id'] === $_SESSION['user_id'] ){
					
						echo '<span class="cmmt__edit">編輯</span>&nbsp;/&nbsp;<span class="cmmt__delete">刪除</span>';
					}
			?>

								</div>
							</div>
						</div>
						<div class="cmmt__content"><?php echo htmlspecialchars($cmmt_row["content"]) ?></div>
						<div class="cmmt__id"><?php echo $cmmt_row["cmmt_id"] ?></div>
						


					<!--  顯示子留言串 START  -->
					
			<?php 
					//查詢子留言
					$sub_stmt = $conn->prepare("SELECT c.id AS cmmt_id, user_id, nickname, created_by, content FROM $cmmts_table AS c INNER JOIN $users_table".
								" WHERE parent_id = :cmmt_id AND user_id = $users_table.id ORDER BY created_by ASC");
					$sub_stmt->bindParam( ':cmmt_id', $cmmt_row['cmmt_id'] );
					$sub_stmt->execute();
					$sub_stmt->setFetchMode(PDO::FETCH_ASSOC);
					while( $sub_row = $sub_stmt->fetch() ){

						//如果是主留言者，則背景上色
						if( $sub_row['user_id'] === $cmmt_row['user_id'] ) echo '<div class="sub-cmmt sub-cmmt__main-author col-11">';
						else echo '<div class="sub-cmmt col-11">';

			?>

								<div class="cmmt__header">
									<div class="cmmt__nickname"><?php echo htmlspecialchars($sub_row["nickname"]) ?></div>
									<div>	
										<div class="cmmt__time"><?php echo convert_time( $sub_row["created_by"] ) ?></div>
										<div class="cmmt__edit-delete">
											<?php  //如果已登入，且這條留言的user_id等於當前用戶的 user_id，則顯示編輯/刪除按鈕
												if( isset($_SESSION['user_id']) AND $sub_row['user_id'] === $_SESSION['user_id'] ){
												
													echo '<span class="cmmt__edit">編輯</span>&nbsp;/&nbsp;<span class="cmmt__delete">刪除</span>';
												}
											?>
										</div>
									</div>
								</div>

								<div class="cmmt__content"><?php echo htmlspecialchars($sub_row["content"]) ?></div>
								<div class="cmmt__id"><?php echo $sub_row["cmmt_id"] ?></div>
							</div>
					

			<?php
					} //END of 子留言查詢 while
			?>
			
							<!--   子留言的撰寫框 START  -->
							<div class="sub-cmmt">  

			<?php
						//如果有登入，顯示回應按鍵
						if( isset($_SESSION['user_id']) ){
				
			?>

								<div class="sub-cmmt__collapse-toggle">回應[+]</div>
								<div>
									<div class="cmmt__nickname"><?php echo htmlspecialchars($user_row['nickname']) ?></div>
									<textarea class="cmmt__textarea" name="content" placeholder="留言內容" required></textarea>
									<input type="hidden" name="parent_id" value=<?php echo $cmmt_row['cmmt_id'] ?> />
									<input class="cmmt__btn sub-cmmt__btn btn btn-primary" type="submit" value="送 出" />
								</div>

			<?php
						}else{
			?>

								<a class="sub-cmmt__login-link text-primary" onclick="location.href='login.php'">
									登入以發表回應 
								</a>

			<?php
						}
			?>

							</div> 
						</div> 


			<?php
				} //END of 主留言查詢 while
			?>


			<!-- Bootstrap 分頁 START -->
			<div class="my-3">
				<ul class="pagination justify-content-center">
					
			<?php 
				//如果目前在第一頁，前一頁連結失效
				if( $page === 1 ){

					echo '<li class="page-item disabled">';
				    echo '<a class="page-link" href="#">';

				}else{

					echo '<li class="page-item">';
				    echo '<a class="page-link" href="index.php?page='. ($page-1) .'">';

				}
			?>
							<span aria-hidden="true">&laquo;</span>
						</a>
					</li>

			<?php

				for( $i=1; $i<=$pagesnum; $i++ ){
					if( $i === $page ){
						//目前頁面的頁碼 active
						echo '<li class="page-item active"><a class="page-link" href="index.php?page='.$i.'">'.$i.'</a></li>';
					}else{
						//非目前頁面的頁碼連結正常
						echo '<li class="page-item"><a class="page-link" href="index.php?page='.$i.'">'.$i.'</a></li>';
					}
					
				}

				//如果目前在最後一頁，後一頁連結失效
				if( $page === $pagesnum ){

					echo '<li class="page-item disabled">';
					echo '<a class="page-link" href="#">';

				}else{

					echo '<li class="page-item">';
				    echo '<a class="page-link" href="index.php?page='. ($page+1) .'">';
				}
			?>

							<span aria-hidden="true">&raquo;</span>
						</a>
					</li>
				</ul>
			</nav>
			<!-- Bootstrap 分頁 END -->

		</div> <!-- END of container-fluid -->
	</body>
</html>