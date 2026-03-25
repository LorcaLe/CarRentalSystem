<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarRental</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="/car_rental/public/assets/css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 mb-4">
    <div class="topbar">

    <div class="logo" name="logo" id="logo">CarRental</div>

    <div class="nav-links">
    <?php if(isset($_SESSION['user'])){ ?>

    <div class="user-menu">

    <div class="user-trigger">

    <div class="avatar">
    <?= strtoupper(substr($_SESSION['user']['name'],0,1)) ?>
    </div>

    <span class="username">
    <?= $_SESSION['user']['name'] ?>
    </span>

    </div>

    <div class="user-dropdown">

    <a href="/car_rental/public/profile">
    👤 My Profile</a>

    <a href="/car_rental/public/my_booking">
    📄 My Bookings
    </a>

    <a href="/car_rental/public/enquiry">
    💬 Support
    </a>

    <a href="/car_rental/public/register-car">
    🚗 Register Car
    </a>
    
    <a href="/car_rental/public/logout" class="logout">
    🚪 Logout
    </a>

    </div>

    </div>
    <?php } else { ?>

    <a href="/car_rental/app/views/auth/login.php">👤</a>

    <?php } ?>
    </div>

    </div>
</nav>

<script>
document.addEventListener("click", function(e) {
    // Kiểm tra nếu click vào phần tử có id là logo
    if (e.target.id === "logo") {
        window.location.href = "/car_rental/public/";
    }
});
</script>