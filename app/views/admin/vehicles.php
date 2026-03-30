<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - PrivateHire Cars</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/car_rental/assets/css/admin/vehicle.css">
</head>
<body>

<div class="sidebar d-flex flex-column shadow">
    <div class="p-4 text-center border-bottom border-secondary border-opacity-25">
        <h4 class="fw-bold mb-0 text-white"><i class="fas fa-shield-alt me-2 text-primary"></i>Admin Panel</h4>
    </div>
    
    <nav class="nav flex-column mt-3 flex-grow-1">
        <?php $current_uri = $_SERVER['REQUEST_URI']; ?>

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
        <h2 class="fw-bold">Manage Vehicles</h2>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
            <i class="fas fa-plus me-2"></i>Add New Car
        </button>
    </div>

    <div class="card stat-card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">Image</th>
                            <th class="py-3">Car Name</th>
                            <th class="py-3">Brand</th>
                            <th class="py-3">Owner</th> <th class="py-3">Price/Day</th>
                            <th class="py-3 text-center">Seats</th>
                            <th class="py-3">Transmission</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['vehicles'])): ?>
                            <?php foreach ($data['vehicles'] as $car): ?>
                            <tr>
                                <td class="ps-4">
                                    <img src="/car_rental/images/<?= htmlspecialchars($car['image']) ?>" 
                                         width="100" height="60" 
                                         style="object-fit: cover; border-radius: 8px;" 
                                         alt="car" onerror="this.src='/car_rental/images/default-car.jpg'">
                                </td>
                                <td class="fw-bold"><?= htmlspecialchars($car['name']) ?></td>
                                <td><?= htmlspecialchars($car['Branch']) ?></td>
                                
                                <td>
                                    <?php if (!empty($car['owner_name'])): ?>
                                        <span class="badge bg-light text-dark border px-2 py-1"><i class="fas fa-user-tie text-muted me-1"></i> <?= htmlspecialchars($car['owner_name']) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-2 py-1"><i class="fas fa-building me-1"></i> Company</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-primary fw-bold"><?= number_format($car['price_per_day']) ?> VND</td>
                                <td class="text-center"><?= $car['seats'] ?></td>
                                <td><?= $car['transmission'] ?></td>
                                <td>
                                    <?php if ($car['available'] == 1): ?>
                                        <span class="badge bg-success rounded-pill px-3">Available</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark rounded-pill px-3">Rented</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn btn-sm btn-light border" 
                                                onclick='openEditModal(<?= json_encode($car) ?>)'>
                                            <i class="fas fa-edit text-info"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light border" 
                                                onclick="deleteVehicle(<?= $car['id'] ?>)" title="Delete">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-center py-5 text-muted">No vehicles found in database.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="/car_rental/public/admin/add-vehicle" method="POST" enctype="multipart/form-data">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Add New Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Car Name</label><input type="text" name="name" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Brand (Branch)</label><input type="text" name="branch" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Price Per Day</label><input type="number" name="price_per_day" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Seats</label><input type="number" name="seats" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Fuel Type</label><input type="text" name="fuel_type" class="form-control" placeholder="e.g. Petrol, Diesel, Electric"></div>
                        <div class="col-md-12"><label class="form-label">Transmission</label><select name="transmission" class="form-select"><option value="Automatic">Automatic</option><option value="Manual">Manual</option></select></div>
                        <div class="col-md-12">
                            <label class="form-label">Car Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required onchange="previewImage(this, 'imagePreview')">
                            <div id="imagePreview" class="mt-2 text-center border rounded-3 p-2" style="min-height: 100px; background: #f8f9fa;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Save Vehicle</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="/car_rental/public/admin/edit-vehicle" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Edit Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Car Name</label><input type="text" name="name" id="edit_name" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Brand</label><input type="text" name="branch" id="edit_branch" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Price Per Day</label><input type="number" name="price_per_day" id="edit_price_per_day" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Seats</label><input type="number" name="seats" id="edit_seats" class="form-control" required></div>
                        <div class="col-md-4"><label class="form-label">Fuel Type</label><input type="text" name="fuel_type" id="edit_fuel_type" class="form-control"></div>
                        <div class="col-md-12"><label class="form-label">Transmission</label><select name="transmission" id="edit_transmission" class="form-select"><option value="Automatic">Automatic</option><option value="Manual">Manual</option></select></div>
                        <div class="col-md-12">
                            <label class="form-label">New Image (Leave blank to keep current)</label>
                            <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this, 'editImagePreview')">
                            <div id="editImagePreview" class="mt-2 text-center border rounded-3 p-2" style="min-height: 100px; background: #f8f9fa;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-4 shadow-sm text-white">Update Vehicle</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" style="max-height: 150px; border-radius: 10px;" class="img-fluid">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function deleteVehicle(id) {
    Swal.fire({
        title: 'Delete Vehicle?', text: "You won't be able to revert this! All data will be lost.",
        icon: 'error', showCancelButton: true, confirmButtonColor: '#dc3545', confirmButtonText: 'Yes, Delete'
    }).then((result) => {
        if (result.isConfirmed) window.location.href = `/car_rental/public/admin/delete-vehicle?id=${id}`;
    });
}

function openEditModal(car) {
    document.getElementById('edit_id').value = car.id;
    document.getElementById('edit_name').value = car.name;
    document.getElementById('edit_branch').value = car.Branch;
    document.getElementById('edit_price_per_day').value = car.price_per_day;
    document.getElementById('edit_seats').value = car.seats;
    document.getElementById('edit_transmission').value = car.transmission;
    document.getElementById('edit_fuel_type').value = car.fuel_type || ''; 
    
    var myModal = new bootstrap.Modal(document.getElementById('editVehicleModal'));
    myModal.show();
}

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
</body>
</html>