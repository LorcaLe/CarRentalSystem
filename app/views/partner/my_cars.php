<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cars — Partner</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="/car_rental/assets/css/my_car.css">
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-logo">PrivateHire Cars  <span >Partner</span> </div>
        <nav class="sidebar-nav">
            <div class="nav-label">Overview</div>
            <a href="/car_rental/public/partner/dashboard" class="nav-item"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <div class="nav-label" style="margin-top:16px;">Fleet</div>
            <a href="/car_rental/public/partner/my-cars" class="nav-item active"><i class="fas fa-car"></i> My Cars</a>
            <a href="/car_rental/public/partner/register-car" class="nav-item"><i class="fas fa-plus-circle"></i> Add New Car</a>
            <div class="nav-label" style="margin-top:16px;">Account</div>
            <a href="/car_rental/public/partner/profile" class="nav-item"><i class="fas fa-user"></i> My Profile</a>
        </nav>
        <div class="sidebar-footer">
            Signed in as <strong style="color:#fff"><?= htmlspecialchars($_SESSION['user']['name']) ?></strong><br>
            <a href="/car_rental/public/logout">Logout</a>
        </div>
    </aside>

    <main class="main">
        <div class="topbar">
            <h1>My Cars <span style="color:var(--muted);font-weight:400;font-size:1rem;">(<?= count($myCars) ?>)</span></h1>
            <a href="/car_rental/public/partner/register-car" class="btn-primary">
                <i class="fas fa-plus"></i> Add New Car
            </a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success">
                <?= $_GET['msg'] === 'submitted' ? '✅ Car submitted for review successfully!' : '🗑️ Car removed.' ?>
            </div>
        <?php endif; ?>

        <?php if (empty($myCars)): ?>
            <div class="empty-state">
                <i class="fas fa-car-side"></i>
                <p>You have no cars registered yet.</p>
                <a href="/car_rental/public/partner/register-car" class="btn-primary">Register your first car</a>
            </div>
        <?php else: ?>
            <div class="car-grid">
                <?php foreach ($myCars as $car):
                    $status = strtolower($car['status']);
                    $cls    = $status === 'approved' ? 'badge-approved' : ($status === 'pending' ? 'badge-pending' : 'badge-rejected');
                ?>
                    <div class="car-card">
                        <img src="/car_rental/images/<?= htmlspecialchars($car['image']) ?>">
                        <div class="car-card-body">
                            <div class="car-card-name"><?= htmlspecialchars($car['name']) ?></div>
                            <div class="car-card-meta">
                                <span class="meta-tag"><i class="fas fa-users"></i> <?= $car['seats'] ?> seats</span>
                                <span class="meta-tag"><?= htmlspecialchars($car['transmission']) ?></span>
                                <span class="meta-tag"><?= htmlspecialchars($car['location']) ?></span>
                            </div>
                            <div class="car-card-footer">
                                <span class="car-price"><?= number_format($car['price_per_day']) ?> VND/day</span>
                                <span class="badge <?= $cls ?>"><?= $car['status'] ?></span>
                            </div>
                            <div style="margin-top:12px;">
                                <form method="POST" action="/car_rental/public/partner/delete-car" onsubmit="return confirmDelete(event)">
                                    <input type="hidden" name="id" value="<?= $car['id'] ?>">
                                    <button type="submit" class="btn-danger w-100" style="width:100%;">
                                        <i class="fas fa-trash-alt"></i> Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function confirmDelete(e) {
            e.preventDefault();
            const form = e.target;
            Swal.fire({
                title: 'Remove this car?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                confirmButtonText: 'Yes, remove it'
            }).then(res => { if (res.isConfirmed) form.submit(); });
            return false;
        }
    </script>
</body>
</html>