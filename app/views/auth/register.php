<?php 
if (session_status() === PHP_SESSION_NONE) session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="/car_rental/assets/css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
/* --- 1. Reset & Nền tảng --- */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
}

body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

/* --- 2. Container Nền (Dùng chung ảnh với Login) --- */
.login-container {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-image: url('/car_rental/assets/images/bg-login.jpg');
    background-size: cover;
    background-position: center;
    position: relative;
}

.login-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.35); /* Làm tối hơn một chút để nổi bật form dài */
    z-index: 1;
}

/* --- 3. Thẻ Card Kính Mờ (Nâng cấp độ bo góc) --- */
.login-card {
    position: relative;
    z-index: 10;
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    padding: 40px;
    border-radius: 24px;
    width: 100%;
    max-width: 440px; /* Form register cần rộng hơn một chút */
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.3);
    text-align: center;
}

.login-card h2 {
    font-size: 28px;
    color: #1e293b;
    margin-bottom: 6px;
    font-weight: 700;
}

.sub {
    color: #475569;
    font-size: 14px;
    margin-bottom: 30px;
}

/* --- 4. Form & Input Groups --- */
.input-group {
    position: relative;
    margin-bottom: 20px;
    text-align: left;
}

.input-group label {
    display: block;
    font-weight: 600;
    font-size: 13px;
    color: #1e293b;
    margin-bottom: 6px;
    margin-left: 5px;
}

.input-group input {
    width: 100%;
    padding: 12px 16px;
    padding-right: 45px;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    font-size: 15px;
    outline: none;
    transition: all 0.3s;
}

.input-group input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    background: #fff;
}

/* --- 5. ĐỊNH VỊ ICON CON MẮT (Fix lỗi lệch tâm cho Register) --- */
.input-group i {
    position: absolute;
    right: 15px;
    /* top: 41px là điểm vàng khi input cao ~45px và label cao ~20px */
    top: 41px; 
    transform: translateY(-50%);
    cursor: pointer;
    color: #94a3b8;
    font-size: 1.1rem;
    z-index: 10;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.input-group i:hover {
    color: #2563eb;
}

/* Style cho dòng gợi ý mật khẩu */
#passwordHint {
    margin-top: 5px;
    margin-left: 5px;
    font-weight: 500;
    transition: all 0.3s;
}

/* --- 6. Nút bấm & Divider --- */
.btn-register {
    width: 100%;
    padding: 14px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
    margin-top: 15px;
    box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
}

.btn-register:hover {
    background: #1d4ed8;
    transform: translateY(-1px);
    box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.4);
}

.divider {
    margin: 25px 0;
    display: flex;
    align-items: center;
    color: #94a3b8;
    font-size: 13px;
}

.divider::before, .divider::after {
    content: "";
    flex: 1;
    height: 1px;
    background: #e2e8f0;
    margin: 0 10px;
}

.create {
    text-decoration: none;
    color: #2563eb;
    font-weight: 600;
    font-size: 14px;
    transition: 0.2s;
}

.create:hover {
    text-decoration: underline;
    color: #1d4ed8;
}
</style>
<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Create Account</h2>
            <p class="sub">Register to rent your first car</p>

            <form id="registerForm" action="/car_rental/public/register" method="POST">

                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required placeholder="Enter your full name">
                </div>
                <div class="input-group">
                    <label>Email or Phone Number</label>
                    <input type="text" name="identity" required placeholder="example@mail.com or 090xxxxxxx">
                </div>

                <div class="input-group" style="position: relative;">
                    <label>Password</label>
                    <input type="password" name="password" id="password" required 
                           pattern="^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$"
                           oninput="checkPasswordMatch()" style="padding-right: 40px;">
                    <i class="fas fa-eye-slash" id="togglePassword" style="position: absolute; right: 15px; top: 43px; cursor: pointer; color: #666;"></i>
                    <small id="passwordHint" style="color: #666; font-size: 0.75rem; display: block; margin-top: 5px;">At least 8 chars, 1 uppercase, 1 number, 1 symbol.</small>
                </div>

                <div class="input-group" style="position: relative; margin-top: 15px;">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required 
                           oninput="checkPasswordMatch()" style="padding-right: 40px;">
                    <i class="fas fa-eye-slash" id="toggleConfirmPassword" style="position: absolute; right: 15px; top: 43px; cursor: pointer; color: #666;"></i>
                </div>

                <button type="submit" class="btn-register">Create an Account</button>
            </form>

            <div class="divider">OR</div>
            <a href="/car_rental/app/views/auth/login.php" class="create">Already have account?</a>
        </div>
    </div>
</body>

<script>

<?php if(isset($_SESSION['error_msg'])): ?>
    Swal.fire({
        icon: 'error',
        title: 'Registration Failed',
        text: '<?php echo $_SESSION['error_msg']; ?>',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Try Again'
    });
    <?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

document.addEventListener("DOMContentLoaded", function() {
    // Thông báo THÀNH CÔNG
    <?php if(isset($_SESSION['success_msg'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Congratulations!',
            text: '<?= $_SESSION['success_msg'] ?>',
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Go to Login'
        }).then((result) => {
            if (result.isConfirmed) {
                // Chỉ chuyển trang khi người dùng bấm nút
                window.location.href = "/car_rental/public/login";
            }
        });
        <?php unset($_SESSION['success_msg']); ?>
    <?php endif; ?>
    
    // Thông báo LỖI (Trùng email/phone...)
    <?php if(isset($_SESSION['error_msg'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '<?= $_SESSION['error_msg'] ?>',
            confirmButtonColor: '#d33'
        });
        <?php unset($_SESSION['error_msg']); ?>
    <?php endif; ?>
});

// Hàm kiểm tra mật khẩu
function checkPasswordMatch() {
    const password = document.getElementById("password");
    const confirm = document.getElementById("confirm_password");
    const error = document.getElementById("passwordError");
    const hint = document.getElementById("passwordHint");

    // Đổi màu gợi ý sức mạnh mật khẩu
    const pattern = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/;
    if (pattern.test(password.value)) {
        hint.style.color = "#28a745";
        hint.innerHTML = "✅ Strong password";
    } else {
        hint.style.color = "#666";
        hint.innerHTML = "At least 8 chars, 1 uppercase, 1 number, 1 symbol.";
    }

    // Kiểm tra khớp nhau
    if (confirm.value === "") {
        error.style.display = "none";
        return;
    }
    if (password.value !== confirm.value) {
        error.style.display = "block";
        confirm.style.borderColor = "#ff4d4d";
    } else {
        error.style.display = "none";
        confirm.style.borderColor = "#28a745";
    }
}

// Chặn submit nếu chưa khớp
document.getElementById("registerForm").addEventListener("submit", function(e) {
    const pass = document.getElementById("password").value;
    const confirm = document.getElementById("confirm_password").value;
    if (pass !== confirm) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: 'Passwords do not match!',
            confirmButtonColor: '#d33'
        });
    }
});

// Ẩn/hiện mật khẩu
function setupPasswordToggle(toggleId, inputId) {
    const toggle = document.getElementById(toggleId);
    const input = document.getElementById(inputId);

    toggle.addEventListener('click', function() {
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        this.classList.toggle('fa-eye-slash', !isPassword);
        this.classList.toggle('fa-eye', isPassword);
        this.style.color = isPassword ? "#2563eb" : "#666";
    });
}
setupPasswordToggle('togglePassword', 'password');
setupPasswordToggle('toggleConfirmPassword', 'confirm_password');
</script>
</html>