<?php
 
class DB {
    private static $conn; 

    public static function connect(){
        $username = "root"; 
        $password = ""; 
        $servername = "localhost"; 
        $dbname = "php_test"; 
        if(!self::$conn){
            self::$conn = new mysqli($servername, $username, $password, $dbname); 
        }
        return self::$conn; 
    }
    
    public static function read(){
        $sql = "SELECT * FROM php "; 
        $conn = self::connect(); 
        $result = $conn->query($sql); 
        if(!$result){
            return "Error" . $conn->error; 
        } else {
            $data = []; 
            while($row = $result->fetch_assoc()){
                $data[] = $row; 
            }
            return $data; 
        }
    }
    public static function read_one($field, $condition){
        $sql = "SELECT $field FROM php WHERE username = '$condition'"; 
        $conn = self::connect(); 
        $result = $conn->query($sql); 
        if(!$result){
            return "Error" . $conn->error; 
        } else {
            $row = $result->fetch_assoc(); // Lấy một hàng duy nhất
            return $row ? $row[$field] : null;
        }
    }

    public static function write($user_name, $pw){
        $sql = "INSERT INTO php(username, password) VALUES(?, ?)"; 
        $stmt = self::$conn->prepare($sql); 
        $stmt->bind_param("ss", $user_name, $pw); 
        $stmt->execute(); 
    }
    
    public static function write_placeOTP($user_name, $otp, $expire){
        $sql = "UPDATE php SET otp = ? , expireOTP = ? WHERE username = ?"; 
        $stmt = self::$conn->prepare($sql); 
        $stmt->bind_param("sss", $otp, $expire, $user_name); 
        $stmt->execute(); 
    }
    
    public static function changePW($user_name, $pw){
        $sql = "UPDATE php SET password = ? WHERE username = '$user_name'"; 
        $stmt = self::$conn->prepare($sql); 
        $stmt->bind_param("s", $pw); 
        $stmt->execute(); 
    }

    public static function close(){
        self::$conn->close(); 
    }
    
}; 







?>