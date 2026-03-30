<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PrivateHire Cars</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/car_rental/assets/css/admin/request.css">
</head>
<body>

<div class="sidebar d-flex flex-column shadow">
    <div class="p-4 text-center border-bottom border-secondary border-opacity-25">
        <h4 class="fw-bold mb-0 text-white"><i class="fas fa-shield-alt me-2 text-primary"></i>Admin Panel</h4>
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Consignment Requests</h2>
        <span class="badge bg-primary rounded-pill px-3 py-2">Total Pending: <?= count($pendingCars) ?></span>
    </div>

    <div class="card card-table shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 py-3">Owner Details</th>
                            <th>Vehicle Info</th>
                            <th>Specs & Price</th>
                            <th>Photo</th>
                            <th class="text-center">Decision</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($pendingCars)): ?>
                            <?php foreach($pendingCars as $car): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold"><?= htmlspecialchars($car['owner_name']) ?></div>
                                    <small class="text-muted">User ID: #<?= $car['owner_id'] ?></small>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary"><?= htmlspecialchars($car['name']) ?></div>
                                    <div class="small text-muted"><?= htmlspecialchars($car['Branch']) ?></div>
                                    <div class="small text-secondary"><i class="fas fa-map-marker-alt me-1"></i><?= htmlspecialchars($car['location'] ?? 'N/A') ?></div>
                                </td>
                                <td>
                                    <div class="fw-bold text-success"><?= number_format($car['price_per_day']) ?>VND/ day</div>
                                    <div class="small"><?= $car['seats'] ?> Seats | <?= $car['fuel_type'] ?></div>
                                </td>
                                <td>
                                    <img src="/car_rental/images/<?= $car['image'] ?>" width="100" height="60" class="img-car">
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm">
                                        <a href="javascript:void(0)" onclick="approveCar(<?= $car['id'] ?>)" class="btn btn-sm btn-success px-3">Approve</a>
                                        <a href="javascript:void(0)" onclick="rejectCar(<?= $car['id'] ?>)" class="btn btn-sm btn-outline-danger">Reject</a>    
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="opacity-25 mb-3">
                                    <p class="text-muted">No pending requests at the moment.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
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

    function approveCar(id) {
        Swal.fire({
            title: 'Approve Vehicle?', text: "This vehicle will be available for rent on the main page.",
            icon: 'success', showCancelButton: true, confirmButtonColor: '#10b981', confirmButtonText: 'Yes, Approve'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = `/car_rental/public/admin/approve-car?id=${id}`;
        });
    }

    function rejectCar(id) {
        Swal.fire({
            title: 'Reject Request?', text: "This request will be declined.",
            icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', confirmButtonText: 'Yes, Reject'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = `/car_rental/public/admin/reject-car?id=${id}`;
        });
    }
</script>
</html>