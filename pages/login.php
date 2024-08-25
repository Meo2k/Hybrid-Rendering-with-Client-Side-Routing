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
    $userName = $_POST['username'];
    $passWord = $_POST['password'];

    $authenticated = false;
    foreach($result as $item){
        if($userName === $item['username'] && $passWord === $item['password']){
            session_start(); 
            $_SESSION['username'] = $userName;
            $authenticated = true;
            break;
        }
    }

    if ($authenticated) {
        // Trả về một phản hồi JSON thay vì cố gắng thực hiện chuyển hướng từ PHP
        echo json_encode(['status' => 'success', 'redirect' => '/hybrid_rendering/index-main.php']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập hoặc mật khẩu không chính xác.']);
    }
    exit();
}
?>
<link rel="stylesheet" href="/hybrid_rendering/css/login-main.css"/>
<div class="container">   
    <div class="popup">Mật khẩu đã được thay đổi thành công <img src="/hybrid_rendering/photos/greentick.png" alt="greentick"></div>
    <h2>Đăng Nhập</h2>
    <form method="post" action="">
        <div class="input-group">
            <label for="username">Email:</label>
            <input type="email" id="username" name="username" 
                oninput="
                    {
                        const emailValue = this.value.trim();
                        const nextLink = document.getElementById('nextBtn');
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                        if (emailPattern.test(emailValue)) {
                            nextLink.style.pointerEvents = 'auto';  // Kích hoạt nút
                            nextLink.style.opacity = '1';  // Đặt lại độ mờ để hiển thị rõ ràng
                        } else {
                            nextLink.style.pointerEvents = 'none';  // Vô hiệu hóa nút
                            nextLink.style.opacity = '0.5';  // Giảm độ mờ để hiển thị rằng nút bị vô hiệu hóa
                        }
                    }
                " 
                onkeydown="
                    {
                        const emailValue = this.value.trim();
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                        if (!emailPattern.test(emailValue) && (event.key === 'Enter' || event.keyCode === 13)) {
                            event.preventDefault();  // Ngăn chặn sự kiện submit bằng phím Enter nếu email không hợp lệ
                        }
                    }
                " 
                required>


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
        <button type="submit" id="nextBtn" style="opacity: 0.5; pointer-events: none;">Đăng Nhập</button>
        <p class="error"><?php echo $error; ?></p>
        <br/>
        <div><a href="/hybrid_rendering/forgot" style="color:blue; " data-route>Quên mật khẩu ?</a></div>
        <div>Bạn chưa có tài khoản ? <br/> Bấm <a href="/hybrid_rendering/signup" style="color:blue; " data-route>vào đây</a> để đăng kí</div>
    </form>
</div>
