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
    $identity = trim($data['identity'] ?? '');
    $password = $data['password'] ?? '';
    $confirm_password = $data['confirm_password'] ?? '';
    $name = trim($data['name'] ?? 'New User');

    // 1. Kiểm tra khớp mật khẩu
    if ($password !== $confirm_password) {
        return "Confirm password does not match.";
    }

    // 2. Nhận diện Email/Phone
    $isEmail = filter_var($identity, FILTER_VALIDATE_EMAIL);
    $isPhone = preg_match('/^[0-9]{10,11}$/', $identity);
    if (!$isEmail && !$isPhone) return "Invalid Email or Phone format.";

    // 3. KIỂM TRA TRÙNG LẶP (Dòng này hay gây lỗi nếu bind sai)
    $checkSql = "SELECT id FROM users WHERE email = ? OR phone = ?";
    $stmt = $this->conn->prepare($checkSql);
    $stmt->bind_param("ss", $identity, $identity); // 2 dấu ?, 2 biến -> ĐÚNG
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        return "This Email or Phone number is already registered.";
    }

    // 4. MÃ HÓA VÀ LƯU DATABASE
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // Câu SQL này có 4 dấu hỏi (?)
    $sql = "INSERT INTO users (name, email, phone, password, role, membership_level) VALUES (?, ?, ?, ?, 'user', 'basic')";
    $insertStmt = $this->conn->prepare($sql);

    if (!$insertStmt) {
        // Nếu vẫn lỗi, dòng này sẽ hiện tên cột bị sai trong Database
        die("SQL Prepare Error: " . $this->conn->error); 
    }

    $emailField = $isEmail ? $identity : null;
    $phoneField = $isPhone ? $identity : null;

    // QUAN TRỌNG: 4 dấu hỏi thì phải có 4 chữ "s" và 4 biến
    $insertStmt->bind_param("ssss", $name, $emailField, $phoneField, $hashedPassword);

    if ($insertStmt->execute()) {
        return true; 
    } else {
        return "Registration failed: " . $insertStmt->error;
    }
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