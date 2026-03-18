<!DOCTYPE html>
<html>
<head>
    <title>My Rental History - Luxury Car Rental</title>
    <link rel="stylesheet" href="/car_rental/assets/css/style.css">
    <link rel="stylesheet" href="/car_rental/assets/css/book.css">
    <link rel="stylesheet" href="/car_rental/assets/css/layout.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .booking-container { max-width: 1200px; margin: 120px auto 50px; padding: 0 20px; }
        .booking-card { 
            background: white; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
            display: grid; grid-template-columns: 300px 1fr auto; gap: 25px; padding: 20px; margin-bottom: 25px;
            transition: 0.3s; align-items: center;
        }
        .booking-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        .car-img { width: 100%; height: 180px; object-fit: cover; border-radius: 15px; }
        .status-badge { padding: 5px 15px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; display: inline-block; margin-bottom: 10px; }
        .status-confirmed { background: #dcfce7; color: #15803d; }
        .status-pending { background: #fef9c3; color: #a16207; }
        .status-cancelled { background: #fee2e2; color: #b91c1c; }
        .cancel-btn { 
            background: #fff; border: 1.5px solid #fee2e2; color: #dc2626; 
            padding: 10px 20px; border-radius: 12px; cursor: pointer; font-weight: 500; transition: 0.3s;
        }
        .cancel-btn:hover { background: #dc2626; color: white; }
    </style>
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