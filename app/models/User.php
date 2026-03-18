<?php

require_once __DIR__ . "/../../config/database.php";

class User {
    private $conn;

    public function __construct(){
        // Khởi tạo database (Sử dụng lớp Database có Static Connection mà chúng ta đã sửa)
        $db = new Database();
        $this->conn = $db->conn;
    }

    public function register($name, $email, $phone, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Role will be 'user' by default due to SQL schema
        $sql = "INSERT INTO users(name, email, phone, password) VALUES(?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword);
        return $stmt->execute();
    }

    public function login($identifier) {
        // Make sure to select the role column
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = ? OR phone = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Thêm hàm lấy thông tin User theo ID (Dùng cho trang Profile)
    public function getUserById($id) {
        $sql = "SELECT id, name, email, phone, avatar FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateAvatar($userId, $avatar){
        $sql = "UPDATE users SET avatar = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $avatar, $userId);
        return $stmt->execute();
    }

        /**
     * Count total registered users
     * @return int
     */
    public function countAllUsers() {
        $sql = "SELECT COUNT(*) as total FROM users";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }

    // app/models/User.php

    /**
     * Get all registered users
     */
    public function getAllUsers() {
        // Select essential columns only
        $sql = "SELECT id, name, email, phone, role, created_at FROM users ORDER BY id DESC";
        $result = $this->conn->query($sql);
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    /**
     * Update a user's role
     */
    public function updateUserRole($id, $role) {
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $role, $id);
        return $stmt->execute();
    }
}