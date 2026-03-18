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
<body class="bg-light">

<?php require_once __DIR__ . "/../layouts/header.php"; ?>

<div class="container my-5 py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg rounded-4 p-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                </div>
                <h2 class="fw-bold mb-3">Submission Successful!</h2>
                <p class="text-muted mb-4">
                    Cảm ơn <strong><?= $_SESSION['user']['name'] ?></strong>! Yêu cầu ký gửi xe của bạn đã được gửi tới hệ thống. 
                    Vui lòng đợi quản trị viên duyệt thông tin xe trong vòng 24h.
                </p>
                <div class="d-grid gap-2">
                    <a href="/car_rental/public/my_booking" class="btn btn-primary btn-lg shadow-sm fw-bold">
                        <i class="fas fa-list me-2"></i>Xem đơn đặt xe của tôi
                    </a>
                    <a href="/car_rental/public/" class="btn btn-outline-secondary">
                        Quay về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>

</body>
</html>