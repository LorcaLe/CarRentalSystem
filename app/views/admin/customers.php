<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PrivateHire Cars</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/car_rental/assets/css/admin/customer.css">
</head>
<body>

<div class="sidebar d-flex flex-column shadow">
    <div class="p-4 text-center border-bottom border-secondary border-opacity-25">
        <h4 class="fw-bold mb-0 text-white"><i class="fas fa-shield-alt me-2 text-primary"></i>Admin Panel</h4>
    </div>
    
    <nav class="nav flex-column mt-3 flex-grow-1">
        <?php $current_uri = $_SERVER['REQUEST_URI']; ?>

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
    <h2 class="fw-bold mb-4">Customer & Partner List</h2>

    <div class="card stat-card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">User Info</th>
                            <th class="py-3">Contact</th>
                            <th class="py-3 text-center">Role</th>
                            <th class="py-3">Joined Date</th>
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
                            <td class="text-center">
                                <?php 
                                    // Xác định class màu và icon dựa trên role
                                    $role = strtolower($c['role']);
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
                                
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle rounded-pill fw-bold border-0 <?= $bgClass ?> role-btn-<?= $c['id'] ?>" 
                                            type="button" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false"
                                            style="width: 125px; font-size: 0.75rem; letter-spacing: 0.5px;">
                                        <i class="fas <?= $iconClass ?> me-1 role-icon-<?= $c['id'] ?>"></i> 
                                        <span class="role-text-<?= $c['id'] ?>"><?= strtoupper($role) ?></span>
                                    </button>
                                    
                                    <ul class="dropdown-menu border-0 shadow rounded-3 fs-7" style="min-width: 140px;">
                                        <li>
                                            <a class="dropdown-item py-2 d-flex align-items-center role-option" href="#" 
                                            data-user-id="<?= $c['id'] ?>" 
                                            data-user-name="<?= htmlspecialchars($c['name']) ?>"
                                            data-new-role="admin">
                                                <i class="fas fa-user-shield text-danger me-2" style="width: 20px; text-align: center;"></i> <b>ADMIN</b>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2 d-flex align-items-center role-option" href="#" 
                                            data-user-id="<?= $c['id'] ?>" 
                                            data-user-name="<?= htmlspecialchars($c['name']) ?>"
                                            data-new-role="partner">
                                                <i class="fas fa-handshake text-warning me-2" style="width: 20px; text-align: center;"></i> <b>PARTNER</b>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2 d-flex align-items-center role-option" href="#" 
                                            data-user-id="<?= $c['id'] ?>" 
                                            data-user-name="<?= htmlspecialchars($c['name']) ?>"
                                            data-new-role="user">
                                                <i class="fas fa-user text-secondary me-2" style="width: 20px; text-align: center;"></i> <b>USER</b>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <?= isset($c['created_at']) ? date('d M, Y', strtotime($c['created_at'])) : 'Unknown' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ĐÂY LÀ JS BẮT BUỘC ĐỂ DROPDOWN CỦA BOOTSTRAP CHẠY ĐƯỢC -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

    // Xử lý thay đổi Role
    document.querySelectorAll('.role-option').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault(); // Ngăn load lại trang khi click thẻ <a>
            
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            const newRole = this.getAttribute('data-new-role');
            
            // Lấy UI elements để cập nhật sau khi thành công
            const btn = document.querySelector(`.role-btn-${userId}`);
            const icon = document.querySelector(`.role-icon-${userId}`);
            const textSpan = document.querySelector(`.role-text-${userId}`);
            const currentRole = textSpan.innerText.toLowerCase();

            // Nếu click vào role hiện tại thì bỏ qua
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
                            Swal.fire({ icon: 'success', title: 'Updated!', text: `${userName} is now a ${newRole.toUpperCase()}.`, confirmButtonColor: '#3b82f6' });
                            
                            // 1. Cập nhật Text
                            textSpan.innerText = newRole.toUpperCase();
                            
                            // 2. Xóa các class màu cũ
                            btn.classList.remove('bg-danger', 'bg-warning', 'bg-secondary', 'text-white', 'text-dark');
                            icon.classList.remove('fa-user-shield', 'fa-handshake', 'fa-user');

                            // 3. Thêm class màu và icon mới
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
                        console.error('Error:', error);
                        Swal.fire('Error', 'Network error. Could not connect to server.', 'error');
                    });
                }
            });
        });
    });
</script>
</body>
</html>