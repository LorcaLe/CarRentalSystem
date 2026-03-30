<?php

require_once __DIR__ . "/../../config/database.php";

class User {
    private $conn;

    public function __construct(){
        // Khởi tạo database (Sử dụng lớp Database có Static Connection mà chúng ta đã sửa)
        $db = new Database();
        $this->conn = $db->conn;
    }

public function register($data) {
    $identity         = trim($data['identity'] ?? '');
    $password         = $data['password']         ?? '';
    $confirm_password = $data['confirm_password'] ?? '';
    $name             = trim($data['name']        ?? 'New User');
    $role             = $data['role']             ?? 'user'; // thêm dòng này

    if ($password !== $confirm_password) {
        return "Confirm password does not match.";
    }

    $isEmail = filter_var($identity, FILTER_VALIDATE_EMAIL);
    $isPhone = preg_match('/^[0-9]{10,11}$/', $identity);
    if (!$isEmail && !$isPhone) return "Invalid Email or Phone format.";

    $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ? OR phone = ? LIMIT 1");
    $stmt->bind_param("ss", $identity, $identity);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        return "This Email or Phone number is already registered.";
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $emailField     = $isEmail ? $identity : null;
    $phoneField     = $isPhone ? $identity : null;

    // thêm role vào INSERT, bỏ hardcode 'user'
    $insertStmt = $this->conn->prepare(
        "INSERT INTO users (name, email, phone, password, role, membership_level)
         VALUES (?, ?, ?, ?, ?, 'basic')"
    );

    if (!$insertStmt) {
        return "SQL Error: " . $this->conn->error;
    }

    // 5 dấu ? → 5 chữ s → 5 biến
    $insertStmt->bind_param("sssss", $name, $emailField, $phoneField, $hashedPassword, $role);

    return $insertStmt->execute() ? true : "Registration failed: " . $insertStmt->error;
}

    // Tạo mã OTP và lưu vào DB
    public function generateOTP($phone) {
        $otp = rand(100000, 999999);
        // Hết hạn sau 5 phút
        $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes")); 
        
        $sql = "UPDATE users SET reset_otp = ?, otp_expire = ? WHERE phone = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $otp, $expiry, $phone);
        return $stmt->execute() ? $otp : false;
    }

    // Kiểm tra mã OTP khách nhập vào
    public function verifyOTP($phone, $inputOtp) {
        $sql = "SELECT id FROM users WHERE phone = ? AND reset_otp = ? AND otp_expire > NOW()";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $phone, $inputOtp);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            // Xác thực thành công -> Xóa mã OTP để không dùng lại được
            $this->conn->query("UPDATE users SET reset_otp = NULL, otp_expire = NULL WHERE phone = '$phone'");
            return true;
        }
        return false;
    }

    // User.php
public function isOtpExpired($phone) {
    $sql = "SELECT otp_expire FROM users WHERE phone = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result && strtotime($result['otp_expire']) < time()) {
        return true; // Đã hết hạn
    }
    return false; // Còn hạn
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
        // Phải là SELECT * hoặc liệt kê đầy đủ các cột mới
        $sql = "SELECT id, name, email, password, phone, payment_method, credit_card_number, credit_expiry_date, credit_cvv, momo_phone FROM users WHERE id = ?";
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

    public function updateProfile($id, $data) {
        $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $data['name'], $data['email'], $data['phone'], $id);
        return $stmt->execute();
    }

    public function updatePaymentInfo($userId, $data) {
        $sql = "UPDATE users SET 
                payment_method = ?, 
                credit_card_number = ?, 
                credit_expiry_date = ?, 
                credit_cvv = ?, 
                momo_phone = ? 
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssi", 
            $data['method'], 
            $data['card_num'], 
            $data['expiry'], 
            $data['cvv'], 
            $data['momo'], 
            $userId
        );
        return $stmt->execute();
    }
// File: app/models/User.php (Dòng 211 nằm ở đây)

public function updatePassword($userId, $hashedPw) {
    // Dùng kết nối database trực tiếp ($this->conn)
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    
    $stmt = $this->conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("si", $hashedPw, $userId);
        return $stmt->execute();
    }
    return false;
}
}