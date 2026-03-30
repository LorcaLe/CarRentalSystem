<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Partner Login — PrivateHire Cars</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/car_rental/assets/css/authPartner/login.css">
</head>
<body>

    <!-- Left -->
    <div class="left">
        <div class="left-logo">PrivateHire<span>Cars</span></div>

        <div class="left-hero">
            <div class="partner-pill">
                <i class="fas fa-handshake"></i> Partner Program
            </div>
            <h1>Earn more.<br>Drive <em>your fleet</em><br>further.</h1>
            <p>List your vehicles on the largest car rental network in Vietnam. Full control. Real-time bookings. Zero hassle.</p>
        </div>

        <div class="left-stats">
            <div class="stat">
                <div class="stat-num">2.4k+</div>
                <div class="stat-label">Active partners</div>
            </div>
            <div class="stat">
                <div class="stat-num">98%</div>
                <div class="stat-label">Satisfaction rate</div>
            </div>
            <div class="stat">
                <div class="stat-num">NONE </div>
                <div class="stat-label">Listing fee</div>
            </div>
        </div>
    </div>

    <!-- Right -->
    <div class="right">
        <div class="form-wrap">
            <h2>Partner Sign In</h2>
            <p class="sub">Access your fleet dashboard</p>

            <form method="POST" action="/car_rental/public/login">
                <input type="hidden" name="redirect_to" value="/car_rental/public/partner/dashboard">
                <input type="hidden" name="portal" value="partner">
                <div class="field">
                    <label>Email or Phone</label>
                    <div class="input-wrap">
                        <input type="text" name="identifier" required placeholder="partner@example.com">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="field">
                    <label>Password</label>
                    <div class="input-wrap">
                        <input type="password" name="password" id="pwd" required placeholder="••••••••">
                        <i class="fas fa-eye-slash" id="togglePwd" onclick="togglePwd()"></i>
                    </div>
                </div>

                <a href="/car_rental/public/partner/forgot-password" class="forgot">Forgot password?</a>

                <button type="submit" class="btn-login">Sign In to Dashboard</button>
            </form>

            <div class="divider">New partner?</div>

            <a href="/car_rental/public/partner/register" class="btn-register">
                Create a partner account
            </a>
        </div>
    </div>

    <script>
        function togglePwd() {
            const input = document.getElementById('pwd');
            const icon  = document.getElementById('togglePwd');
            const isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            icon.classList.toggle('fa-eye-slash', !isPass);
            icon.classList.toggle('fa-eye', isPass);
        }

        <?php if (isset($_SESSION['error_msg'])): ?>
            Swal.fire({ icon: 'error', title: 'Sign In Failed', text: '<?= $_SESSION['error_msg'] ?>', background: '#141b2d', color: '#fff', confirmButtonColor: '#3b6ef8' });
            <?php unset($_SESSION['error_msg']); ?>
        <?php endif; ?>
    </script>
</body>
</html>