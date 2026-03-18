<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .success-card { background: white; padding: 50px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); text-align: center; max-width: 500px; }
        .checkmark { font-size: 80px; color: #22c55e; }
        .btn-custom { background: #2563eb; color: white; border-radius: 10px; padding: 12px 25px; text-decoration: none; display: inline-block; margin-top: 20px; transition: 0.3s; }
        .btn-custom:hover { background: #1d4ed8; transform: translateY(-2px); color: white; }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="checkmark">✔</div>
        <h2 class="fw-bold mt-3">Payment Successful!</h2>
        <p class="text-muted">Thank you for choosing Luxury Car Rental. Your booking has been confirmed.</p>
        <p class="small text-secondary">You will be redirected to your bookings in <span id="countdown">3</span> seconds...</p>
        <a href="/car_rental/public/my-booking" class="btn-custom">Go to My Bookings Now</a>
    </div>

    <script>
        let seconds = 3;
        setInterval(() => {
            seconds--;
            document.getElementById('countdown').innerText = seconds;
            if (seconds <= 0) window.location.href = "/car_rental/public/my_booking";
        }, 1000);
    </script>
</body>
</html>