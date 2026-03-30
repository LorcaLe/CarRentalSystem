<?php 
if (session_status() === PHP_SESSION_NONE) session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PrivateHire Cars</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/car_rental/assets/css/auth/login.css">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <h2>Welcome Back</h2>
            <p class="sub">Login to continue your journey</p>

            <form method="POST" action="/car_rental/public/login">
                <div class="input-group">
                    <label>Email or Phone Number</label>
                    <input type="text" name="identifier" required placeholder="Enter your email or phone">
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password" required placeholder="Enter your password">
                    <i class="fas fa-eye-slash" id="togglePassword"></i>
                </div>

                <p style="text-align: right; margin-bottom: 20px;">
                    <a href="/car_rental/public/forgot-password" style="font-size: 13px; color: #2563eb; text-decoration: none; font-weight: 600;">
                        Forgot password?
                    </a>
                </p>

                <button type="submit" class="btn-login">Login</button>
            </form>

            <div class="divider">OR</div>

            <a href="/car_rental/public/register" class="create">Create an account</a>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Thông báo THÀNH CÔNG
        <?php if(isset($_SESSION['success_msg'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Congratulations!',
                text: '<?= $_SESSION['success_msg'] ?>',
                confirmButtonColor: '#28a745'
            });
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>

        // 2. Thông báo LỖI (Quan trọng: Sửa lại màu nút và hiệu ứng)
        <?php if(isset($_SESSION['error_msg'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: '<?= $_SESSION['error_msg'] ?>',
                confirmButtonColor: '#d33',
                confirmButtonText: 'Try Again'
            });
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>
    });

    // 3. Logic ẩn/hiện mật khẩu
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function() {
        const isPassword = password.type === 'password';
        password.type = isPassword ? 'text' : 'password';
        this.classList.toggle('fa-eye', isPassword);
        this.classList.toggle('fa-eye-slash', !isPassword);
        this.style.color = isPassword ? "#2563eb" : "#94a3b8";
    });
    </script>
</body>
</html>