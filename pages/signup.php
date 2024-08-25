<?php

require_once "db.php"; 
DB::connect();
if(isset($_COOKIE['user_otp'])){
    $userName = $_COOKIE['user_otp']; 
    DB::write_placeOTP($userName, NULL, NULL); 
    setcookie('user_otp', '', 0 , '/hybrid_rendering/pages');
}
 
$result = DB::read(); 



$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = trim($_POST['username']); 
    $passWord = trim($_POST['password']);

    $userExists = true;
    foreach($result as $item){
            if($userName === $item['username']){
                $userExists = false; 
                break;
            }
    }

    if ($userExists) {
        // Trả về một phản hồi JSON thay vì cố gắng thực hiện chuyển hướng từ PHP
        DB::write($userName, $passWord);
        session_start();  
        $_SESSION['username'] = $userName; 
        echo json_encode(['status' => 'success', 'redirect' => '/hybrid_rendering/index-main.php']);
    }
    else {
        echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập đã được sử dụng . Vui lòng sử dụng tài khoản khác ']);
    }
    exit();
}
?>
<link rel="stylesheet" href="/hybrid_rendering/css/signup.css"/>
<div class="container">
    <h2>Đăng Kí</h2>
    <form method="post" action="">
        <div class="input-group">
            <label for="username">Email đăng kí:</label>
            <input type="email" id="username" name="username" required>
        </div>
        <div class="input-group">
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required><br/>
        </div>
        <span id="span_s/h" onclick='
            const inputPW = document.getElementById("password");
            inputPW.type = inputPW.type === "password" ? "text" : "password";
            this.textContent = inputPW.type === "password" ? "show password" : "hide password";'>show password</span>
        <br/>
        <button type="submit">Đăng Kí</button>
        <p class="error"><?php echo $error; ?></p>
        <br/>
    </form>
</div>
