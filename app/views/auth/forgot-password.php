<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - BonBonCar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* --- 1. CSS Nền tảng Luxury Glassmorphism --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        
        body {
            height: 100vh; display: flex; justify-content: center; align-items: center;
            background: url('/car_rental/assets/images/bg-login.jpg') center/cover fixed;
            position: relative; overflow: hidden;
        }
        
        body::before { content: ''; position: absolute; inset: 0; background: rgba(0,0,0,0.4); z-index: 1; }

        /* 1. Ép ảnh nền luôn cố định, không bị nhảy khi có Pop-up */
html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    /* Ngăn chặn rung lắc màn hình khi thanh cuộn ẩn/hiện */
    overflow-x: hidden; 
}

/* 2. CHUẨN LUXURY: Cấu hình lại nền cho Container */
.login-container, .auth-container {
    position: fixed; /* Thay vì dùng width/height thông thường */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100vw;
    height: 100vh;
    
    background-image: url('/car_rental/assets/images/bg-login.jpg');
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1;
}

/* 3. ĐẶC TRỊ SWEETALERT: Chặn đứng việc nhảy nền */
body.swal2-shown {
    height: 100vh !important;
    overflow: hidden !important;
    padding-right: 0 !important; /* Chặn SweetAlert tự thêm padding khi ẩn thanh cuộn */
}

/* Giữ cho Card luôn trắng trẻo khi có Pop-up đè lên */
.swal2-shown .auth-card {
    background: rgba(255, 255, 255, 1) !important;
    backdrop-filter: none !important;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5) !important;
}

        .auth-card {

            position: relative;
            z-index: 10;
            
            /* 1. Quan trọng: Tăng độ đục của nền lên 0.95 hoặc 1.0 
            để nó không bị ảnh hưởng bởi lớp phủ của SweetAlert */
            background: rgba(255, 255, 255, 0.98); 
            
            /* 2. Giảm blur xuống một chút để ổn định hơn */
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            
            padding: 40px;
            border-radius: 24px;
            width: 100%;
            max-width: 400px;
            
            /* 3. Đổ bóng đậm hơn một chút để tách biệt hẳn với nền */
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4); 
            
            border: 1px solid rgba(255, 255, 255, 0.6);
            text-align: center;
            min-height: 450px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* --- 2. Hiệu ứng chuyển bước (Ẩn/Hiện) --- */
        .step-container { 
            display: none; /* Mặc định ẩn tất cả */
            animation: fadeIn 0.4s ease-in-out; 
        }
        
        .step-container.active { 
            display: block; /* Chỉ hiện step có class active */
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 { font-size: 26px; color: #1e293b; margin-bottom: 10px; font-weight: 700; }
        .sub { color: #475569; font-size: 14px; margin-bottom: 25px; line-height: 1.4; }

        /* --- 3. Input & Groups --- */
        .input-group { position: relative; margin-bottom: 20px; text-align: left; }
        .input-group label { display: block; font-weight: 600; font-size: 13px; color: #1e293b; margin-bottom: 8px; }
        
        input {
            width: 100%; padding: 13px 15px; border-radius: 12px; border: 1px solid #cbd5e1;
            font-size: 15px; outline: none; transition: 0.3s; background: white;
        }
        input:focus { border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37,99,235,0.1); }

        .input-group i { position: absolute; right: 15px; top: 38px; cursor: pointer; color: #94a3b8; }

        /* --- 4. Nút bấm --- */
        button {
            width: 100%; padding: 14px; background: #2563eb; color: white; border: none;
            border-radius: 12px; font-weight: 600; cursor: pointer; transition: 0.3s;
            margin-top: 10px; box-shadow: 0 8px 15px rgba(37,99,235,0.2);
        }
        button:hover { background: #1d4ed8; transform: translateY(-1px); }
        button:disabled { background: #94a3b8; cursor: not-allowed; transform: none; box-shadow: none; }

        .back-link { display: inline-block; margin-top: 25px; text-decoration: none; color: #2563eb; font-weight: 600; font-size: 14px; }
        small { display: block; margin-top: 5px; font-size: 12px; font-weight: 500; }
    </style>
</head>
<body>

    <div class="auth-card">
        <div id="step1" class="step-container active">
            <h2>Forgot Password</h2>
            <p class="sub">Enter your email or phone to receive a 6-digit OTP code.</p>
            <form id="formStep1">
                <div class="input-group">
                    <label>Email or Phone Number</label>
                    <input type="text" id="identifier" required placeholder="example@mail.com">
                </div>
                <button type="submit">Send OTP Code</button>
            </form>
        </div>

        <div id="step2" class="step-container">
            <h2>Verify Identity</h2>
            <p class="sub">Please enter the verification code sent to your account.</p>
            <div class="input-group">
                <label>Verification Code</label>
                <input type="text" id="otp" maxlength="6" placeholder="Enter OTP" style="text-align: center; letter-spacing: 3px; font-weight: bold;">
            </div>
            <button onclick="verifyOTP()">Verify & Continue</button>
        </div>

        <div id="step3" class="step-container">
            <h2>New Password</h2>
            <p class="sub">Set a new secure password for your account.</p>
            
            <div class="input-group">
                <label>New Password</label>
                <input type="password" id="newPassword" required oninput="validatePass()">
                <i class="fas fa-eye-slash" onclick="toggleView('newPassword', this)"></i>
                <small id="passHint" style="color: #64748b;">At least 8 chars, 1 uppercase, 1 symbol.</small>
            </div>

            <div class="input-group">
                <label>Confirm New Password</label>
                <input type="password" id="confirmPassword" required oninput="validatePass()">
                <i class="fas fa-eye-slash " onclick="toggleView('confirmPassword', this)"></i>
                <small id="matchError" style="color: #ef4444; display:none;">❌ Passwords do not match!</small>
            </div>

            <button id="btnUpdate" onclick="resetPassword()" disabled>Update Password</button>
        </div>

        <a href="/car_rental/public/login" class="back-link"> Back to Login</a>
    </div>

    <script>
        // Hàm chuyển bước (Chỉ hiện bước cần thiết)
        function showStep(stepNum) {
            document.querySelectorAll('.step-container').forEach(s => s.classList.remove('active'));
            document.getElementById('step' + stepNum).classList.add('active');
        }

        // STEP 1: Gửi OTP
        document.getElementById("formStep1").onsubmit = function(e) {
            e.preventDefault();
            Swal.showLoading();
            let identifier = document.getElementById("identifier").value;
            
            fetch("/car_rental/public/send-otp", { 
                method: "POST", 
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "identifier=" + identifier 
            })
            .then(res => res.text())
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'OTP Sent!',
                    text: data,
                    confirmButtonColor: '#2563eb'
                }).then(() => {
                    showStep(2); // Hiện bảng nhập OTP
                });
            });
        };

        // STEP 2: Xác thực OTP
        function verifyOTP() {
            let otp = document.getElementById("otp").value;
            if(otp.length < 6) {
                Swal.fire('Error', 'Please enter 6-digit code', 'warning');
                return;
            }

            fetch("/car_rental/public/verify-otp", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "otp=" + otp
            })
            .then(res => res.text())
            .then(data => {
                if(data.trim() == "OTP verified") {
                    showStep(3); // Hiện bảng đổi mật khẩu
                } else {
                    Swal.fire('Error', data, 'error');
                }
            });
        }

        // STEP 3: Logic Mật khẩu mạnh & Confirm
        function validatePass() {
            const pass = document.getElementById("newPassword").value;
            const confirm = document.getElementById("confirmPassword").value;
            const hint = document.getElementById("passHint");
            const error = document.getElementById("matchError");
            const btn = document.getElementById("btnUpdate");

            const pattern = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/;
            const isValid = pattern.test(pass);
            const isMatch = (pass === confirm && confirm !== "");

            hint.style.color = isValid ? "#10b981" : "#ef4444";
            hint.innerHTML = isValid ? "✅ Password meets requirements" : "❌ Requirement: 1 Upper, 1 Number, 1 Symbol";

            error.style.display = (pass !== confirm && confirm !== "") ? "block" : "none";

            btn.disabled = !(isValid && isMatch);
        }

        function toggleView(id, icon) {
            const input = document.getElementById(id);
            const isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            icon.classList.toggle('fa-eye-slash', isPass);
            icon.classList.toggle('fa-eye', !isPass);
        }

        function resetPassword() {
            let password = document.getElementById("newPassword").value;
            let otp = document.getElementById("otp").value;
            
            fetch("/car_rental/public/reset-password", {
                method: "POST",
                headers: {"Content-Type": "application/x-www-form-urlencoded"},
                body: "password=" + password + "&otp=" + otp
            })
            .then(res => res.text())
            .then(data => {
                Swal.fire('Success!', data, 'success').then(() => {
                    window.location = "/car_rental/public/login";
                });
            });
        }
    </script>
</body>
</html>