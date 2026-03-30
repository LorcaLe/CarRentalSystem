<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - PrivateHire Cars</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/car_rental/assets/css/auth/forgotPassword.css">
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