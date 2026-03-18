<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Staff - CarRental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar { width: 250px; height: 100vh; position: fixed; background: #111827; color: #fff; }
        .main-content { margin-left: 250px; padding: 30px; background: #f9fafb; min-height: 100vh; }
        .nav-link { color: #9ca3af; padding: 12px 20px; transition: 0.3s; }
        .nav-link:hover { background: #1f2937; color: #fff; }
        .nav-link.active { background: #3b82f6; color: #fff; } /* Highlight Customers/Staff link */
        .stat-card { border: none; border-radius: 15px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<?php include __DIR__ . "/../layouts/admin_sidebar.php"; ?>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">User & Staff Management</h2>
        <div class="text-muted">Total Users: <?= count($users) ?></div>
    </div>

    <div class="card stat-card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">ID</th>
                            <th class="py-3">User Info</th>
                            <th class="py-3">Current Role</th>
                            <th class="py-3 text-center">Change Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $u): ?>
                        <tr>
                            <td class="ps-4 fw-bold text-muted">#<?= $u['id'] ?></td>
                            <td>
                                <div class="fw-bold"><?= $u['name'] ?></div>
                                <div class="small text-muted"><?= $u['email'] ?: $u['phone'] ?></div>
                            </td>
                            <td>
                                <?php 
                                    $roleBadge = 'bg-secondary';
                                    if($u['role'] == 'admin') $roleBadge = 'bg-danger';
                                    if($u['role'] == 'staff') $roleBadge = 'bg-info text-dark';
                                ?>
                                <span class="badge <?= $roleBadge ?> rounded-pill px-3">
                                    <?= strtoupper($u['role']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <form onsubmit="return updateRole(event, <?= $u['id'] ?>)" class="d-inline-flex gap-2 pe-4">
                                    <select class="form-select form-select-sm rounded-3" id="role_<?= $u['id'] ?>" style="width: 120px;">
                                        <option value="user" <?= $u['role'] == 'user' ? 'selected' : '' ?>>User</option>
                                        <option value="staff" <?= $u['role'] == 'staff' ? 'selected' : '' ?>>Staff</option>
                                        <option value="admin" <?= $u['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary rounded-3 px-3">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * AJAX to update user role 
 * Logic remains the same, but comments are in English
 */
function updateRole(e, userId) {
    e.preventDefault();
    const role = document.getElementById('role_' + userId).value;
    
    // Using fetch to communicate with AdminController::updateRole
    fetch('/car_rental/public/admin/update-role', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `user_id=${userId}&role=${role}`
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        location.reload(); // Refresh to show new badges
    })
    .catch(err => console.error("Update failed:", err));
    
    return false;
}
</script>
</body>
</html>