<?php
if (isset($_COOKIE['PHPSESSID'])) {
header("Location: /hybrid_rendering/index-main.php");
exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hybrid Rendering with Client-Side Routing</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Đảm bảo html và body chiếm 100% chiều cao */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /* Đảm bảo #app và #content chiếm toàn bộ chiều cao màn hình */
        #app {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        #content {
            flex: 1; /* Đảm bảo #content chiếm không gian còn lại sau nav */
        }
    </style>
</head>
<body>
    <div id="app">
        <nav 
        style="display: flex; 
        justify-content: space-between;
        padding: 0 5px;">
            <ul>
                <li><a href="/hybrid_rendering/home" data-route>Home</a></li>
                <li><a href="/hybrid_rendering/about" data-route>About</a></li>
            </ul>
            <ul>
                <button><a href="/hybrid_rendering/login" data-route>Log in</a></button>
                <button><a href="/hybrid_rendering/signup" data-route>Sign up</a></button>
            </ul>
        </nav>

        <div id="content">
            <!-- Nội dung sẽ được thay đổi bằng JavaScript -->
        </div>
    </div>

    <script src="js/main.js"></script>
</body>
</html>
