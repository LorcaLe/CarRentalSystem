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

        /* CSS cho Badge căn giữa */
        .status-badge {
            width: 80px;  /* Độ rộng cố định */
            height: 30px; /* Độ cao cố định */
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
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

        <div class="mt-auto border-top border-secondary border-opacity-10 py-3">
            <a class="nav-link text-danger" href="/car_rental/public/logout" onclick="return confirm('Logout now?')">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </nav>
</div>

<div class="main-content">
    <h2 class="fw-bold text-dark mb-4">Customer Support Requests</h2>

    <?php if(!empty($enquiries)): ?>
        <?php foreach($enquiries as $enq): ?>
        <div class="card card-enquiry mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-3">
                    <div>
                        <h5 class="fw-bold text-primary mb-0"><?= htmlspecialchars($enq['user_name']) ?></h5>
                        <small class="text-muted"><?= $enq['created_at'] ?></small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge rounded-pill <?= $enq['status'] == 'Replied' ? 'bg-success' : 'bg-warning text-dark' ?>">
                            <?= $enq['status'] ?>
                        </span>
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 mb-3 border-start border-primary border-4">
                    <strong>Message:</strong><br>
                    <?= nl2br(htmlspecialchars($enq['message'])) ?>
                </div>

                <?php if(!empty($enq['reply'])): ?>
                    <div class="p-3 rounded-3" style="background: #ecfdf5; border-left: 4px solid #10b981;">
                        <strong class="text-success">Your Response:</strong><br>
                        <?= nl2br(htmlspecialchars($enq['reply'])) ?>
                    </div>
                <?php else: ?>
                    <form action="/car_rental/public/admin/reply-enquiry" method="POST" class="mt-3">
                        <input type="hidden" name="id" value="<?= $enq['id'] ?>">
                        <textarea name="reply" class="form-control border-0 bg-light mb-2" rows="3" placeholder="Write your reply..." required></textarea>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Send Reply</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-center py-5">
            <i class="fas fa-comment-slash fa-3x text-muted mb-3"></i>
            <p class="text-muted">No support requests yet.</p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>