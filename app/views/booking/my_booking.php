<!DOCTYPE html>
<html>
<head>
    <title>My Rental History - PrivateHire Cars</title>
    <link rel="stylesheet" href="/car_rental/assets/css/style.css">
    <link rel="stylesheet" href="/car_rental/assets/css/book.css">
    <link rel="stylesheet" href="/car_rental/assets/css/layout.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/car_rental/assets/css/booking.css">
</head>
<body>

<?php include __DIR__ . "/../layouts/header.php"; ?>

<div class="booking-container">
    <h2 style="font-size: 2rem; margin-bottom: 30px; color: #1e293b;">My Rental History</h2>
    
    <?php if(empty($bookings)): ?>
        <p>You have no bookings yet. <a href="/car_rental/public/">Book now!</a></p>
    <?php else: ?>
        <?php foreach($bookings as $b): ?>
            <div class="booking-card">
                <img src="/car_rental/images/<?= $b['car_image'] ?>" class="car-img">
                
                <div class="info">
                    <span class="status-badge status-<?= strtolower($b['status']) ?>"><?= $b['status'] ?></span>
                    <h3 style="font-size: 1.4rem; margin-bottom: 10px;"><?= $b['car_name'] ?></h3>
                    <p style="color: #64748b; margin-bottom: 5px;">📍 <?= $b['pickup_location'] ?></p>
                    <div style="display: flex; gap: 20px; font-size: 0.9rem; color: #475569;">
                        <span>📅 Pickup: <?= $b['pickup_date'] ?> (<?= $b['pickup_time'] ?>)</span>
                        <span>📅 Return: <?= $b['return_date'] ?> (<?= $b['return_time'] ?>)</span>
                    </div>
                </div>

                <div style="text-align: right;">
                    <p style="color: #94a3b8; font-size: 0.8rem;">Total Paid</p>
                    <h4 style="color: #2563eb; font-size: 1.2rem; margin-bottom: 15px;"><?= number_format($b['total_price']) ?> VND</h4>
                    
                    <?php if($b['status'] !== 'Cancelled'): ?>
                        <button class="cancel-btn" onclick="cancelBooking(<?= $b['id'] ?>)">Cancel Booking</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function cancelBooking(id) {
    if(confirm('Are you sure you want to cancel this booking?')) {
        fetch('/car_rental/public/cancel-booking', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(res => res.text())
        .then(data => {
            alert(data);
            location.reload();
        });
    }
}

</script>
</body>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
</html>