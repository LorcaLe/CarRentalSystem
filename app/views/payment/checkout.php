<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Checkout - CarRental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/car_rental/assets/css/payment.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="checkout-wrapper">
    <div class="container" style="max-width: 1100px;">
        <h2 class="mb-4 fw-bold text-center" style="color: #111827;">Checkout</h2>
        
        <div class="checkout-grid">
            
            <div class="order-summary">
                <div class="checkout-card sticky-top" style="top: 20px;">
                    <h3 class="section-title">Order Summary</h3>
                    
                    <img src="/car_rental/images/<?= htmlspecialchars($vehicle['image'] ?? 'default.jpg') ?>" alt="Car" class="car-image-summary shadow-sm">
                    
                    <h4 class="text-center fw-bold mb-4" style="color: #1f2937;"><?= htmlspecialchars($vehicle['name'] ?? 'Unknown Car') ?></h4>

                    <div class="summary-item">
                        <span>Pickup Location</span>
                        <strong><?= htmlspecialchars($_POST['pickup_location'] ?? '') ?></strong>
                    </div>
                    <div class="summary-item">
                        <span>Pickup Date</span>
                        <strong><?= htmlspecialchars($_POST['pickup_date'] ?? '') ?> (<?= htmlspecialchars($_POST['pickup_time'] ?? '') ?>)</strong>
                    </div>
                    <div class="summary-item">
                        <span>Return Date</span>
                        <strong><?= htmlspecialchars($_POST['return_date'] ?? '') ?> (<?= htmlspecialchars($_POST['return_time'] ?? '') ?>)</strong>
                    </div>
                    
                    <div class="total-row">
                        <span>Total Amount</span>
                        <span class="total-price"><?= number_format($_POST['total_price'] ?? 0) ?> VND</span>
                    </div>
                </div>
            </div>

            <div class="payment-section">
                <form action="/car_rental/public/confirm-booking" method="POST">
                    
                    <input type="hidden" name="vehicle_id" value="<?= htmlspecialchars($_POST['vehicle_id'] ?? '') ?>">
                    <input type="hidden" name="pickup_location" value="<?= htmlspecialchars($_POST['pickup_location'] ?? '') ?>">
                    <input type="hidden" name="pickup_date" value="<?= htmlspecialchars($_POST['pickup_date'] ?? '') ?>">
                    <input type="hidden" name="pickup_time" value="<?= htmlspecialchars($_POST['pickup_time'] ?? '') ?>">
                    <input type="hidden" name="return_date" value="<?= htmlspecialchars($_POST['return_date'] ?? '') ?>">
                    <input type="hidden" name="return_time" value="<?= htmlspecialchars($_POST['return_time'] ?? '') ?>">
                    <input type="hidden" name="total_price" value="<?= htmlspecialchars($_POST['total_price'] ?? 0) ?>">

                    <div class="checkout-card">
                        <h3 class="section-title">1. Personal Information</h3>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="modern-label">Full Name</label>
                                <input type="text" class="form-control-luxury" value="<?= htmlspecialchars($_SESSION['user']['name'] ?? 'Guest') ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="modern-label">Email / Phone</label>
                                <input type="text" class="form-control-luxury" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? $_SESSION['user']['phone'] ?? '') ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <h3 class="section-title">2. Payment Method</h3>
                        
                        <div class="payment-method-list">
                            <label class="payment-card active">
                                <input type="radio" name="payment_method" value="credit_card" checked>
                                <svg class="method-icon-svg" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="32" height="22" rx="4" fill="#1f2937"/>
                                    <rect x="0" y="5" width="32" height="4" fill="#4b5563"/>
                                    <circle cx="24" cy="16" r="3" fill="#ffffff" opacity="0.5"/>
                                </svg>
                                <span class="method-title">Credit Card</span>
                            </label>

                            <label class="payment-card">
                                <input type="radio" name="payment_method" value="momo">
                                <svg class="method-icon-svg" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="32" height="32" rx="6" fill="#A12270"/>
                                    <path d="M16 11.5L20 16L16 20.5L12 16L16 11.5Z" fill="white"/>
                                </svg>
                                <span class="method-title">Momo Wallet</span>
                            </label>

                            <label class="payment-card">
                                <input type="radio" name="payment_method" value="apple_pay">
                                <svg class="method-icon-svg" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="32" height="32" rx="6" fill="#000000"/>
                                    <path d="M16 23C13.5 23 11 21.5 11 18C11 14 13.5 13 16 13C17.5 13 19 13.5 20 14.5M16 13C18 13 20 14.5 20 18C20 21.5 18 23 16 23M16 10.5C14.5 10.5 13 11.5 13 13M16 10.5C17.5 10.5 19 11.5 19 13" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                                <span class="method-title">Apple Pay</span>
                            </label>

                            <label class="payment-card">
                                <input type="radio" name="payment_method" value="cash">
                                <div style="font-size: 1.8rem;">💵</div>
                                <span class="method-title">Pay at Pickup</span>
                            </label>
                        </div>

                        <button type="submit" class="btn-pay">Complete Booking</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    // Xử lý Active State cho Payment Cards
    const cards = document.querySelectorAll('.payment-card');
    cards.forEach(card => {
        card.addEventListener('click', () => {
            cards.forEach(c => c.classList.remove('active')); // Xóa active cũ
            card.classList.add('active'); // Thêm active mới
            card.querySelector('input').checked = true; // Check radio ẩn
        });
    });
</script>
</body>
</html>