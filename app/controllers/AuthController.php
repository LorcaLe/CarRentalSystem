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

public function forgotPassword(){

$identifier = $_POST['identifier'] ?? null;

if(!$identifier){
echo "Please enter email or phone";
return;
}

$conn = (new Database())->conn;

/* check email or phone */

if(filter_var($identifier,FILTER_VALIDATE_EMAIL)){

$stmt=$conn->prepare("SELECT id FROM users WHERE email=?");

}else{

$stmt=$conn->prepare("SELECT id FROM users WHERE phone=?");

}

$stmt->bind_param("s",$identifier);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0){

echo "Account not found";
return;

}

/* generate OTP */

$otp = rand(100000,999999);

/* UPDATE DATABASE */

$stmt=$conn->prepare("
UPDATE users
SET reset_otp=?, otp_expire=DATE_ADD(NOW(),INTERVAL 5 MINUTE)
WHERE email=? OR phone=?
");

$stmt->bind_param("sss",$otp,$identifier,$identifier);
$stmt->execute();

/* debug */

echo "OTP sent: ".$otp;

}

public function verifyOTP(){

$otp = $_POST['otp'];

$conn = (new Database())->conn;

$stmt=$conn->prepare("
SELECT id
FROM users
WHERE reset_otp=?
AND otp_expire > NOW()
");

$stmt->bind_param("s",$otp);

$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0){

echo "Invalid or expired OTP";

}else{

echo "OTP verified";

}

}

public function resetPassword(){

$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$otp = $_POST['otp'];

$conn = (new Database())->conn;

$stmt = $conn->prepare("
UPDATE users
SET password=?, reset_otp=NULL, otp_expire=NULL
WHERE reset_otp=? AND otp_expire > NOW()
");

$stmt->bind_param("ss",$password,$otp);

$stmt->execute();

if($stmt->affected_rows > 0){
echo "Password updated";
}else{
echo "Invalid OTP";
}

}

public function profile(){

echo json_encode($_SESSION['user']);

}

public function updateProfile(){

$conn = (new Database())->conn;

$id = $_SESSION['user']['id'];

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

$stmt = $conn->prepare("
UPDATE users
SET name=?, email=?, phone=?
WHERE id=?
");

$stmt->bind_param("sssi",$name,$email,$phone,$id);

$stmt->execute();

/* cập nhật session */

$_SESSION['user']['name']=$name;
$_SESSION['user']['email']=$email;
$_SESSION['user']['phone']=$phone;

echo "Profile updated";

}

public function changePassword(){

$conn = (new Database())->conn;

$id = $_SESSION['user']['id'];

$old = $_POST['old_password'];
$new = $_POST['new_password'];

/* lấy password hiện tại */

$stmt = $conn->prepare("SELECT password FROM users WHERE id=?");

$stmt->bind_param("i",$id);

$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

/* kiểm tra password */

if(!password_verify($old,$user['password'])){

echo "Old password incorrect";

return;

}

/* update password */

$newHash = password_hash($new,PASSWORD_DEFAULT);

$stmt = $conn->prepare("
UPDATE users
SET password=?
WHERE id=?
");

$stmt->bind_param("si",$newHash,$id);

$stmt->execute();

echo "Password updated";

}

}