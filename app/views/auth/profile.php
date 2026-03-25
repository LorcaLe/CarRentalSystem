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
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            --glass: rgba(255, 255, 255, 0.85);
            --sidebar-glass: rgba(255, 255, 255, 0.4);
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        * { box-sizing: border-box; font-family: 'Poppins', sans-serif; margin: 0; padding: 0; }

        body {
            padding-top: 10px; 
            background: #fff;
        }
        .topbar {
            /* Đảm bảo Header luôn nằm trên cùng và có đổ bóng nhẹ để tách biệt */
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* --- Dashboard Layout --- */
        .profile-container {
            max-width: 1100px;
            margin-top: 0;
            margin: 0 auto;
            display: flex;
            background: var(--glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            min-height: 80vh;
            align-items: stretch;
        }

        /* --- Sidebar Navigation --- */
        .profile-sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.4);
            padding: 50px 25px;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
        }

        .user-identity { text-align: center; margin-bottom: 50px; }
        .avatar-circle {
            width: 110px; height: 110px; background: var(--primary);
            color: white; font-size: 45px; font-weight: 600;
            display: flex; align-items: center; justify-content: center;
            border-radius: 50%; margin: 0 auto 15px;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.25);
        }

        .nav-menu a {
            display: flex; align-items: center; padding: 16px 22px;
            text-decoration: none; color: var(--text-muted);
            border-radius: 14px; margin-bottom: 12px; transition: 0.4s;
            font-weight: 500;
        }
        .nav-menu a i { margin-right: 15px; font-size: 18px; }
        .nav-menu a:hover, .nav-menu a.active {
            background: var(--primary); color: white; transform: translateX(8px);
        }

        /* --- Main Content Area --- */
        .profile-main { flex: 1; padding: 60px; overflow-y: auto; }
        .section-block { margin-bottom: 70px; animation: slideUp 0.6s ease-out; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .section-title { 
            font-size: 22px; 
            border-bottom: 3px solid var(--primary); 
            display: inline-block; padding-bottom: 6px; 
            margin-top: 10px;
            margin-bottom: 30px;
        }

        /* --- Form Elements --- */
        .input-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        .field-group { margin-bottom: 25px; }
        .field-group label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 10px; }
        .field-group input {
            width: 100%; padding: 14px 18px; border-radius: 14px;
            border: 1px solid #cbd5e1; outline: none; transition: 0.3s;
            background: rgba(255, 255, 255, 0.6);
        }
        .field-group input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12); background: white; }
        .input-hint { font-size: 11px; color: var(--text-muted); margin-top: 6px; display: block; }

        /* --- Payment Selector --- */
        .payment-methods-grid { display: flex; gap: 20px; margin-bottom: 35px; }
        .method-card {
            flex: 1; display: flex; flex-direction: column; align-items: center;
            padding: 24px; background: white; border: 2px solid #e2e8f0;
            border-radius: 20px; cursor: pointer; transition: 0.3s;
        }
        .method-card i { font-size: 28px; margin-bottom: 10px; color: var(--text-muted); }
        .method-card img { width: 35px; margin-bottom: 10px; border-radius: 6px; }
        .method-card span { font-size: 13px; font-weight: 600; }
        .method-card.active { border-color: var(--primary); background: rgba(37, 99, 235, 0.05); }
        .method-card.active i, .method-card.active span { color: var(--primary); }
        
        .method-panel { display: none; }
        .method-panel.active { display: block; animation: fadeIn 0.4s ease; }

        /* --- Buttons --- */
        .btn-prime {
            padding: 14px 30px; background: var(--primary); color: white;
            border: none; border-radius: 12px; font-weight: 600;
            cursor: pointer; transition: 0.3s; display: inline-flex; align-items: center; gap: 10px;
        }
        .btn-prime:hover { background: var(--primary-hover); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2); }

        .section-block {
            transition: all 0.4s ease;
            animation: fadeInStep 0.5s ease-in-out;
        }

        @keyframes fadeInStep {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
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