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
                    
                    <?php 
                    $now = new DateTime();

                    // Kiểm tra đã qua return date chưa
                    $returnDateTime = new DateTime($b['return_date'] . ' ' . $b['return_time']);
                    $isReturned = $now > $returnDateTime;

                    // Kiểm tra đã qua 24h kể từ pickup chưa
                    $pickupDateTime = new DateTime($b['pickup_date'] . ' ' . $b['pickup_time']);
                    $pickupDateTime->modify('+24 hours');
                    $past24h = $now > $pickupDateTime;
                    ?>

                    <?php if($isReturned): ?>
                        <?php /* Đã qua return date — không hiện gì */ ?>
                    <?php elseif($b['status'] !== 'Cancelled' && !$past24h): ?>
                        <button class="cancel-btn" onclick="cancelBooking(<?= $b['id'] ?>)">Cancel Booking</button>
                    <?php elseif($b['status'] !== 'Cancelled' && $past24h): ?>
                        <span style="color: #94a3b8; font-size: 0.85rem; font-style: italic;"
                              title="Cancellation is not allowed after 24 hours from pickup">
                            <i class="fas fa-clock me-1"></i>Cannot cancel
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Result Popup -->
<div id="result-overlay">
    <div id="result-box">
        <div class="result-icon" id="result-icon"></div>
        <h3 id="result-title"></h3>
        <p id="result-msg"></p>
        <button id="result-ok" onclick="closeResult()">OK</button>
    </div>
</div>

<!-- Confirm Modal -->
<div id="confirm-overlay">
    <div id="confirm-box">
        <div class="confirm-icon"><i class="fa fa-triangle-exclamation"></i></div>
        <h3>Booking Cancellation ?</h3>
        <p>Are you sure you want to cancel this booking?<br>This action cannot be undone.</p>
        <div class="confirm-actions">
            <button id="confirm-no"  onclick="closeConfirm()">No, keep it</button>
            <button id="confirm-yes">Yes, cancel now</button>
        </div>
    </div>
</div>

<script>
/* ── Result Popup ── */
let _reloadAfter = false;

function showResult(title, msg, type = 'success') {
    _reloadAfter = type === 'success';
    const icons = { success: '✅', error: '❌' };
    document.getElementById('result-icon').textContent  = icons[type];
    document.getElementById('result-icon').className    = 'result-icon ' + type;
    document.getElementById('result-title').textContent = title;
    document.getElementById('result-msg').textContent   = msg;
    document.getElementById('result-ok').className      = type;
    document.getElementById('result-overlay').classList.add('show');
}

function closeResult() {
    document.getElementById('result-overlay').classList.remove('show');
    if (_reloadAfter) location.reload();
}

/* ── Confirm Modal ── */
let _pendingId = null;
function cancelBooking(id) {
    _pendingId = id;
    document.getElementById('confirm-overlay').classList.add('show');
}
function closeConfirm() {
    document.getElementById('confirm-overlay').classList.remove('show');
    _pendingId = null;
}

document.getElementById('confirm-yes').addEventListener('click', function () {
    if (_pendingId === null) return;
    const id = _pendingId;
    closeConfirm();

    fetch('/car_rental/public/cancel-booking', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id
    })
    .then(res => res.text())
    .then(data => {
        const hasPhpError = /<b>(Warning|Fatal error|Notice|Error)<\/b>/i.test(data)
                         || /on line <b>\d+<\/b>/i.test(data);

        if (hasPhpError) {
            showResult('Success', 'Booking cancelled successfully.', 'success');
            return;
        }

        const isError = /error|fail|lỗi/i.test(data);
        if (isError) {
            showResult('Error', data.trim() || 'Please try again.', 'error');
        } else {
            showResult('Success', 'Booking cancelled successfully.', 'success');
        }
    })
    .catch(() => {
        showResult('Error', 'An error occurred. Please try again later.', 'error');
    });
});

document.getElementById('confirm-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeConfirm();
});
</script>
</body>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
</html>