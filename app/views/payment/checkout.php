<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Checkout - CarRental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary:    #2563eb;
            --dark-slate: #1e293b;
            --glass-bg:   rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.5);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)),
                        url('/car_rental/images/main_background.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            color: #1f2937;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
        }

        .spec-badge {
            background: #f1f5f9; color: #475569;
            padding: 6px 14px; border-radius: 12px;
            font-size: 0.8rem; font-weight: 500;
            border: 1px solid #e2e8f0;
        }

        .btn-review-mini {
            background: var(--primary); color: white;
            font-size: 0.75rem; padding: 6px 14px;
            border-radius: 20px; font-weight: 600; border: none;
        }

        .btn-pay {
            background: var(--primary) !important; color: white;
            border: none; padding: 20px; border-radius: 18px;
            font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .btn-pay:hover {
            background: #1d4ed8 !important;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.4);
        }

        .price-badge {
            background: var(--dark-slate); color: white;
            padding: 12px 24px; border-radius: 14px;
            font-size: 1.3rem; font-weight: 800;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        /* Discount badge nổi bật */
        .discount-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #15803d;
            border: 1px solid #86efac;
            padding: 5px 12px; border-radius: 20px;
            font-size: 0.78rem; font-weight: 700;
        }

        /* Dòng giá gốc bị gạch ngang */
        .original-price {
            text-decoration: line-through;
            color: #94a3b8;
            font-size: 0.9rem;
        }

        /* Dòng giảm giá màu xanh lá */
        .saving-row {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 10px 14px;
        }

        .payment-item {
            transition: all 0.3s ease; cursor: pointer;
            border: 2px solid #f1f5f9 !important;
            border-radius: 20px !important; background: white;
        }
        .payment-item.active {
            border-color: var(--primary) !important;
            background: #f8faff;
        }

        .car-thumb {
            width: 100%; height: 200px; object-fit: contain;
            filter: drop-shadow(0 20px 30px rgba(0,0,0,0.15));
            transition: transform 0.5s ease;
        }
        .car-thumb:hover { transform: scale(1.05); }
    </style>
</head>
<body>

<?php
    // ── Tính tiền ────────────────────────────────────────────────────────────
    $diff          = strtotime($_POST['return_date']) - strtotime($_POST['pickup_date']);
    $days          = max(1, round($diff / 86400));
    $originalTotal = $days * $vehicle['price_per_day'];   // tổng gốc (không giảm)
    $discountRate  = 0.15;                                 // 15%
    $discountAmt   = round($originalTotal * $discountRate);
    $finalTotal    = $originalTotal - $discountAmt;
?>

<div class="container py-5">
    <div class="row g-5">

        <!-- ── Order Summary ─────────────────────────────────────────────── -->
        <div class="col-lg-5">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold m-0" style="color:var(--dark-slate)">Order Summary</h4>
                    <span class="discount-badge">
                        <i class="fas fa-tag"></i> 15% Online Discount
                    </span>
                </div>

                <img src="/car_rental/images/<?= htmlspecialchars($vehicle['image'] ?? 'default.jpg') ?>"
                     class="car-thumb mb-4">

                <div class="mb-4">
                    <h3 class="fw-bold mb-2"><?= htmlspecialchars($vehicle['name']) ?></h3>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="spec-badge"><i class="fas fa-users me-2"></i><?= $vehicle['seats'] ?> Seats</div>
                        <div class="spec-badge"><i class="fas fa-cog me-2"></i><?= $vehicle['transmission'] ?></div>
                        <div class="spec-badge"><i class="fas fa-gas-pump me-2"></i><?= $vehicle['fuel_type'] ?></div>
                    </div>
                </div>

                <!-- Pickup info -->
                <div class="p-3 rounded-4 border mb-4 shadow-sm" style="background:rgba(255,255,255,0.6)">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted small">Pickup Location</span>
                        <span class="fw-bold small text-end"><?= htmlspecialchars($_POST['pickup_location']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">Rental Period</span>
                        <span class="fw-bold small">
                            <?= date('d M', strtotime($_POST['pickup_date'])) ?> –
                            <?= date('d M', strtotime($_POST['return_date'])) ?>
                        </span>
                    </div>
                </div>

                <!-- Price breakdown -->
                <div class="price-section border-top pt-4">
                    <div class="d-flex justify-content-between text-muted mb-2">
                        <span>Daily Rate</span>
                        <span class="fw-semibold"><?= number_format($vehicle['price_per_day']) ?> VND</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted mb-2">
                        <span>Duration</span>
                        <span class="fw-semibold"><?= $days ?> day(s)</span>
                    </div>
                    <div class="d-flex justify-content-between text-muted mb-3">
                        <span>Subtotal</span>
                        <span class="original-price"><?= number_format($originalTotal) ?> VND</span>
                    </div>

                    <!-- Dòng giảm giá -->
                    <div class="saving-row d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <div class="fw-bold text-success" style="font-size:0.9rem;">
                                <i class="fas fa-circle-check me-1"></i> Online Booking Discount
                            </div>
                            <div class="text-muted" style="font-size:0.75rem;">Applied automatically — book online &amp; save!</div>
                        </div>
                        <span class="fw-bold text-success">− <?= number_format($discountAmt) ?> VND</span>
                    </div>

                    <!-- Tổng sau giảm -->
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold m-0">Total Amount</h5>
                        <div class="price-badge"><?= number_format($finalTotal) ?> VND</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Checkout Form ──────────────────────────────────────────────── -->
        <div class="col-lg-7">
            <form id="checkoutForm" action="/car_rental/public/confirm-booking" method="POST">

                <!-- Contact info -->
                <div class="glass-card p-4 mb-4">
                    <h5 class="fw-bold mb-4 text-primary">
                        <i class="fas fa-address-card me-2"></i>1. Contact Information
                    </h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-2">FULL NAME</label>
                            <div class="p-3 border rounded-3 bg-white shadow-sm fw-medium">
                                <?= htmlspecialchars($_SESSION['user']['name']) ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-muted mb-2">EMAIL ADDRESS</label>
                            <div class="p-3 border rounded-3 bg-white shadow-sm fw-medium">
                                <?= htmlspecialchars($_SESSION['user']['email']) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment method -->
                <div class="glass-card p-4 mb-4">
                    <h5 class="fw-bold mb-4 text-primary">
                        <i class="fas fa-shield-alt me-2"></i>2. Select Payment
                    </h5>
                    <div class="payment-stack d-flex flex-column gap-3">

                        <label class="payment-item d-flex align-items-center p-3 border">
                            <input type="radio" name="payment_method" value="apple_pay" class="d-none">
                            <div class="bg-dark text-white rounded-3 me-3 d-flex align-items-center justify-content-center" style="width:50px;height:35px;">
                                <i class="fab fa-apple fa-lg"></i>
                            </div>
                            <span class="fw-bold">Apple Pay</span>
                            <div class="ms-auto check-indicator d-none"><i class="fas fa-check-circle text-primary fa-lg"></i></div>
                        </label>

                        <label class="payment-item d-flex align-items-center p-3 border">
                            <input type="radio" name="payment_method" value="momo" class="d-none">
                            <div class="rounded-3 me-3 d-flex align-items-center justify-content-center" style="width:50px;height:35px;background:#A12270;">
                                <i class="fas fa-mobile-alt text-white"></i>
                            </div>
                            <span class="fw-bold">MoMo Wallet</span>
                            <div class="ms-auto check-indicator d-none"><i class="fas fa-check-circle text-primary fa-lg"></i></div>
                        </label>

                        <label class="payment-item d-flex align-items-center p-3 border active">
                            <input type="radio" name="payment_method" value="credit_card" class="d-none" checked>
                            <div class="bg-primary text-white rounded-3 me-3 d-flex align-items-center justify-content-center" style="width:50px;height:35px;">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <span class="fw-bold">Credit Card</span>
                            <div class="ms-auto check-indicator d-block"><i class="fas fa-check-circle text-primary fa-lg"></i></div>
                        </label>

                        <label class="payment-item d-flex align-items-center p-3 border">
                            <input type="radio" name="payment_method" value="cash" class="d-none">
                            <div class="bg-secondary text-white rounded-3 me-3 d-flex align-items-center justify-content-center" style="width:50px;height:35px;">
                                <i class="fas fa-money-bill-wave fa-lg"></i>
                            </div>
                            <span class="fw-bold">Pay at Pickup</span>
                            <div class="ms-auto check-indicator d-none"><i class="fas fa-check-circle text-primary fa-lg"></i></div>
                        </label>

                    </div>
                </div>

                <!-- Confirm -->
                <div class="glass-card p-4 border-0 shadow-lg" style="background:var(--dark-slate)">
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label text-white-50 small" for="terms">
                            I verify that rental details are correct and I accept the terms of service.
                        </label>
                    </div>
                    <button type="submit" class="btn-pay w-100">Confirm Booking</button>
                </div>

                <!-- Hidden fields: truyền toàn bộ dữ liệu đặt xe, total_price dùng giá đã giảm -->
                <?php foreach ($_POST as $key => $value): ?>
                    <?php if ($key === 'total_price') continue; // override bên dưới ?>
                    <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                <?php endforeach; ?>
                <input type="hidden" name="total_price" value="<?= $finalTotal ?>">

            </form>
        </div>

    </div>
</div>

<script>
    // Payment method selection
    document.querySelectorAll('.payment-item').forEach(item => {
        item.addEventListener('click', () => {
            document.querySelectorAll('.payment-item').forEach(i => {
                i.classList.remove('active');
                i.querySelector('.check-indicator').classList.replace('d-block', 'd-none');
            });
            item.classList.add('active');
            item.querySelector('input').checked = true;
            item.querySelector('.check-indicator').classList.replace('d-none', 'd-block');
        });
    });

    // Confirm dialog trước khi submit — dùng giá đã giảm
    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Confirm Booking?',
            html: `<div>Total after 15% online discount:<br><strong style="font-size:1.3rem;color:#2563eb"><?= number_format($finalTotal) ?> ₫</strong></div>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Yes, Finalize!'
        }).then(res => { if (res.isConfirmed) this.submit(); });
    });
</script>
</body>
</html>