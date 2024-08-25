<?php
require_once "db.php"; 

DB::connect(); 
$result = DB::read(); 


$error = '';
$change = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $passWord_change = $_POST['password_new'];
    $userName = $_COOKIE['user_otp'];
    DB::write_placeOTP($userName, NULL, NULL); 
    DB::changePW($userName, $passWord_change); 
    setcookie('user_otp', '', 0 , '/hybrid_rendering/pages'); 
    $change = true; 
    
}
if ($change) {
    // Trả về một phản hồi JSON thay vì cố gắng thực hiện chuyển hướng từ PHP
    echo json_encode(['status' => 'success', 'redirect' => ['forgot' => '/hybrid_rendering/login']]);
    exit();
}


?>
<link rel="stylesheet" href="/hybrid_rendering/css/OTP.css"/>
<div class="container">
    <h2>Thay Đổi Mật Khẩu</h2>
    <h4>Nhập mật khẩu mới của bạn . Đây sẽ là mật khẩu bạn dùng đăng nhập trong các lần tiếp theo</h4>
    <form method="post" action="">
        <div class="input-group">
            <label for="password">Mật khẩu mới:</label>
            <input type="text" id="password" name="password_new" oninput='
                const otpValue = this.value.trim();
                const nextLink = document.getElementById("nextBtn");

                if (otpValue) {
                    nextLink.style.pointerEvents = "auto";  // Kích hoạt nút
                    nextLink.style.opacity = "1";  // Đặt lại độ mờ để hiển thị rõ ràng
                } else {
                    nextLink.style.pointerEvents = "none";  // Vô hiệu hóa nút
                    nextLink.style.opacity = "0.5";  // Giảm độ mờ để hiển thị rằng nút bị vô hiệu hóa
                }
            ' required><br/>
        </div>
        <button type="submit" id="nextBtn" style="margin-top: 20px; opacity: 0.5; pointer-events: none;" onclick="
            document.querySelector('.parent').classList.add('active'); 
        ">Thay đổi</button>
    </form>
    <div class="parent">
    <div class="loading" ></div>
    </div>
</div>
