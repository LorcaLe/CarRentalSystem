<?php 
if (session_status() === PHP_SESSION_NONE) session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BonBonCar</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* --- 1. Reset & Nền tảng Fix lỗi nhảy nền --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            overflow-x: hidden; /* Chặn thanh cuộn ngang */
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* --- 2. Container Nền: Dùng fixed để chặn lỗi "sáng chân" --- */
        .login-container {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('/car_rental/assets/images/bg-login.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 1;
        }

        /* Lớp phủ làm tối nền */
        .login-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 1;
        }

        /* --- 3. Thẻ Card Kính Mờ (Nâng cấp độ trắng để không bị xám khi hiện Pop-up) --- */
        .login-card {
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.9); 
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 50px 40px;
            border-radius: 24px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.4);
            text-align: center;
            transition: transform 0.3s ease;
        }

        /* --- 4. CSS ĐẶC TRỊ LỖI SWEETALERT --- */
        body.swal2-shown {
            padding-right: 0 !important; /* Chặn giật màn hình */
        }

        .swal2-shown .login-card {
            background: #ffffff !important; /* Giữ card luôn trắng khi có pop-up */
            backdrop-filter: none !important;
        }

        .login-card:hover { transform: translateY(-5px); }
        .login-card h2 { font-size: 30px; color: #1e293b; margin-bottom: 8px; font-weight: 700; }
        .sub { color: #475569; font-size: 14px; margin-bottom: 35px; }

        /* --- 5. Form & Input Groups --- */
        .input-group { position: relative; margin-bottom: 22px; text-align: left; }
        .input-group label { display: block; font-weight: 600; font-size: 14px; color: #1e293b; margin-bottom: 8px; margin-left: 5px; }
        
        .input-group input {
            width: 100%;
            padding: 14px 18px;
            padding-right: 50px;
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        /* Icon con mắt chuẩn tâm */
        .input-group i {
            position: absolute;
            right: 18px;
            top: 48px; 
            transform: translateY(-50%);
            cursor: pointer;
            color: #94a3b8;
            font-size: 1.2rem;
            z-index: 10;
        }

        .btn-login {
            width: 100%;
            padding: 15px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .btn-login:hover { background: #1d4ed8; }

        .divider { margin: 25px 0; display: flex; align-items: center; color: #94a3b8; font-size: 13px; }
        .divider::before, .divider::after { content: ""; flex: 1; height: 1px; background: #e2e8f0; margin: 0 10px; }
        .create { text-decoration: none; color: #2563eb; font-weight: 600; font-size: 14px; }
    </style>
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