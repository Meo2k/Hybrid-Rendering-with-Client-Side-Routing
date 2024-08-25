<!-- pages/home.php -->
<?php
require_once "db.php"; 
if(isset($_COOKIE['user_otp'])){
    $userName = $_COOKIE['user_otp']; 
    DB::connect(); 
    DB::write_placeOTP($userName, NULL, NULL); 
    setcookie('user_otp', '', 0 , '/hybrid_rendering/pages');
}

echo "<h1>Welcome to the Home Page!</h1>";
echo "<p>This content is rendered on the server using PHP and dynamically loaded on the client using JavaScript.</p>";
?>
