<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// BƯỚC 1: Lấy ID từ session
$userId = $_SESSION['user']['id'] ?? null;

if (!$userId) {

    header("Location: /car_rental/public/login");
    exit;
}


$host = '127.0.0.1';
$db   = 'car_rental';
$user = 'root'; 
$pass = '';     
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$currentUser) {
    // Nếu không tìm thấy user, có thể session bị sai, cho logout
    header("Location: /car_rental/public/logout");
    exit;
}

// Cập nhật lại một số thông tin cơ bản vào Session phòng trường hợp dùng ở Header/Sidebar
$_SESSION['user']['name'] = $currentUser['name'];
$_SESSION['user']['email'] = $currentUser['email'];

// --- SAU ĐÓ SỬ DỤNG BIẾN $currentUser THAY VÌ $_SESSION['user'] ---

$rawPhone    = $currentUser["phone"] ?? "";
$maskedPhone = $rawPhone 
    ? str_repeat("●", max(0, strlen($rawPhone) - 4)) . substr($rawPhone, -4) 
    : "";

$payMethod  = $currentUser["payment_method"] ?? "";

$rawCard    = $currentUser["credit_card_number"] ?? "";
$maskedCard = $rawCard 
    ? "●●●● ●●●● ●●●● " . substr(preg_replace("/\D/", "", $rawCard), -4) 
    : "";

$expiryDate = $currentUser["credit_expiry_date"] ?? "";
$maskedCVV  = (!empty($currentUser["credit_cvv"])) ? "•••" : "";

$rawMomo   = $currentUser["momo_phone"] ?? "";
$momoPhone = $rawMomo 
    ? str_repeat("●", max(0, strlen($rawMomo) - 4)) . substr($rawMomo, -4) 
    : "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile — Partner</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&family=DM+Mono&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/car_rental/assets/css/partner.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/car_rental/assets/css/profilePartner.css">
</head>
<body>

    <!-- ════════════════════════════════
         PARTNER SIDEBAR (unchanged)
    ════════════════════════════════ -->
    <aside class="sidebar">
        <div class="sidebar-logo">PrivateHire Cars <span>Partner</span></div>
        <nav class="sidebar-nav">
            <div class="nav-label">Overview</div>
            <a href="/car_rental/public/partner/dashboard" class="nav-item"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <div class="nav-label">Fleet</div>
            <a href="/car_rental/public/partner/my-cars" class="nav-item"><i class="fas fa-car"></i> My Cars</a>
            <a href="/car_rental/public/partner/register-car" class="nav-item"><i class="fas fa-plus-circle"></i> Add New Car</a>
            <div class="nav-label">Account</div>
            <a href="/car_rental/public/partner/profile" class="nav-item active"><i class="fas fa-user"></i> My Profile</a>
        </nav>
        <div class="sidebar-footer">
            Signed in as <strong><?= htmlspecialchars($currentUser['name']) ?></strong><br>
            <a href="/car_rental/public/logout">Logout</a>
        </div>
    </aside>

    <!-- ════════════════════════════════
         MAIN CONTENT
    ════════════════════════════════ -->
    <main class="main">

        <!-- Profile hero -->
        <div class="profile-hero">
            <div class="hero-avatar"><?= strtoupper($currentUser['name'][0]) ?></div>
            <div class="hero-info">
                <h1><?= htmlspecialchars($currentUser['name']) ?></h1>
                <p><?= htmlspecialchars($currentUser['email'] ?? $currentUser['phone'] ?? '') ?></p>
            </div>
            <div class="partner-badge-hero">🤝 Partner</div>
        </div>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> Profile updated successfully.</div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error_msg'] ?></div>
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>

        <!-- ── Personal Information ── -->
        <div class="card">
            <div class="section-label"><i class="fas fa-user-edit"></i> Personal Information</div>
            <form id="personalDetailsForm">
                <div class="form-grid">
                    <div class="field">
                        <label>Full Name</label>
                        <input type="text" name="name"
                               value="<?= htmlspecialchars($currentUser['name']) ?>" required>
                    </div>
                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email"
                               value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Phone Number</label>
                        <!-- CHỈ NHẬN SỐ -->
                        <input type="text" name="phone" id="phoneInput"
                               value="<?= htmlspecialchars($maskedPhone) ?>"
                               placeholder="e.g. 0901234567"
                               inputmode="numeric" maxlength="15"
                               oninput="validatePhone(this)">
                        <span class="phone-hint" id="phoneHint"></span>
                    </div>
                    <div class="field">
                        <label>Role</label>
                        <input type="text" value="Partner" readonly>
                    </div>
                </div>
                <button type="button" onclick="savePersonalChanges()" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </form>
        </div>

        <!-- ── Payment Methods ── -->
        <div class="card">
            <div class="section-label"><i class="fas fa-wallet"></i> Payment Methods</div>

            <div class="payment-methods-grid">
                <div class="method-card <?= ($payMethod === 'credit' || empty($payMethod)) ? 'active' : '' ?>" onclick="switchPaymentView('credit', this)">
                    <i class="fas fa-credit-card"></i>
                    <span>Credit Card</span>
                </div>
                <div class="method-card <?= ($payMethod === 'momo') ? 'active' : '' ?>" onclick="switchPaymentView('momo', this)">
                    <img src="https://seeklogo.com/images/M/momo-logo-ED8A3A0DF2-seeklogo.com.png" alt="MoMo">
                    <span>MoMo E-Wallet</span>
                </div>
                <div class="method-card <?= ($payMethod === 'apple') ? 'active' : '' ?>" onclick="switchPaymentView('apple', this)">
                    <i class="fab fa-apple-pay" style="font-size:32px;"></i>
                    <span>Apple Pay</span>
                </div>
            </div>

            <!-- Credit Card -->
            <div id="panel-credit" class="method-panel <?= ($payMethod === 'credit' || empty($payMethod)) ? 'active' : '' ?>">
                <div class="form-grid">
                    <div class="field" style="grid-column:1/-1;">
                        <label>Card Number</label>
                        <input type="text" id="card_number_input"
                               value="<?= $maskedCard ?? '' ?>"
                               placeholder="XXXX XXXX XXXX 1234"
                               maxlength="19" inputmode="numeric"
                               oninput="formatCardNumber(this)">
                    </div>
                    <div class="field">
                        <label>Expiry Date</label>
                        <input type="text" id="expiry_date_input"
                               value="<?= $expiryDate ?? '' ?>"
                               placeholder="MM / YY" maxlength="7">
                    </div>
                    <div class="field">
                        <label>CVV</label>
                        <input type="password" id="cvv_input"
                               value="<?= $maskedCVV ?? '' ?>"
                               placeholder="•••" maxlength="4" inputmode="numeric"
                               oninput="this.value=this.value.replace(/\D/g,'')">
                    </div>
                </div>
            </div>

            <!-- MoMo -->
            <div id="panel-momo" class="method-panel <?= ($payMethod === 'momo') ? 'active' : '' ?>">
                <div class="form-grid">
                    <div class="field">
                        <label>Registered MoMo Phone</label>
                        <input type="text" id="momo_phone_input"
                               value="<?= $momoPhone ?? '' ?>"
                               placeholder="+84 XXX XXX XXX"
                               inputmode="numeric"
                               oninput="this.value=this.value.replace(/[^\d\s+]/g,'')">
                    </div>
                </div>
            </div>

            <!-- Apple Pay -->
            <div id="panel-apple" class="method-panel <?= ($payMethod === 'apple') ? 'active' : '' ?>">
                <div style="text-align:center;padding:28px;border:2px dashed var(--border,#e2e8f0);border-radius:14px;">
                    <i class="fab fa-apple" style="font-size:36px;margin-bottom:10px;display:block;"></i>
                    <p style="font-size:14px;color:var(--muted);">Apple Pay is ready on this device.</p>
                </div>
            </div>

            <div style="margin-top:20px;">
                <button type="button" class="btn btn-primary" onclick="savePaymentMethod()">
                    <i class="fas fa-link"></i> Link Payment Account
                </button>
            </div>
        </div>

        <!-- ── Change Password (3 steps) ── -->
        <div class="card">
            <div class="section-label"><i class="fas fa-lock"></i> Change Password</div>

            <!-- STEP 1: Current password -->
            <div id="step-verify-pw" class="step-block">
                <div class="form-grid">
                    <div class="field span-2">
                        <label>Current Password</label>
                        <div class="pw-wrap">
                            <input type="password" id="old_pw_input" placeholder="Enter your current password">
                            <span class="pw-eye" onclick="toggleVis('old_pw_input',this)"><i class="fas fa-eye-slash"></i></span>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="verifyOldPassword()" class="btn btn-primary">
                    <i class="fas fa-shield-alt"></i> Verify & Send OTP
                </button>
            </div>

            <!-- STEP 2: OTP -->
            <div id="step-otp" class="step-block" style="display:none;">
                <div class="form-grid">
                    <div class="field span-2">
                        <label>Verification Code (OTP)</label>
                        <input type="text" id="security_otp"
                               placeholder="Enter 6-digit code"
                               maxlength="6" inputmode="numeric"
                               oninput="this.value=this.value.replace(/\D/g,'')">
                        <span style="font-size:12px;color:var(--muted);margin-top:6px;display:block;">
                            Check the OTP shown in the popup (simulation).
                        </span>
                    </div>
                </div>
                <button type="button" onclick="verifyOTP()" class="btn btn-primary">
                    <i class="fas fa-check-double"></i> Verify OTP
                </button>
            </div>

            <!-- STEP 3: New password -->
            <div id="step-new-pw" class="step-block" style="display:none;">
                <div class="form-grid">
                    <div class="field">
                        <label>New Password</label>
                        <div class="pw-wrap">
                            <input type="password" id="new_pw_input"
                                   placeholder="Min 8 chars" oninput="checkStrength()">
                            <span class="pw-eye" onclick="toggleVis('new_pw_input',this)"><i class="fas fa-eye-slash"></i></span>
                        </div>
                        <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                        <div class="check-list">
                            <div class="check-item" id="p_len">  <i class="fas fa-circle"></i> 8+ characters</div>
                            <div class="check-item" id="p_upper"><i class="fas fa-circle"></i> Uppercase</div>
                            <div class="check-item" id="p_lower"><i class="fas fa-circle"></i> Lowercase</div>
                            <div class="check-item" id="p_num">  <i class="fas fa-circle"></i> Number</div>
                        </div>
                    </div>
                    <div class="field">
                        <label>Confirm New Password</label>
                        <div class="pw-wrap">
                            <input type="password" id="confirm_pw_input"
                                   placeholder="Repeat new password" oninput="checkMatch()">
                            <span class="pw-eye" onclick="toggleVis('confirm_pw_input',this)"><i class="fas fa-eye-slash"></i></span>
                        </div>
                        <span style="font-size:12px;margin-top:6px;display:block;" id="matchHint"></span>
                    </div>
                </div>
                <button type="button" onclick="finalizeNewPassword()" class="btn btn-primary">
                    <i class="fas fa-key"></i> Update Password
                </button>
            </div>
        </div>

    </main>

    <script>
        /* ── Phone: digits only ── */
        function validatePhone(input) {
            input.value = input.value.replace(/\D/g, '');
            const hint = document.getElementById('phoneHint');
            const len  = input.value.length;
            if (len > 0 && (len < 9 || len > 15)) {
                hint.style.color = '#f87171'; hint.textContent = '✗ Must be 9–15 digits';
            } else if (len >= 9) {
                hint.style.color = '#22d3a5'; hint.textContent = '✓ Looks good';
            } else {
                hint.textContent = '';
            }
        }

        /* ── Toggle password visibility ── */
        function toggleVis(id, btn) {
            const input = document.getElementById(id);
            const icon  = btn.querySelector('i');
            input.type = (input.type === 'password') ? 'text' : 'password';
            icon.className = (input.type === 'text') ? 'fas fa-eye-slash' : 'fas fa-eye';
        }

        /* ── Payment tab switch ── */
        // 1. Chuyển đổi Tab Thanh toán
        function switchPaymentView(method, element) {
            document.querySelectorAll('.method-card').forEach(c => c.classList.remove('active'));
            element.classList.add('active');
            document.querySelectorAll('.method-panel').forEach(p => p.classList.remove('active'));
            document.getElementById('panel-' + method).classList.add('active');
        }

        // Formatting Inputs
        document.getElementById('expiry_date_input').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 2) e.target.value = value.slice(0, 2) + ' / ' + value.slice(2, 4);
        });

        document.querySelectorAll('#card_number_input, #cvv_input').forEach(input => {
            input.addEventListener('focus', function() {
                if (this.value.includes('●') || this.value.includes('•')) this.value = '';
            });
        });

        /* ── Password strength ── */
        function checkStrength() {
            const pwd = document.getElementById('new_pw_input').value;
            const checks = {
                p_len:   pwd.length >= 8,
                p_upper: /[A-Z]/.test(pwd),
                p_lower: /[a-z]/.test(pwd),
                p_num:   /[0-9]/.test(pwd),
            };
            let score = 0;
            Object.entries(checks).forEach(([id, ok]) => {
                document.getElementById(id).classList.toggle('ok', ok);
                if (ok) score++;
            });
            const colors = ['#f87171','#fb923c','#facc15','#22d3a5'];
            const fill = document.getElementById('strengthFill');
            fill.style.width      = (score * 25) + '%';
            fill.style.background = colors[score-1] || '#e2e8f0';
            checkMatch();
        }

        function checkMatch() {
            const pwd  = document.getElementById('new_pw_input').value;
            const cfm  = document.getElementById('confirm_pw_input').value;
            const hint = document.getElementById('matchHint');
            if (!cfm) { hint.textContent = ''; return; }
            hint.style.color  = (pwd === cfm) ? '#22d3a5' : '#f87171';
            hint.textContent  = (pwd === cfm) ? '✓ Passwords match' : '✗ Does not match';
        }

        /* ══════════════════════════════════
           3-STEP PASSWORD CHANGE
        ══════════════════════════════════ */
        let fakeOTP = "";

        // BƯỚC 1: Verify Password cũ qua API
        function verifyOldPassword() {
            const oldPw = document.getElementById('old_pw_input').value;
            if (!oldPw) return Swal.fire('Error', 'Please enter current password', 'warning');

            Swal.fire({ title: 'Verifying...', didOpen: () => Swal.showLoading() });

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
            const newPw     = document.getElementById('new_pw_input').value;
            const confirmPw = document.getElementById('confirm_pw_input').value;

            // 1. Kiểm tra khớp mật khẩu
            if (newPw !== confirmPw) {
                return Swal.fire('Error', 'Confirm password does not match!', 'error');
            }

            // 2. Định nghĩa Regex: Ít nhất 8 ký tự, 1 chữ hoa, 1 chữ thường, 1 số
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
                if (data.trim() === "updated") {
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
                if (data.trim() === "success") {
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
    </script>
</body>
</html>