<?php

class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "car_rental";

    // Sử dụng biến static để giữ kết nối duy nhất
    private static $sharedConn = null;
    public $conn;

    public function __construct(){
        // Nếu đã có kết nối trước đó, tái sử dụng nó
        if (self::$sharedConn === null) {
            self::$sharedConn = new mysqli(
                $this->host,
                $this->user,
                $this->pass,
                $this->dbname
            );

            if (self::$sharedConn->connect_error) {
                die("Connection failed: " . self::$sharedConn->connect_error);
            }
            
            // Đảm bảo tiếng Việt hiển thị đúng
            self::$sharedConn->set_charset("utf8mb4");
        }

        $this->conn = self::$sharedConn;
    }
}