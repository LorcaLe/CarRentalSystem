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
    <link rel="stylesheet" href="/car_rental/assets/css/auth/register.css">
</head>
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