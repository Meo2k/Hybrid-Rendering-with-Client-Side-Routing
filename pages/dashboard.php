<?php
session_start();
if (!isset($_SESSION['username'])) {
header("Location: login.php");
exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<style>
body {
font-family: Arial, sans-serif;
background-color: #f4f4f4;
margin: 0;
padding: 0;
}
.container {
width: 80%;
margin: 0 auto;
padding: 20px;
background-color: #fff;
border-radius: 5px;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
margin-top: 50px;
}
h2 {
text-align: center;

margin-bottom: 20px;
}
.container p {
text-align: center;
font-size: 18px;
color: #333;
}
.container a {
display: block;
width: 100px;
margin: 20px auto;
padding: 10px;
text-align: center;
color: #fff;
background-color: #4caf50;
text-decoration: none;
border-radius: 5px;
transition: background-color 0.3s ease;
}
.container a:hover {
background-color: #45a049;
}
</style>
</head>
<body>
<div class="container">
<h2>Xin chào, <?php echo $_SESSION['username'];

?></h2>

<p>Đây là trang dashboard của bạn.</p>
<a href="/hybrid_rendering/logout" data-route>Đăng Xuất</a>
</div>
</body>
</html>