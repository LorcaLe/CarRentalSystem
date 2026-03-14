<?php

require_once __DIR__ . "/../../config/database.php";

class User {

private $conn;

public function __construct(){

$db = new Database();

$this->conn = $db->conn;

}

public function register($name,$email,$phone,$password){

$sql="INSERT INTO users(name,email,phone,password)
VALUES(?,?,?,?)";

$stmt=$this->conn->prepare($sql);

$stmt->bind_param("ssss",$name,$email,$phone,$password);

$stmt->execute();

}

public function login($identifier){

$sql="SELECT * FROM users 
WHERE email=? OR phone=?";

$stmt=$this->conn->prepare($sql);

$stmt->bind_param("ss",$identifier,$identifier);

$stmt->execute();

$result=$stmt->get_result();

return $result->fetch_assoc();

}

public function updateAvatar($userId,$avatar){

$sql="UPDATE users SET avatar=? WHERE id=?";

$stmt=$this->conn->prepare($sql);

$stmt->bind_param("si",$avatar,$userId);

$stmt->execute();

}

}