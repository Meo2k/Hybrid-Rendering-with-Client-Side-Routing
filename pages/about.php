<!-- pages/about.php -->
<?php
require_once "db.php"; 
if(isset($_COOKIE['user_otp'])){
    $userName = $_COOKIE['user_otp']; 
    DB::connect(); 
    DB::write_placeOTP($userName, NULL, NULL); 
    setcookie('user_otp', '', 0 , '/hybrid_rendering/pages');
}
echo "<h1>About Us</h1>";
echo "<p>This is the about page. The content is also rendered on the server and dynamically loaded on the client.</p>";
?>
