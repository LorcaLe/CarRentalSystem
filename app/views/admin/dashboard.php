<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CarRental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root { --sidebar-bg: #0f172a; --main-bg: #f8fafc; --accent: #3b82f6; }
        body { background-color: var(--main-bg); font-family: 'Inter', system-ui, sans-serif; }
        
        /* Sidebar */
        .sidebar { width: 260px; height: 100vh; position: fixed; background: var(--sidebar-bg); color: #fff; z-index: 1000; }
        .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; }
        .nav-link { color: #94a3b8; padding: 14px 25px; transition: 0.2s; border-left: 4px solid transparent; }
        .nav-link:hover, .nav-link.active { background: rgba(59, 130, 246, 0.1); color: #fff; border-left-color: var(--accent); }
        .nav-link.active { color: var(--accent); }

        /* Cards */
        .stat-card { border: none; border-radius: 16px; transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
        .card-table { border: none; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
        
        /* Badges Custom */
        .badge-soft-success { background: #dcfce7; color: #15803d; }
        .badge-soft-warning { background: #fef9c3; color: #a16207; }
        .badge-soft-danger { background: #fee2e2; color: #b91c1c; }

        /* Sidebar Nav Links */
        .nav-link {
            color: #94a3b8 !important; /* Màu xám mặc định */
            padding: 14px 25px;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            background: rgba(59, 130, 246, 0.05);
            color: #fff !important;
        }

        /* Hiệu ứng khi Active */
        .nav-link.active {
            background: rgba(59, 130, 246, 0.15) !important; /* Nền xanh mờ */
            color: #3b82f6 !important; /* Chữ xanh sáng */
            border-left-color: #3b82f6 !important; /* Vạch xanh bên trái */
            font-weight: 600;
        }

        /* Fix Badge căn giữa trong flexbox */
        .nav-link .badge {
            margin-left: auto; /* Đẩy badge về bên phải */
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column shadow">
    <div class="p-4 text-center border-bottom border-secondary border-opacity-25">
        <h4 class="fw-bold mb-0 text-white"><i class="fas fa-shield-alt me-2 text-primary"></i>Admin Panel</h4>
        <small class="text-muted">Car Rental Management</small>
    </div>
    
    <nav class="nav flex-column mt-3 flex-grow-1">
        <?php 
            // Lấy URI hiện tại để so sánh
            $current_uri = $_SERVER['REQUEST_URI']; 
        ?>

        <a class="nav-link <?= strpos($current_uri, '/admin/dashboard') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/dashboard">
            <i class="fas fa-th-large me-2"></i> Dashboard
        </a>

        <a class="nav-link <?= strpos($current_uri, '/admin/vehicles') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/vehicles">
            <i class="fas fa-car me-2"></i> Manage Vehicles
        </a>

        <a class="nav-link d-flex justify-content-between align-items-center <?= strpos($current_uri, '/admin/requests') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/requests">
            <span><i class="fas fa-clipboard-list me-2"></i> Pending Requests</span>
            <?php if (isset($data['totalRequests']) && $data['totalRequests'] > 0): ?>
                <span class="badge bg-danger rounded-pill shadow-sm" style="font-size: 0.7rem; padding: 0.4em 0.65em;">
                    <?= $data['totalRequests'] ?>
                </span>
            <?php endif; ?>
        </a>

        <a class="nav-link <?= strpos($current_uri, '/admin/bookings') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/bookings">
            <i class="fas fa-calendar-check me-2"></i> Bookings
        </a>

        <a class="nav-link <?= strpos($current_uri, '/admin/customers') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/customers">
            <i class="fas fa-users me-2"></i> Customers
        </a>

        <a class="nav-link <?= strpos($current_uri, '/admin/enquiries') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/enquiries">
            <i class="fas fa-envelope me-2"></i> Enquiries
        </a>
        
        <a class="nav-link d-flex justify-content-between align-items-center <?= strpos($current_uri, '/admin/tickets') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/tickets">
            <span><i class="fas fa-ticket-alt me-2"></i> Support Tickets</span>
            <?php if (isset($data['openTickets']) && $data['openTickets'] > 0): ?>
                <span class="badge bg-primary rounded-pill shadow-sm" style="font-size: 0.7rem; padding: 0.4em 0.65em;">
                    <?= $data['openTickets'] ?>
                </span>
            <?php endif; ?>
        </a>

        <div class="mt-auto border-top border-secondary border-opacity-10 py-3">
            <a class="nav-link text-danger" href="/car_rental/public/logout" onclick="return confirm('Logout now?')">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </nav>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">System Overview</h2>
            <p class="text-muted small mb-0">Real-time data from your rental platform</p>
        </div>
        <div class="dropdown">
            <button class="btn btn-white shadow-sm border rounded-pill px-4 py-2" type="button">
                <i class="far fa-calendar-alt me-2"></i> March 2026
            </button>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fas fa-shopping-cart text-primary fa-lg"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Total Bookings</div>
                        <h4 class="fw-bold mb-0"><?= $data['totalBookings'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fas fa-wallet text-success fa-lg"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Revenue</div>
                        <h4 class="fw-bold mb-0"><?= number_format($data['totalRevenue']) ?> <small style="font-size: 10px">VND</small></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fas fa-car text-info fa-lg"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Active Cars</div>
                        <h4 class="fw-bold mb-0"><?= $data['totalCars'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="bg-dark bg-opacity-10 p-3 rounded-3 me-3">
                        <i class="fas fa-users text-dark fa-lg"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Total Users</div>
                        <h4 class="fw-bold mb-0"><?= $data['totalUsers'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-table p-4 mb-4">
        <h5 class="fw-bold mb-4">Revenue Trends</h5>
        <div class="chart-container" style="position: relative; height:350px; width:100%">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card card-table h-100 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between">
                    <h6 class="fw-bold mb-0">Recent Bookings</h6>
                    <a href="/car_rental/public/admin/bookings" class="small text-decoration-none">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Customer</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['recentBookings'] as $booking): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold"><?= $booking['customer_name'] ?></div>
                                        <small class="text-muted"><?= $booking['car_name'] ?></small>
                                    </td>
                                    <td class="fw-bold"><?= number_format($booking['total_price']) ?>đ</td>
                                    <td>
                                        <?php 
                                            $class = "badge-soft-warning";
                                            if($booking['status'] == 'Confirmed') $class = "badge-soft-success";
                                            if($booking['status'] == 'Cancelled') $class = "badge-soft-danger";
                                        ?>
                                        <span class="badge <?= $class ?> rounded-pill"><?= $booking['status'] ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-table h-100 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Quick Access Management</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Update staff or user roles quickly below.</p>
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($data['allUsers'], 0, 5) as $u): ?>
                        <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-0">
                            <div>
                                <div class="fw-bold"><?= $u['name'] ?></div>
                                <span class="badge bg-light text-dark border small"><?= strtoupper($u['role']) ?></span>
                            </div>
                            <form onsubmit="return updateRole(event, <?= $u['id'] ?>)" class="d-flex gap-1">
                                <select class="form-select form-select-sm" id="role_<?= $u['id'] ?>">
                                    <option value="user" <?= $u['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="staff" <?= $u['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
                                    <option value="admin" <?= $u['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Revenue Chart Logic
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Revenue (VND)',
                data: <?= json_encode($data['monthlyRevenue']) ?>,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                fill: true,
                tension: 0.4,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            // QUAN TRỌNG: Tắt cái này để nó tuân theo chiều cao của div bọc ngoài
            maintainAspectRatio: false, 
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + 'đ';
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    function updateRole(e, userId) {
        e.preventDefault();
        const role = document.getElementById('role_' + userId).value;
        fetch('/car_rental/public/admin/update-role', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `user_id=${userId}&role=${role}`
        }).then(() => location.reload());
    }
</script>

</body>
</html>