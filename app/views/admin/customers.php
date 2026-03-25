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
            <a class="nav-link text-danger" href="/car_rental/public/logout" id="logoutBtn">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </nav>
</div>

<div class="main-content">
    <h2 class="fw-bold mb-4">Customer & Staff List</h2>

    <div class="card stat-card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">User Info</th>
                            <th class="py-3">Contact</th>
                            <th class="py-3">Role</th>
                            <th class="py-3">Joined Date</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['customers'] as $c): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-primary"><?= $c['name'] ?></div>
                                <div class="small text-muted">ID: #<?= $c['id'] ?></div>
                            </td>
                            <td>
                                <div><i class="far fa-envelope me-1 small"></i> <?= $c['email'] ?: 'N/A' ?></div>
                                <div class="small"><i class="fas fa-phone me-1 small"></i> <?= $c['phone'] ?: 'N/A' ?></div>
                            </td>
                            <td>
                                <?php 
                                    $badge = 'bg-secondary';
                                    if($c['role'] == 'admin') $badge = 'bg-danger';
                                    if($c['role'] == 'staff') $badge = 'bg-info text-dark';
                                ?>
                                <span class="badge <?= $badge ?> rounded-pill px-3"><?= strtoupper($c['role']) ?></span>
                            </td>
                            <td>
                                <?= isset($c['created_at']) ? date('d M, Y', strtotime($c['created_at'])) : 'Unknown' ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light border" title="View Details"><i class="fas fa-eye text-muted"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('logoutBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        const logoutUrl = this.href;
        Swal.fire({
            title: 'Ready to leave?', text: "You are about to log out of the Admin Dashboard.",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, Logout'
        }).then((result) => { if (result.isConfirmed) window.location.href = logoutUrl; });
    });
</script>
</html>