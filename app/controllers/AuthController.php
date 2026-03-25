<?php
// app/controllers/AuthController.php

require_once __DIR__ . "/../models/User.php";

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Handle user login and role-based redirection
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $identifier = $_POST['identifier'];
            $password = $_POST['password'];

            $user = $this->userModel->login($identifier);

            // Verify password and check if user exists
            if ($user && password_verify($password, $user['password'])) {
                
                // Set Session with role for RBAC
                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email'],
                    'role'  => $user['role'] // 'user', 'staff', or 'admin'
                ];

                // Role-based redirection logic
                switch ($user['role']) {
                    case 'admin':
                        header("Location: /car_rental/public/admin/dashboard");
                        break;
                    case 'staff':
                        header("Location: /car_rental/public/staff/dashboard");
                        break;
                    default:
                        header("Location: /car_rental/public/");
                }
                exit;

            } else {
                echo "Invalid login credentials";
            }
        } else {
            require __DIR__ . "/../views/auth/login.php";
        }
    }

    /**
     * Handle new user registration
     */
    public function register() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST["name"];
            $identifier = $_POST["identifier"];
            // Password hashing for security
            $password = $_POST["password"]; 

            $email = null;
            $phone = null;

            if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
                $email = $identifier;
            } else {
                $phone = $identifier;
            }

            // Default role 'user' is handled within User model or SQL default
            $this->userModel->register($name, $email, $phone, $password);

            header("Location: /car_rental/public/login");
        } else {
            require __DIR__ . "/../views/auth/register.php";
        }
    }

    /**
     * Clear session and logout
     */
    public function logout() {
        session_destroy();
        header("Location: /car_rental/public");
        exit;
    }

/* 1. GỬI OTP */
public function forgotPassword(){
    $identifier = $_POST['identifier'] ?? null;
    if(!$identifier){ echo "Please enter email or phone"; return; }

    $conn = (new Database())->conn;
    
    // Kiểm tra tài khoản tồn tại
    $stmt = $conn->prepare("SELECT id, email, phone FROM users WHERE email=? OR phone=?");
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if(!$user){
        echo "Account not found";
        return;
    }

    $otp = rand(100000, 999999);
    
    // Lưu OTP và Identifier vào Session để dùng cho bước cuối
    $_SESSION['reset_otp'] = $otp;
    $_SESSION['reset_id'] = $user['id']; 
    $_SESSION['otp_expire'] = time() + 300; // Hết hạn sau 5 phút

    // Cập nhật vào DB để backup (không bắt buộc nhưng nên làm)
    $stmt = $conn->prepare("UPDATE users SET reset_otp=?, otp_expire=DATE_ADD(NOW(), INTERVAL 5 MINUTE) WHERE id=?");
    $stmt->bind_param("si", $otp, $user['id']);
    $stmt->execute();

    echo "OTP sent: " . $otp;
}

/* 2. XÁC THỰC OTP */
public function verifyOTP(){
    $otp = $_POST['otp'] ?? '';
    
    if(isset($_SESSION['reset_otp']) && $_SESSION['reset_otp'] == $otp && time() < $_SESSION['otp_expire']){
        echo "OTP verified";
    } else {
        echo "Invalid or expired OTP";
    }
}

/* 3. RESET PASSWORD - ĐÂY LÀ KHÚC LORCA BỊ LỖI */
public function resetPassword() {
    // 1. Lấy dữ liệu từ POST (do JavaScript gửi lên)
    $password = $_POST['password'] ?? null;
    $otp = $_POST['otp'] ?? null;

    if (!$password || !$otp) {
        echo "Missing password or OTP code.";
        return;
    }

    // 2. Hash mật khẩu mới
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    $conn = (new Database())->conn;

    // 3. TÌM USER THEO OTP TRƯỚC (Để lấy ID chuẩn xác)
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_otp = ? AND otp_expire > NOW()");
    $stmt->bind_param("s", $otp);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "Invalid or expired OTP. Please try again.";
        return;
    }

    $userId = $user['id'];

    // 4. CẬP NHẬT MẬT KHẨU (Dùng ID để chắc chắn 100%)
    // Chú ý: Cột password phải đủ độ dài (thường là VARCHAR(255))
    $updateStmt = $conn->prepare("UPDATE users SET password = ?, reset_otp = NULL, otp_expire = NULL WHERE id = ?");
    $updateStmt->bind_param("si", $hashedPassword, $userId);
    
    if ($updateStmt->execute()) {
        // Kiểm tra xem có dòng nào bị tác động không
        if ($updateStmt->affected_rows > 0) {
            echo "Password updated successfully!";
        } else {
            // Trường hợp này xảy ra nếu mật khẩu mới GIỐNG HỆT mật khẩu cũ
            echo "New password cannot be the same as the old one.";
        }
    } else {
        echo "Database error: " . $conn->error;
    }
}

public function profile(){

echo json_encode($_SESSION['user']);

}

public function updateProfile() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_SESSION['user']['id'];
        $data = [
            'name'  => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone']
        ];

        if ($this->userModel->updateProfile($userId, $data)) {
            // Cập nhật lại Session để tên mới hiện lên ngay
            $_SESSION['user']['name'] = $data['name'];
            echo "success";
        } else {
            echo "error";
        }
    }
}
public function updatePayment() {
    $userId = $_SESSION['user']['id'];
    $method = $_POST['pay_method'];
    $info = $_POST['pay_info']; // Số thẻ hoặc số MoMo

    if ($this->userModel->updatePayment($userId, $method, $info)) {
        echo "Payment method linked successfully!";
    }
}

public function savePayment() {
    if (!isset($_SESSION['user']['id'])) {
        echo "unauthorized";
        return;
    }

    $userId = $_SESSION['user']['id'];
    $method = $_POST['pay_method'];

    // 1. Lấy dữ liệu hiện tại đang có trong Database
    $currentUser = $this->userModel->getUserById($userId);

    // 2. Thiết lập mảng dữ liệu mặc định là dữ liệu cũ
    $data = [
        'method'   => $method, // Cập nhật phương thức ưu tiên mới
        'card_num' => $currentUser['credit_card_number'],
        'expiry'   => $currentUser['credit_expiry_date'],
        'cvv'      => $currentUser['credit_cvv'],
        'momo'     => $currentUser['momo_phone']
    ];

    // 3. Nếu người dùng nhập ở tab Credit, chỉ ghi đè các trường Credit
    if ($method === 'credit') {
        $newCard = $_POST['card_number'] ?? '';
        $newCVV = $_POST['cvv'] ?? '';

        // Chỉ cập nhật nếu người dùng nhập số thực (không phải dấu ****)
        if (!empty($newCard) && !str_contains($newCard, '*')) {
            $data['card_num'] = $newCard;
        }
        $data['expiry'] = $_POST['expiry_date'] ?? $data['expiry'];
        
        if (!empty($newCVV) && !str_contains($newCVV, '*')) {
            $data['cvv'] = $newCVV;
        }
    } 
    // 4. Nếu người dùng nhập ở tab MoMo, chỉ ghi đè trường MoMo
    else if ($method === 'momo') {
        $newMomo = $_POST['momo_phone'] ?? '';
        if (!empty($newMomo)) {
            $data['momo'] = $newMomo;
        }
    }

    // 5. Lưu lại toàn bộ (bao gồm cả cái cũ và cái mới)
    if ($this->userModel->updatePaymentInfo($userId, $data)) {
        echo "success";
    } else {
        echo "error";
    }
}
public function handleUpdatePasswordWithOTP() {
    $newPass = $_POST['new_password'];
    $confirmPass = $_POST['confirm_password'];
    $otp = $_POST['otp'];
    $userId = $_SESSION['user']['id'];

    // 1. Kiểm tra khớp mật khẩu ngay tại Server (Lớp bảo mật thứ 2)
    if ($newPass !== $confirmPass) {
        echo "Passwords do not match!";
        return;
    }

    // 2. Kiểm tra OTP (Dùng lại logic xác thực Lorca đã có)
    $conn = (new Database())->conn;
    $stmt = $conn->prepare("SELECT id FROM users WHERE id = ? AND reset_otp = ? AND otp_expire > NOW()");
    $stmt->bind_param("is", $userId, $otp);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows > 0) {
        // 3. Cập nhật mật khẩu mới (Đã Hash)
        $hashed = password_hash($newPass, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = ?, reset_otp = NULL WHERE id = ?");
        $update->bind_param("si", $hashed, $userId);
        $update->execute();
        echo "Success: Password updated!";
    } else {
        echo "Error: Invalid or expired OTP!";
    }
}
// AuthController.php
// Trong AuthController.php (Hàm xử lý đăng ký)
public function handleRegister() {
    $result = $this->userModel->register($_POST);

    if ($result === true) {
        // Thay vì header ngay, ta load lại view Register với biến success
        $_SESSION['success_msg'] = "Your account has been created successfully!";
        header("Location: /car_rental/public/register"); // Quay lại register để hiện thông báo
        exit();
    } else {
        $_SESSION['error_msg'] = $result;
        header("Location: /car_rental/public/register");
        exit();
    }
}
public function handleLogin() {
    $identifier = $_POST['identifier'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        $_SESSION['error_msg'] = "Please enter both Email/Phone and Password";
        header("Location: /car_rental/public/login");
        exit();
    }

    // 1. Tìm user trong Database dựa trên Email hoặc Phone
    $conn = (new Database())->conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ? LIMIT 1");
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    // 2. Kiểm tra user có tồn tại và khớp mật khẩu không
    if ($user && password_verify($password, $user['password'])) {
        
        // Đăng nhập thành công -> Lưu thông tin vào Session
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'email' => $user['email'],
            'role'  => $user['role']
        ];

        // Chuyển hướng theo quyền (Role-based)
        if ($user['role'] === 'admin') {
            header("Location: /car_rental/public/admin/dashboard");
        } else {
            header("Location: /car_rental/public/"); // Về trang chủ
        }
        exit();
        
    } else {
        // Sai tài khoản hoặc mật khẩu
        $_SESSION['error_msg'] = "Invalid Email/Phone or Password!";
        header("Location: /car_rental/public/login");
        exit();
    }
}
public function showProfilePage() {
    // 1. Kiểm tra session bảo mật
    if (!isset($_SESSION['user']['id'])) {
        header("Location: /car_rental/public/login");
        exit();
    }

    $userId = $_SESSION['user']['id'];
    $user = $this->userModel->getUserById($userId);

    // 2. Xử lý hiển thị Credit Card (Che số thẻ)
    $maskedCard = "";
    if (!empty($user['credit_card_number'])) {
        // Lấy 4 số cuối, ví dụ: **** **** **** 1234
        $last4 = substr($user['credit_card_number'], -4);
        $maskedCard = "**** **** **** " . $last4;
    }

    // 3. CVV luôn luôn hiển thị là dấu sao để bảo mật tuyệt đối
    $maskedCVV = !empty($user['credit_cvv']) ? "***" : "";

    // 4. Expiry Date: Giữ nguyên MM/YY
    $expiryDate = $user['credit_expiry_date'] ?? "";

    // 5. MoMo Phone: Load từ database
    // Bạn có thể để nguyên hoặc che 3 số giữa (ví dụ: 098****123) nếu muốn "Luxury" hơn
    $momoPhone = $user['momo_phone'] ?? "";

    // 6. Payment Method hiện tại (Để biết tab nào cần Active)
    $currentMethod = $user['payment_method'] ?? 'credit';

    // Gọi View và truyền toàn bộ biến đã xử lý
    require_once __DIR__ . "/../views/auth/profile.php";
}

// BƯỚC 1: Xác thực mật khẩu cũ (Gọi từ AJAX)
public function verifyCurrentPassword() {
    $userId = $_SESSION['user']['id'];
    // Dùng trim() để xóa khoảng trắng 2 đầu
    $currentInput = trim($_POST['current_password'] ?? '');

    $user = $this->userModel->getUserById($userId);

    if ($user) {
        // Trim cả dữ liệu từ DB cho chắc ăn (đôi khi DB bị thừa space)
        $hashFromDB = trim($user['password']);

        if (password_verify($currentInput, $hashFromDB)) {
            echo "correct";
        } else {
            echo "incorrect";
        }
    } else {
        echo "user_not_found";
    }
}

// BƯỚC 3: Cập nhật mật khẩu mới (Sau khi đã qua bước OTP giả)
// File: app/controllers/AuthController.php

public function updatePassword() {
    $userId = $_SESSION['user']['id'];
    $newPw = $_POST['password'] ?? '';

    // 1. Kiểm tra yêu cầu mật khẩu
    if (strlen($newPw) < 8 || 
        !preg_match('/[A-Z]/', $newPw) || 
        !preg_match('/[a-z]/', $newPw) || 
        !preg_match('/[0-9]/', $newPw)) 
    {
        echo "weak_password";
        return;
    }

    // 2. Mã hóa mật khẩu
    $hashedPw = password_hash($newPw, PASSWORD_DEFAULT);

    // 3. Gọi Model để lưu vào Database
    if ($this->userModel->updatePassword($userId, $hashedPw)) {
        echo "updated";
    } else {
        echo "error";
    }
}
}