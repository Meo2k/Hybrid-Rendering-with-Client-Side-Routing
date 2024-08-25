<?php
require_once "db.php"; 
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

DB::connect(); 
$result = DB::read(); 

$error = '';
date_default_timezone_set('Asia/Ho_Chi_Minh');

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

function reSend(){
    $userName = $_COOKIE['user_otp']; 

    $otp = generateOTP();
    sendOTP($userName, $otp);
    $expiry = date('Y-m-d H:i:s', strtotime('+5minutes')); 
    DB::write_placeOTP($userName, $otp, $expiry); 
    return "Mã OTP đã được gửi lại!";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'resend_otp') {
        echo json_encode(reSend());
        exit;
    } else {
        $userName = $_COOKIE['user_otp'];
        $otp_db = DB::read_one('otp', $userName); 
        $expiry = DB::read_one('expireOTP', $userName); 
        $expiryTime = new DateTime($expiry);
        $otp = $_POST['otp']; 
        $time = new DateTime();
        if(($otp_db === $otp) && ($time < $expiryTime)){
            echo json_encode(['status' => 'success', 'redirect' =>  ['forgot' => '/hybrid_rendering/changepw']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'OTP không khớp hoặc đã hết hạn']);
        }
    }
    exit(); 
}

?>
<link rel="stylesheet" href="/hybrid_rendering/css/OTP.css"/>
<div class="container">
    <h2>OTP</h2>
    <h4>Chúng tôi vừa gửi 1 mã OTP đến email của bạn hãy nhập mã này vào bên dưới (mã OTP sẽ hết hạn sau 5 phút)</h4><br>
    <form method="post">
        <div class="input-group">
            <label for="username">OTP:</label>
            <input type="text" id="otp" name="otp" oninput='
                const otpValue = this.value.trim();
                const nextLink = document.getElementById("nextBtn");

                if (otpValue) {
                    nextLink.style.pointerEvents = "auto";  // Kích hoạt nút
                    nextLink.style.opacity = "1";  // Đặt lại độ mờ để hiển thị rõ ràng
                } else {
                    nextLink.style.pointerEvents = "none";  // Vô hiệu hóa nút
                    nextLink.style.opacity = "0.5";  // Giảm độ mờ để hiển thị rằng nút bị vô hiệu hóa
                }
            ' required>
        </div>
        <br/>
        <div>Bạn chưa nhận được <a id="resendLink" style="color:blue; cursor:pointer;"
        onclick="
        document.querySelector('.parent').classList.add('active');
        event.preventDefault(); // Ngăn chặn hành vi mặc định của thẻ <a>
        fetch('pages/OTP.php', {
            method: 'POST',
            
            body: new URLSearchParams({
                'action': 'resend_otp'
            })
        })
        .then(response => response.json())
        .then(data => {
            document.querySelector('.parent').classList.remove('active');
            alert(data); // Hiển thị thông báo khi thành công
        })
        .catch(error => {
            console.error('Error:', error);
        });
        
        ">Gửi lại</a></div>
        <div><b>
            <button type="submit" id="nextBtn" style="margin-top: 20px; opacity: 0.5; pointer-events: none;" onclick="
            document.querySelector('.parent').classList.add('active'); 
        ">Tiếp
            </button>
        </b></div>
       
        <p class="error"></p>
    </form>
    <div class="parent">
    <div class="loading" ></div>
    </div>
</div>


