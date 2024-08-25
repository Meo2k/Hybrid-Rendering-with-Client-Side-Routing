<?php
require_once "db.php"; 
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;




DB::connect(); 
$result = DB::read(); 


$error = '';

function generateOTP($length = 6) {
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= mt_rand(0, 9);
    }
    return $otp;
}

function sendOTP($toEmail, $otp) {
    $mail = new PHPMailer(true);
    try {
        // Thiết lập server SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Sử dụng server SMTP của Gmail
        $mail->SMTPAuth   = true;
        $mail->Username   = 'example@gmail.com'; // Thay bằng email của bạn
        $mail->Password   = 'apppassword';  // Thay bằng mật khẩu email của bạn
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Người gửi và người nhận
        $mail->setFrom('example@gmail.com', 'Web App');
        $mail->addAddress($toEmail);

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "Your OTP code is <b>$otp</b>";
        $mail->AltBody = "Your OTP code is $otp";

        $mail->send();
    } catch (Exception $e) {
        echo json_encode("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = $_POST['username'];

    $authenticated = false;
    foreach($result as $item){
        if($userName === $item['username'] ){
            $authenticated = true;
            // Sử dụng chức năng
            $otp = generateOTP();
            sendOTP($userName, $otp);
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $expiry = date('Y-m-d H:i:s', strtotime('+5minutes')); 
            DB::write_placeOTP($userName, $otp, $expiry); 
            break;
        }
    }

    if ($authenticated) {
        // Trả về một phản hồi JSON thay vì cố gắng thực hiện chuyển hướng từ PHP
        setcookie('user_otp', $userName); 
        echo json_encode(['status' => 'success', 'redirect' => ['forgot' => '/hybrid_rendering/OTP']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Tên đăng nhập không tồn tại.']);
    }
    exit();
}
?>
<link rel="stylesheet" href="/hybrid_rendering/css/forgot.css"/>
<div class="container">
    <h2>Quên Mật Khẩu</h2>
    <h4>Nhập email bạn đã đăng kí vào bên dưới</h4><br>
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
        <br/>
        <button id="nextBtn" type="submit" onclick="
        const parent = document.querySelector('.parent'); 
        parent.classList.add('active'); 
        " style="margin-top: 20px; opacity: 0.5; pointer-events: none;">Tiếp</button>
        <p class="error"><?php echo $error; ?></p>
    </form>
    <div class="parent">
    <div class="loading" ></div>
    </div>
</div>

