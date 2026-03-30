<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PrivateHire Cars</title>
    <!-- CSS Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/car_rental/assets/css/admin/dashboard.css">
</head>
<body>

<div class="sidebar d-flex flex-column shadow">
    <div class="p-4 text-center border-bottom border-secondary border-opacity-25">
        <h4 class="fw-bold mb-0 text-white"><i class="fas fa-shield-alt me-2 text-primary"></i>Admin Panel</h4>
    </div>
    
    <nav class="nav flex-column mt-3 flex-grow-1">
        <?php $current_uri = $_SERVER['REQUEST_URI']; ?>
        <!-- Giữ nguyên Sidebar Links của bạn -->
        <a class="nav-link <?= strpos($current_uri, '/admin/dashboard') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/dashboard"><i class="fas fa-th-large me-2"></i> Dashboard</a>
        <a class="nav-link <?= strpos($current_uri, '/admin/vehicles') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/vehicles"><i class="fas fa-car me-2"></i> Manage Vehicles</a>
        <a class="nav-link d-flex justify-content-between align-items-center <?= strpos($current_uri, '/admin/requests') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/requests">
            <span><i class="fas fa-clipboard-list me-2"></i> Pending Requests</span>
            <?php if (isset($data['totalRequests']) && $data['totalRequests'] > 0): ?>
                <span class="badge bg-danger rounded-pill shadow-sm" style="font-size: 0.7rem; padding: 0.4em 0.65em;"><?= $data['totalRequests'] ?></span>
            <?php endif; ?>
        </a>
        <a class="nav-link <?= strpos($current_uri, '/admin/bookings') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/bookings"><i class="fas fa-calendar-check me-2"></i> Bookings</a>
        <a class="nav-link <?= strpos($current_uri, '/admin/customers') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/customers"><i class="fas fa-users me-2"></i> Customers</a>
        <a class="nav-link <?= strpos($current_uri, '/admin/enquiries') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/enquiries"><i class="fas fa-envelope me-2"></i> Enquiries</a>
        <a class="nav-link d-flex justify-content-between align-items-center <?= strpos($current_uri, '/admin/tickets') !== false ? 'active' : '' ?>" href="/car_rental/public/admin/tickets">
            <span><i class="fas fa-ticket-alt me-2"></i> Support Tickets</span>
            <?php if (isset($data['openTickets']) && $data['openTickets'] > 0): ?>
                <span class="badge bg-primary rounded-pill shadow-sm" style="font-size: 0.7rem; padding: 0.4em 0.65em;"><?= $data['openTickets'] ?></span>
            <?php endif; ?>
        </a>
        <div class="mt-auto border-top border-secondary border-opacity-10 py-3">
            <a class="nav-link text-danger" href="/car_rental/public/logout" id="logoutBtn"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
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
                <i class="far fa-calendar-alt me-2"></i> April 2026
            </button>
        </div>
    </div>

    <!-- Stats Cards (Giữ nguyên) -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-white p-3 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3"><i class="fas fa-shopping-cart text-primary fa-lg"></i></div>
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
                    <div class="bg-success bg-opacity-10 p-3 rounded-3 me-3"><i class="fas fa-wallet text-success fa-lg"></i></div>
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
                    <div class="bg-info bg-opacity-10 p-3 rounded-3 me-3"><i class="fas fa-car text-info fa-lg"></i></div>
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
                    <div class="bg-dark bg-opacity-10 p-3 rounded-3 me-3"><i class="fas fa-users text-dark fa-lg"></i></div>
                    <div>
                        <div class="small text-muted">Total Users</div>
                        <h4 class="fw-bold mb-0"><?= $data['totalUsers'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-table p-4 mb-4 shadow-sm">
        <h5 class="fw-bold mb-4">Revenue Trends</h5>
        <div class="chart-container" style="position: relative; height:350px; width:100%">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="row">
        <!-- Recent Bookings Table -->
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
                                    <td class="fw-bold"><?= number_format($booking['total_price']) ?>VND</td>
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

        <!-- QUẢN LÝ QUYỀN (QUICK ACCESS) ĐÃ ĐƯỢC LÀM MỚI -->
        <div class="col-lg-5">
            <div class="card card-table h-100 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold mb-0">Quick Access Management</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">Update staff or user roles quickly below.</p>
                    <div class="list-group list-group-flush">
                        <?php foreach (array_slice($data['allUsers'], 0, 5) as $u): ?>
                        <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-0">
                            <div>
                                <div class="fw-bold"><?= $u['name'] ?></div>
                                <div class="small text-muted">ID: #<?= $u['id'] ?></div>
                            </div>
                            
                            <?php 
                                // Logic xử lý màu và icon cho Quick Access
                                $role = strtolower($u['role']);
                                // Mặc định cho staff/partner nếu db của bạn lưu là staff
                                if ($role === 'staff') $role = 'partner'; 
                                
                                $bgClass = 'bg-secondary text-white';
                                $iconClass = 'fa-user';

                                if($role == 'admin') {
                                    $bgClass = 'bg-danger text-white';
                                    $iconClass = 'fa-user-shield';
                                } elseif($role == 'partner') {
                                    $bgClass = 'bg-warning text-dark';
                                    $iconClass = 'fa-handshake';
                                }
                            ?>
                            
                            <!-- Dropdown Role mới thay cho Form Cũ -->
                            <div class="dropdown">
                                <button class="btn btn-sm dropdown-toggle rounded-pill fw-bold border-0 <?= $bgClass ?> role-btn-<?= $u['id'] ?>" 
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                        style="width: 110px; font-size: 0.7rem; letter-spacing: 0.5px;">
                                    <i class="fas <?= $iconClass ?> me-1 role-icon-<?= $u['id'] ?>"></i> 
                                    <span class="role-text-<?= $u['id'] ?>"><?= strtoupper($role) ?></span>
                                </button>
                                
                                <ul class="dropdown-menu border-0 shadow rounded-3 fs-7" style="min-width: 130px;">
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center role-option" href="#" 
                                        data-user-id="<?= $u['id'] ?>" data-user-name="<?= htmlspecialchars($u['name']) ?>" data-new-role="admin">
                                            <i class="fas fa-user-shield text-danger me-2" style="width: 20px; text-align: center;"></i> <b>ADMIN</b>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center role-option" href="#" 
                                        data-user-id="<?= $u['id'] ?>" data-user-name="<?= htmlspecialchars($u['name']) ?>" data-new-role="partner">
                                            <i class="fas fa-handshake text-warning me-2" style="width: 20px; text-align: center;"></i> <b>PARTNER</b>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2 d-flex align-items-center role-option" href="#" 
                                        data-user-id="<?= $u['id'] ?>" data-user-name="<?= htmlspecialchars($u['name']) ?>" data-new-role="user">
                                            <i class="fas fa-user text-secondary me-2" style="width: 20px; text-align: center;"></i> <b>USER</b>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS BẮT BUỘC -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Xử lý nút Logout
    document.getElementById('logoutBtn')?.addEventListener('click', function(e) {
        e.preventDefault();
        const logoutUrl = this.href;
        Swal.fire({
            title: 'Ready to leave?', 
            text: "You are about to log out of the Admin Dashboard.",
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, Logout'
        }).then((result) => { 
            if (result.isConfirmed) window.location.href = logoutUrl; 
        });
    });

    // Xử lý Chart.js
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
            maintainAspectRatio: false, 
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { callback: function(value) { return value.toLocaleString() + 'đ'; } }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Gắn sự kiện click đổi Role cho tất cả các nút trong Dropdown
    document.querySelectorAll('.role-option').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault(); // Ngăn load lại trang
            
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            const newRole = this.getAttribute('data-new-role');
            
            // Lấy UI elements
            const btn = document.querySelector(`.role-btn-${userId}`);
            const icon = document.querySelector(`.role-icon-${userId}`);
            const textSpan = document.querySelector(`.role-text-${userId}`);
            const currentRole = textSpan.innerText.toLowerCase();

            if (currentRole === newRole) return;

            Swal.fire({
                title: 'Confirm Role Change',
                html: `Are you sure you want to change <b>${userName}</b>'s role to <b>${newRole.toUpperCase()}</b>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, change it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Updating...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});

                    const formData = new URLSearchParams();
                    formData.append('user_id', userId);
                    formData.append('role', newRole);

                    fetch('/car_rental/public/admin/update-role', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: formData.toString()
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === 'success') {
                            Swal.fire({ icon: 'success', title: 'Updated!', text: `${userName} is now a ${newRole.toUpperCase()}.`, timer: 1500, showConfirmButton: false });
                            
                            textSpan.innerText = newRole.toUpperCase();
                            btn.classList.remove('bg-danger', 'bg-warning', 'bg-secondary', 'text-white', 'text-dark');
                            icon.classList.remove('fa-user-shield', 'fa-handshake', 'fa-user');

                            if (newRole === 'admin') {
                                btn.classList.add('bg-danger', 'text-white');
                                icon.classList.add('fa-user-shield');
                            } else if (newRole === 'partner') {
                                btn.classList.add('bg-warning', 'text-dark');
                                icon.classList.add('fa-handshake');
                            } else {
                                btn.classList.add('bg-secondary', 'text-white');
                                icon.classList.add('fa-user');
                            }
                        } else {
                            Swal.fire('Error', 'Failed to update role in database.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Network error. Could not connect to server.', 'error');
                    });
                }
            });
        });
    });
</script>
</body>
</html>