<?php require_once __DIR__ . "/../layouts/header.php"; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white p-3">
                    <h4 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Book Your Vehicle</h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 shadow-sm">
                        <strong>Selected Vehicle:</strong> <?= htmlspecialchars($vehicle['name']) ?> 
                        <span class="badge bg-white text-primary ms-2"><?= number_format($vehicle['price_per_day']) ?> VND/day</span>
                    </div>

                    <form action="/car_rental/public/book-car" method="POST" id="bookingForm">
                        
                        <input type="hidden" name="vehicle_id" value="<?= htmlspecialchars($vehicle['id']) ?>">

                        <div class="mb-3">
                            <label for="pickup_location" class="form-label text-dark fw-bold">Pickup Location</label>
                            <input type="text" id="pickup_location" name="pickup_location" class="form-control" placeholder="e.g., Binh Duong" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pickup_date" class="form-label text-dark fw-bold">Pickup Date</label>
                                <input type="date" id="pickup_date" name="pickup_date" 
                                       class="form-control" 
                                       min="<?= date('Y-m-d') ?>" 
                                       required onchange="updateReturnMin()">
                            </div>
                            <div class="col-md-6">
                                <label for="pickup_time" class="form-label text-dark fw-bold">Pickup Time</label>
                                <input type="time" id="pickup_time" name="pickup_time" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="return_date" class="form-label text-dark fw-bold">Return Date</label>
                                <input type="date" id="return_date" name="return_date" 
                                       class="form-control" 
                                       min="<?= date('Y-m-d') ?>" 
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label for="return_time" class="form-label text-dark fw-bold">Return Time</label>
                                <input type="time" id="return_time" name="return_time" class="form-control" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg shadow-sm">
                                Check Availability & Continue
                            </button>
                            <a href="javascript:history.back()" class="btn btn-outline-secondary border-0">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// 1. Cập nhật ngày trả xe tối thiểu dựa trên ngày nhận xe
function updateReturnMin() {
    const pickupDate = document.getElementById('pickup_date').value;
    const returnDateInput = document.getElementById('return_date');
    
    // Ngày trả xe không thể trước ngày nhận xe
    returnDateInput.min = pickupDate;
    
    // Nếu ngày trả đang chọn nhỏ hơn ngày nhận mới chọn, thì reset lại
    if (returnDateInput.value < pickupDate) {
        returnDateInput.value = pickupDate;
    }
}

// 2. Chặn submit nếu dữ liệu không hợp lệ (Double Check)
document.getElementById('bookingForm').onsubmit = function(e) {
    const pickupDate = new Date(document.getElementById('pickup_date').value);
    const returnDate = new Date(document.getElementById('return_date').value);
    const today = new Date();
    today.setHours(0,0,0,0);

    if (pickupDate < today) {
        alert("Pickup date cannot be in the past!");
        e.preventDefault();
        return false;
    }

    if (returnDate < pickupDate) {
        alert("Return date must be after or equal to pickup date!");
        e.preventDefault();
        return false;
    }
};
</script>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>