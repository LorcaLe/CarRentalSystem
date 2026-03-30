<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password — Partner</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/car_rental/assets/css/authPartner/forgotPassword.css">
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="card">
        <div class="logo">PrivateHire<span>Cars</span> <span style="color:var(--muted);font-size:0.8rem;font-weight:400;">Partner</span></div>

        <!-- Progress -->
        <div class="progress">
            <div class="prog-step">
                <div class="prog-dot active" id="dot1">1</div>
                <span class="prog-label active" id="lbl1">Email</span>
            </div>
            <div class="prog-line"></div>
            <div class="prog-step">
                <div class="prog-dot" id="dot2">2</div>
                <span class="prog-label" id="lbl2">Verify</span>
            </div>
            <div class="prog-line"></div>
            <div class="prog-step">
                <div class="prog-dot" id="dot3">3</div>
                <span class="prog-label" id="lbl3">Reset</span>
            </div>
        </div>

        <!-- Step 1 -->
        <div class="step-body active" id="step1">
            <div class="step-icon blue"><i class="fas fa-envelope"></i></div>
            <h2>Forgot password?</h2>
            <p class="sub">Enter your registered email or phone and we'll send you a verification code.</p>
            <form id="form1">
                <div class="field">
                    <label>Email or Phone</label>
                    <div class="input-wrap">
                        <input type="text" id="identifier" required placeholder="partner@example.com">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
                <button type="submit" class="btn">Send Verification Code</button>
            </form>
        </div>

        <!-- Step 2 -->
        <div class="step-body" id="step2">
            <div class="step-icon gold"><i class="fas fa-shield-alt"></i></div>
            <h2>Enter OTP</h2>
            <p class="sub">A 6-digit code was sent to your account. It expires in 10 minutes.</p>
            <div class="field">
                <label>Verification Code</label>
                <div class="input-wrap">
                    <input type="text" id="otp" maxlength="6" placeholder="— — — — — —" style="text-align:center;">
                </div>
            </div>
            <button class="btn" onclick="verifyOTP()">Verify Code</button>
        </div>

        <!-- Step 3 -->
        <div class="step-body" id="step3">
            <div class="step-icon green"><i class="fas fa-lock"></i></div>
            <h2>New Password</h2>
            <p class="sub">Set a strong new password for your partner account.</p>
            <div class="field">
                <label>New Password</label>
                <div class="input-wrap">
                    <input type="password" id="newPwd" required oninput="validatePass()" placeholder="••••••••">
                    <i class="fas fa-eye-slash" onclick="toggle('newPwd', this)"></i>
                </div>
                <div class="hint" id="pwdHint" style="color:var(--muted);">Min 8 chars, 1 uppercase, 1 number, 1 symbol.</div>
            </div>
            <div class="field">
                <label>Confirm Password</label>
                <div class="input-wrap">
                    <input type="password" id="cfmPwd" required oninput="validatePass()" placeholder="••••••••">
                    <i class="fas fa-eye-slash" onclick="toggle('cfmPwd', this)"></i>
                </div>
                <div class="hint" id="matchHint"></div>
            </div>
            <button class="btn" id="btnReset" onclick="resetPassword()" disabled>Update Password</button>
        </div>

        <a href="/car_rental/public/partner/login" class="back">
            <i class="fas fa-arrow-left"></i> Back to Sign In
        </a>
    </div>

    <script>
        function showStep(n) {
            document.querySelectorAll('.step-body').forEach(s => s.classList.remove('active'));
            document.getElementById('step' + n).classList.add('active');
            // Update progress dots
            for (let i = 1; i <= 3; i++) {
                const dot = document.getElementById('dot' + i);
                const lbl = document.getElementById('lbl' + i);
                dot.className = 'prog-dot' + (i < n ? ' done' : i === n ? ' active' : '');
                dot.textContent = i < n ? '✓' : i;
                lbl.className   = 'prog-label' + (i === n ? ' active' : '');
            }
        }

        document.getElementById('form1').onsubmit = function(e) {
            e.preventDefault();
            fetch("/car_rental/public/send-otp", {
                method:"POST", headers:{"Content-Type":"application/x-www-form-urlencoded"},
                body:"identifier=" + document.getElementById('identifier').value
            })
            .then(r => r.text())
            .then(data => {
                Swal.fire({ icon:'success', title:'Code Sent', text:data, background:'#141b2d', color:'#fff', confirmButtonColor:'#3b6ef8' })
                .then(() => showStep(2));
            });
        };

        function verifyOTP() {
            const otp = document.getElementById('otp').value;
            if (otp.length < 6) { Swal.fire({icon:'warning', title:'Incomplete', text:'Enter the full 6-digit code.', background:'#141b2d', color:'#fff', confirmButtonColor:'#3b6ef8'}); return; }
            fetch("/car_rental/public/verify-otp", {
                method:"POST", headers:{"Content-Type":"application/x-www-form-urlencoded"},
                body:"otp=" + otp
            })
            .then(r => r.text())
            .then(data => {
                if (data.trim() === 'OTP verified') showStep(3);
                else Swal.fire({icon:'error', title:'Invalid Code', text:data, background:'#141b2d', color:'#fff', confirmButtonColor:'#3b6ef8'});
            });
        }

        function validatePass() {
            const pwd = document.getElementById('newPwd').value;
            const cfm = document.getElementById('cfmPwd').value;
            const pattern = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/;
            const ok = pattern.test(pwd);
            const match = pwd === cfm && cfm !== '';

            document.getElementById('pwdHint').style.color   = ok ? '#22d3a5' : '#8892a4';
            document.getElementById('pwdHint').textContent   = ok ? '✓ Strong password' : 'Min 8 chars, 1 uppercase, 1 number, 1 symbol.';
            document.getElementById('matchHint').style.color = match ? '#22d3a5' : '#f87171';
            document.getElementById('matchHint').textContent = cfm ? (match ? '✓ Passwords match' : '✗ Does not match') : '';
            document.getElementById('btnReset').disabled = !(ok && match);
        }

        function toggle(id, icon) {
            const el = document.getElementById(id);
            const isPass = el.type === 'password';
            el.type = isPass ? 'text' : 'password';
            icon.classList.toggle('fa-eye-slash', !isPass);
            icon.classList.toggle('fa-eye', isPass);
        }

        function resetPassword() {
            fetch("/car_rental/public/reset-password", {
                method:"POST", headers:{"Content-Type":"application/x-www-form-urlencoded"},
                body:"password=" + document.getElementById('newPwd').value + "&otp=" + document.getElementById('otp').value
            })
            .then(r => r.text())
            .then(data => {
                Swal.fire({icon:'success', title:'Password Updated!', text:data, background:'#141b2d', color:'#fff', confirmButtonColor:'#3b6ef8'})
                .then(() => window.location.href = '/car_rental/public/partner/login');
            });
        }
    </script>
</body>
</html>