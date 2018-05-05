# 使用 PHP 和 AJAX 的留言板  

## 後端  
**使用 PDO & prepare statement 存取 MySQL 資料庫**  
```
$stmt = $conn->prepare("SELECT id, username, password FROM $users_table WHERE username = :username");
$stmt->bindParam(':username', $_POST['username']);
$stmt->execute();
```
PDO (PHP Database Object) 支援包括 MySQL 在內的多種資料庫系統，所以如果需要更換資料庫系統，原本寫的 code 也不用大幅更動。 PDO 的 prepare statement 可以使用 named parameter，就不需要去算 sql statement 中放置了幾個問號。PDO prepare statement 在變數插入 sql statement 前已經做了預處理，所以不需要擔心 SQL injection 的問題。  

**使用者登入系統**  
- 使用 session 驗證使用者登入。  
- 資料庫內的使用者密碼使用 `password_hash()` 加密後再存入資料庫。使用者登入時，使用 `password_verify()` 比對輸入的密碼跟資料庫內的密碼是否相符。  

**主機系統**  
- 主機：AWS EC2  
- 系統：ubuntu 16.04 / apache 2.4.18 / PHP 7.0 / MySQL 5.7  
- 使用 scp 或是 sftp 的方式，將檔案上傳 aws 主機。  

## 前端  
- 前端使用 AJAX 與後端傳遞資訊，後端 PHP 在顯示留言時，有先用 `htmlspecialchars()` 處理，以避免 XSS 攻擊，而為了讓 AJAX 後續產生的網頁跟 PHP render 的網頁一致，前端 JavaScript 顯示新留言前也會通過 JavaScript 版本的 `htmlspecialchars()` 處理。  
- css framework 使用 bootstrap。
