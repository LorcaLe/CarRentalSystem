<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings - Luxury Car Rental</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/car_rental/assets/css/style.css">
    <link rel="stylesheet" href="/car_rental/assets/css/auth/profile.css">
</head>
<body>

<?php include __DIR__ . "/../layouts/header.php"; ?>

<div class="profile-container">
    <aside class="profile-sidebar">
        <div class="user-identity">
            <div class="avatar-circle">
                <?= strtoupper(substr($_SESSION['user']['name'], 0, 1)) ?>
            </div>
            <h2><?= $_SESSION['user']['name'] ?></h2>
            <p style="color: var(--text-muted); font-size: 13px;"><?= $_SESSION['user']['email'] ?></p>
        </div>
        <nav class="nav-menu">
            <a href="#personal" class="active"><i class="fas fa-user-circle"></i> Personal Profile</a>
            <a href="#payment"><i class="fas fa-wallet"></i> Payment Methods</a>
            <a href="#security"><i class="fas fa-user-shield"></i> Password & Security</a>
        </nav>
    </aside>

    <main class="profile-main">
        
        <section id="personal" class="section-block">
            <h3 class="section-title">Personal Information</h3>
            <form id="personalDetailsForm">
                <div class="input-grid">
                    <div class="field-group">
                        <label>Display Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>">
                    </div>
                    <div class="field-group">
                        <label>Email Address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                    </div>
                    <div class="field-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
                    </div>
                </div>
                <button type="button" onclick="savePersonalChanges()" class="btn-prime">
                    <i class="fas fa-check"></i> Save Changes
                </button>
            </form>
        </section>

        <section id="payment" class="section-block">
            <h3 class="section-title">Payment Methods</h3>
            <div class="payment-methods-grid">
                <div class="method-card active" onclick="switchPaymentView('credit', this)">
                    <i class="fas fa-credit-card"></i>
                    <span>Credit Card</span>
                </div>
                <div class="method-card" onclick="switchPaymentView('momo', this)">
                    <i class="fas fa-wallet" style="color: #ae2070;"></i> 
                        <span>MoMo E-Wallet</span>
                </div>
                <div class="method-card" onclick="switchPaymentView('apple', this)">
                    <i class="fab fa-apple-pay"></i>
                    <span>Apple Pay</span>
                </div>
            </div>

            <div id="panel-credit" class="method-panel <?= (($user['payment_method'] ?? '') == 'credit' || empty($user['payment_method'] ?? '')) ? 'active' : '' ?>">
                <div class="field-group">
                    <label>Card Number</label>
                    <input type="text" id="card_number_input" value="<?= $maskedCard ?>" placeholder="XXXX XXXX XXXX 1234">
                </div>
                <div class="input-grid">
                    <div class="field-group">
                        <label>Expiry Date</label>
                        <input type="text" id="expiry_date_input" value="<?= $expiryDate ?>" placeholder="MM / YY">
                    </div>
                    <div class="field-group">
                        <label>Security Code (CVV)</label>
                        <input type="password" id="cvv_input" value="<?= $maskedCVV ?>" placeholder="***">
                    </div>
                </div>
            </div>

            <div id="panel-momo" class="method-panel <?= (($user['payment_method'] ?? '') == 'momo') ? 'active' : '' ?>">
                <div class="field-group">
                    <label>Registered MoMo Phone</label>
                    <input type="text" id="momo_phone_input" value="<?= $momoPhone ?>" placeholder="+84 XXX XXX XXX">
                </div>
            </div>

            <div id="panel-apple" class="method-panel <?= (($user['payment_method'] ?? '')== 'apple') ? 'active' : '' ?>">
                <div style="text-align: center; padding: 30px; border: 2px dashed #cbd5e1; border-radius: 20px;">
                    <i class="fab fa-apple" style="font-size: 30px; margin-bottom: 10px;"></i>
                    <p style="font-size: 14px;">Apple Pay is ready on this browser.</p>
                </div>
            </div>

            <button type="button" class="btn-prime" style="margin-top: 30px;" onclick="savePaymentMethod()">
                <i class="fas fa-link"></i> Link Payment Account
            </button>

        <section id="security" class="section-block">
            <h3 class="section-title">Security Settings</h3>
            
        <div id="step-verify-pw" class="section-block">
            <div class="field-group">
                <label>Current Password</label>
                <input type="password" id="old_pw_input" placeholder="Enter your existing password">
            </div>
            <button type="button" onclick="verifyOldPassword()" class="btn-prime">Verify & Send OTP</button>
        </div>

        <div id="step-otp" class="section-block" style="display:none;">
            <div class="field-group">
                <label>Verification Code (OTP)</label>
                <input type="text" id="security_otp" placeholder="6-digit code from your email">
            </div>
            <button type="button" onclick="verifyOTP()" class="btn-prime">Verify OTP</button>
        </div>

        <div id="step-new-pw" class="section-block" style="display:none;">
            <div class="field-group">
                <label>New Secure Password</label>
                <input type="password" id="new_pw_input" placeholder="Enter new password">
                <small class="input-hint">8+ chars, Uppercase, Lowercase, Number.</small>
            </div>
            <div class="field-group">
                <label>Confirm New Password</label>
                <input type="password" id="confirm_pw_input" placeholder="Repeat new password">
            </div>
            <button type="button" onclick="finalizeNewPassword()" class="btn-prime">Update Password</button>
        </div>
        </section>
    </div>
</div>

<script>
    // 1. Chuyển đổi Tab Thanh toán
    function switchPaymentView(method, element) {
        document.querySelectorAll('.method-card').forEach(c => c.classList.remove('active'));
        element.classList.add('active');
        
        document.querySelectorAll('.method-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('panel-' + method).classList.add('active');
    }

    // --- LOGIC ĐỔI MẬT KHẨU 3 BƯỚC ---
    let fakeOTP = ""; 

    // BƯỚC 1: Verify Password cũ qua API
    function verifyOldPassword() {
        const oldPw = document.getElementById('old_pw_input').value;
        if(!oldPw) return Swal.fire('Error', 'Please enter current password', 'warning');

        Swal.fire({ title: 'Verifying...', didOpen: () => Swal.showLoading() });

        const formData = new URLSearchParams();
        formData.append('current_password', oldPw);

        fetch('/car_rental/public/verify-current-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            // Bắt buộc phải có encodeURIComponent
            body: "current_password=" + encodeURIComponent(oldPw)
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "correct") {
                sendOTPSimulator(); // Nếu đúng pass cũ, hiện mã OTP giả
            } else {
                Swal.fire('Error', 'Current password is incorrect.', 'error');
            }
        });
    }

    // BƯỚC 2: Hiện Popup mã OTP giả (Mô phỏng gửi Email)
    function sendOTPSimulator() {
        fakeOTP = Math.floor(100000 + Math.random() * 900000).toString();

        Swal.fire({
            title: 'Simulator: OTP Sent!',
            html: `Your simulation code is: <h2 style="color: #2563eb; letter-spacing: 5px;">${fakeOTP}</h2>`,
            icon: 'info',
            confirmButtonText: 'I got it'
        }).then(() => {
            document.getElementById('step-verify-pw').style.display = 'none';
            document.getElementById('step-otp').style.display = 'block';
        });
    }

    // BƯỚC 2.5: Kiểm tra OTP người dùng nhập
    function verifyOTP() {
        const userInput = document.getElementById('security_otp').value;
        if (userInput === fakeOTP) {
            Swal.fire('Verified!', 'Now set your new password.', 'success').then(() => {
                document.getElementById('step-otp').style.display = 'none';
                document.getElementById('step-new-pw').style.display = 'block';
            });
        } else {
            Swal.fire('Error', 'Invalid OTP code.', 'error');
        }
    }

    // BƯỚC 3: Gửi mật khẩu mới lên Server để lưu
    function finalizeNewPassword() {
        const newPw = document.getElementById('new_pw_input').value;
        const confirmPw = document.getElementById('confirm_pw_input').value;

        // 1. Kiểm tra khớp mật khẩu
        if (newPw !== confirmPw) {
            return Swal.fire('Error', 'Confirm password does not match!', 'error');
        }

        // 2. Định nghĩa Regex: Ít nhất 8 ký tự, 1 chữ hoa, 1 chữ thường, 1 số
        // Đây là chuẩn chung giống như lúc Lorca làm trang Register
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

        if (!passwordRegex.test(newPw)) {
            return Swal.fire({
                icon: 'warning',
                title: 'Weak Password',
                text: 'Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, and one number.',
                confirmButtonColor: '#2563eb'
            });
        }

        // 3. Nếu vượt qua tất cả các bước check, mới gửi fetch lên Server
        Swal.fire({ title: 'Updating...', didOpen: () => Swal.showLoading() });

        fetch('/car_rental/public/update-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `password=${encodeURIComponent(newPw)}`
        })
        .then(res => res.text())
        .then(data => {
            if(data.trim() === "updated") {
                Swal.fire('Success', 'Your password has been changed!', 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', 'Something went wrong. Please try again.', 'error');
            }
        });
    }

    // --- CÁC HÀM CẬP NHẬT THÔNG TIN KHÁC ---

    function savePersonalChanges() {
        const form = document.getElementById('personalDetailsForm');
        const formData = new FormData(form);

        Swal.fire({ title: 'Updating...', didOpen: () => Swal.showLoading() });

        fetch('/car_rental/public/update-profile', { method: 'POST', body: formData })
        .then(res => res.text())
        .then(data => {
            if(data.trim() === "success") {
                Swal.fire('Updated!', 'Profile saved.', 'success').then(() => location.reload());
            }
        });
    }

    function savePaymentMethod() {
        const activePanel = document.querySelector('.method-panel.active').id;
        const payMethod = activePanel.replace('panel-', ''); 
        const formData = new URLSearchParams();
        formData.append('pay_method', payMethod);

        if (payMethod === 'credit') {
            formData.append('card_number', document.getElementById('card_number_input').value);
            formData.append('expiry_date', document.getElementById('expiry_date_input').value);
            formData.append('cvv', document.getElementById('cvv_input').value);
        } else if (payMethod === 'momo') {
            formData.append('momo_phone', document.getElementById('momo_phone_input').value);
        }

        fetch('/car_rental/public/update-payment', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(res => res.text())
        .then(result => {
            if (result.trim() === "success") {
                Swal.fire('Success!', 'Payment info updated.', 'success').then(() => location.reload());
            }
        });
    }

    // Formatting Inputs
    document.getElementById('expiry_date_input').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) e.target.value = value.slice(0, 2) + ' / ' + value.slice(2, 4);
    });

    document.querySelectorAll('#card_number_input, #cvv_input').forEach(input => {
        input.addEventListener('focus', function() {
            if (this.value.includes('*')) this.value = ''; 
        });
    });
</script>

</body>
</html>