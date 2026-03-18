<!DOCTYPE html>
<html>

<head>

<title>Luxury Car Rental</title>
<link rel="stylesheet" href="/car_rental/assets/css/book.css">
<link rel="stylesheet" href="/car_rental/assets/css/style.css">
<link rel="stylesheet" href="/car_rental/assets/css/layout.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

<?php require_once __DIR__ . "/../layouts/header.php"; ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-4 text-center border-0">
                    <h3 class="fw-bold mb-1 text-white">Consign Your Vehicle</h3>
                    <p class="mb-0 opacity-75">Join our premium rental network and earn today</p>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="/car_rental/public/submit-consignment" method="POST" enctype="multipart/form-data">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Car Name</label>
                                <input type="text" name="name" class="form-control bg-light border-0 py-3" placeholder="e.g. Mercedes-Benz C200" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Brand (Branch)</label>
                                <input type="text" name="branch" class="form-control bg-light border-0 py-3" placeholder="e.g. Mercedes" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Price / Day (VND)</label>
                                <input type="number" name="price_per_day" class="form-control bg-light border-0" placeholder="1000000" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Seats</label>
                                <input type="number" name="seats" class="form-control bg-light border-0" placeholder="5" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Fuel Type</label>
                                <select name="fuel_type" class="form-select bg-light border-0">
                                    <option value="Petrol">Petrol</option>
                                    <option value="Diesel">Diesel</option>
                                    <option value="Electric">Electric</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold text-dark">Vehicle Location (City/District)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-map-marker-alt text-danger"></i></span>
                                    <input type="text" name="location" class="form-control bg-light border-0 py-2" placeholder="e.g. District 1, Ho Chi Minh City" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold d-block">Transmission</label>
                                <div class="d-flex gap-4 p-2 bg-light rounded-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="transmission" value="Automatic" id="auto" checked>
                                        <label class="form-check-label" for="auto">Automatic</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="transmission" value="Manual" id="manual">
                                        <label class="form-check-label" for="manual">Manual</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Vehicle Photo</label>
                                <input type="file" name="image" class="form-control border-0 bg-light" accept="image/*" required onchange="previewImg(this)">
                                <div id="previewContainer" class="mt-3 text-center border rounded-4 p-3 d-none bg-light">
                                    <img id="imagePreview" src="#" alt="Preview" style="max-height: 250px; border-radius: 12px;" class="shadow-sm">
                                </div>
                            </div>

                            <div class="col-md-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100 shadow py-3 fw-bold">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Consignment Request
                                </button>
                                <p class="text-center text-muted mt-3 small">
                                    <i class="fas fa-info-circle me-1"></i> Admin will review your request within 24 hours.
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImg(input) {
    const container = document.getElementById('previewContainer');
    const preview = document.getElementById('imagePreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.classList.remove('d-none');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
</body>
</html>