<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Partner Register — PrivateHire Cars</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/car_rental/assets/css/authPartner/register.css">
</head>
<body>

    <div class="left">
        <div class="left-logo">PrivateHire<span>Cars</span></div>

        <div class="left-hero">
            <div class="partner-pill"><i class="fas fa-handshake"></i> Partner Program</div>
            <h1>List your cars.<br><em>Grow your</em><br>business.</h1>
            <p>Join thousands of partners earning passive income by listing their vehicles on our platform.</p>
        </div>

        <div class="steps">
            <div class="step">
                <div class="step-num">1</div>
                <div class="step-text"><strong>Create your account</strong>Register in under 2 minutes.</div>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <div class="step-text"><strong>List your vehicles</strong>Upload photos, set price, done.</div>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <div class="step-text"><strong>Start earning</strong>Customers book — you get paid.</div>
            </div>
        </div>
    </div>

    <div class="right">
        <div class="form-wrap">
            <h2>Create Account</h2>
            <p class="sub">Join the PrivateHire Cars partner network</p>

            <form id="regForm" method="POST" action="/car_rental/public/register">
                <input type="hidden" name="role" value="partner">

                <div class="field">
                    <label>Full Name</label>
                    <div class="input-wrap">
                        <input type="text" name="name" required placeholder="Your full name">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="field">
                    <label>Email or Phone</label>
                    <div class="input-wrap">
                        <input type="text" name="identity" required placeholder="partner@example.com">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="field">
                    <label>Password</label>
                    <div class="input-wrap">
                        <input type="password" name="password" id="pwd" required oninput="checkMatch()" placeholder="Min 8 chars">
                        <i class="fas fa-eye-slash" onclick="toggle('pwd', this)"></i>
                    </div>
                    <div id="pwdChecklist" style="margin-top:8px; display:flex; flex-direction:column; gap:4px;">
                    <div class="check-item" id="c_len">  <i class="fas fa-circle"></i> At least 8 characters</div>
                    <div class="check-item" id="c_upper"><i class="fas fa-circle"></i> 1 uppercase letter (A–Z)</div>
                    <div class="check-item" id="c_num">  <i class="fas fa-circle"></i> 1 number (0–9)</div>
                    <div class="check-item" id="c_sym">  <i class="fas fa-circle"></i> 1 symbol (!@#$%^&*)</div>
                </div>
                <div class="hint" id="matchHint" style="margin-top:6px;"></div>
                </div>

                <div class="field">
                    <label>Confirm Password</label>
                    <div class="input-wrap">
                        <input type="password" name="confirm_password" id="cpwd" required oninput="checkMatch()" placeholder="Repeat password">
                        <i class="fas fa-eye-slash" onclick="toggle('cpwd', this)"></i>
                    </div>
                    <div class="hint" id="matchHint"></div>
                </div>

                <button type="submit" class="btn-register">Create Partner Account</button>
            </form>

            <p class="login-link">Already have an account? <a href="/car_rental/public/partner/login">Sign in</a></p>
        </div>
    </div>

    <script>
        function toggle(id, icon) {
            const el = document.getElementById(id);
            const isPass = el.type === 'password';
            el.type = isPass ? 'text' : 'password';
            icon.classList.toggle('fa-eye-slash', !isPass);
            icon.classList.toggle('fa-eye', isPass);
        }

        function checkMatch() {
            const pwd  = document.getElementById('pwd').value;
            const cpwd = document.getElementById('cpwd').value;
            const hint = document.getElementById('pwdHint');
            const mhint = document.getElementById('matchHint');
            const pattern = /^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/;

            hint.style.color  = pattern.test(pwd) ? '#22d3a5' : '#8892a4';
            hint.textContent  = pattern.test(pwd) ? '✓ Strong password' : 'At least 8 chars, 1 uppercase, 1 number, 1 symbol.';

            if (cpwd) {
                mhint.style.color = pwd === cpwd ? '#22d3a5' : '#f87171';
                mhint.textContent = pwd === cpwd ? '✓ Passwords match' : '✗ Passwords do not match';
            }
        }

        document.getElementById('regForm').addEventListener('submit', function(e) {
            const pwd  = document.getElementById('pwd').value;
            const cpwd = document.getElementById('cpwd').value;
            if (pwd !== cpwd) {
                e.preventDefault();
                Swal.fire({ icon:'error', title:'Mismatch', text:'Passwords do not match!', background:'#141b2d', color:'#fff', confirmButtonColor:'#3b6ef8' });
            }
        });

        <?php if (isset($_SESSION['error_msg'])): ?>
            Swal.fire({ icon:'error', title:'Registration Failed', text:'<?= $_SESSION['error_msg'] ?>', background:'#141b2d', color:'#fff', confirmButtonColor:'#3b6ef8' });
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_msg'])): ?>
            Swal.fire({ icon:'success', title:'Account Created!', text:'<?= $_SESSION['success_msg'] ?>', background:'#141b2d', color:'#fff', confirmButtonColor:'#3b6ef8' })
            .then(() => window.location.href = '/car_rental/public/partner/login');
            <?php unset($_SESSION['success_msg']); ?>
        <?php endif; ?>

        function checkMatch() {
        const pwd  = document.getElementById('pwd').value;
        const cpwd = document.getElementById('cpwd').value;

        const checks = {
            c_len:   pwd.length >= 8,
            c_upper: /[A-Z]/.test(pwd),
            c_num:   /[0-9]/.test(pwd),
            c_sym:   /[!@#$%^&*]/.test(pwd),
        };
        Object.entries(checks).forEach(([id, ok]) => {
            document.getElementById(id).classList.toggle('ok', ok);
        });

        const mhint = document.getElementById('matchHint');
        if (cpwd) {
            mhint.style.color   = pwd === cpwd ? '#22d3a5' : '#f87171';
            mhint.textContent   = pwd === cpwd ? '✓ Passwords match' : '✗ Does not match';
        } else {
            mhint.textContent = '';
        }
    }
    </script>
</body>
</html>