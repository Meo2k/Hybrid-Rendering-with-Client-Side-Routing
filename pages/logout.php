<?php
if(isset($_COOKIE['PHPSESSID'])){
    session_start();
    session_destroy();
    setcookie('PHPSESSID', '', 0 , '/'); 
}
exit;
?>