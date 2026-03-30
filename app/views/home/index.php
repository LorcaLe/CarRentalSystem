<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PrivateHire Cars</title>
    <base href="http://localhost/car_rental/public/">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="assets/css/book.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/layout.css">

    <style>
        html, body, .main-container { overflow: visible !important; overflow-x: clip !important; }

        .car-layout { display: flex; align-items: flex-start; }

        .car-layout aside {
            position: sticky;
            top: 20px;
            max-height: 1000px;
            overflow-y: auto;
            z-index: 100;
        }

        #filter-form { position: static; width: 280px; }

        .filter {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 20px;
            border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .luxurySwiper .swiper-slide,
        .popularSwiper .swiper-slide { display: flex; justify-content: center; padding: 10px 0; }

        .vehicle-card { width: 100% !important; max-width: 400px; }

        /* Booking modal */
        .booking-modal-wrap {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .swal2-container {
            z-index: 99999 !important;
        }
        .booking-modal-box {
            position: relative;
            background: white;
            width: 450px;
            border-radius: 24px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            overflow: visible; /* thêm dòng này */
        }
        .booking-modal-box .flatpickr-wrapper {
            display: block;
            width: 100%;
        }
        .booking-modal-close {
            position: absolute; right: 20px; top: 20px;
            border: none; background: none; font-size: 1.5rem; cursor: pointer;
        }
        .booking-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
        .booking-field { margin-bottom: 15px; }
        .booking-label {
            display: block; font-size: 0.8rem;
            font-weight: 600; color: #64748b; margin-bottom: 5px;
        }
        .booking-input {
            width: 100%; padding: 12px;
            border-radius: 10px; border: 1px solid #e2e8f0;
            box-sizing: border-box;
        }
        .booking-input-sm { width: 100%; padding: 10px; border-radius: 10px; border: 1px solid #e2e8f0; box-sizing: border-box; }
        .booking-total {
            background: #f8fafc; padding: 15px; border-radius: 12px;
            margin-bottom: 20px; text-align: center; border: 1px solid #e2e8f0;
        }
        .booking-total-label { font-size: 0.75rem; color: #64748b; font-weight: 600; text-transform: uppercase; }
        .booking-total-amount { margin: 5px 0; color: #2563eb; font-weight: 800; font-size: 1.4rem; }
        .booking-total-days { color: #64748b; font-style: italic; font-size: 0.85rem; }
        .booking-submit {
            width: 100%; background: #1e293b; color: white;
            border: none; padding: 15px; border-radius: 12px;
            font-weight: 700; cursor: pointer;
        }

        /* Ad popup button */
        .luxury-popup { background: transparent !important; border: none !important; box-shadow: none !important; padding: 0 !important; }
        .luxury-popup img { width: 100%; border-radius: 20px; display: block; }
        .luxury-button {
            width: 520px !important; padding: 26px 40px !important;
            border: none !important; outline: none !important;
            border-radius: 70px !important; font-size: 20px !important;
            font-weight: 900 !important; letter-spacing: 1.5px !important;
            text-transform: uppercase !important; color: white !important;
            box-shadow: 0 15px 40px rgba(37,99,235,0.55);
            animation: btnPulse 1.8s infinite; transition: all .25s ease;
        }
        .luxury-button:hover { animation: none; transform: scale(1.06); }
        @keyframes btnPulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.1); } }

        body { font-family: 'Inter', sans-serif !important; }
    </style>
</head>

<body>
    <div class="top-trigger"></div>

    <nav class="topbar">
        <div class="logo" id="logo" style="cursor:pointer">PrivateHire Cars</div>
        <div class="nav-links" style="display:flex; align-items:center; gap:12px;">

            <?php if (!isset($_SESSION['user'])): ?>
                <a href="/car_rental/public/partner/login"
                style="font-weight:600; color:#3b6ef8; border:1.5px solid #3b6ef8;
                        padding:6px 18px; border-radius:20px; text-decoration:none;
                        font-size:0.88rem; white-space:nowrap;">
                    🤝 Partner
                </a>
            <?php endif; ?>

            <?php if (isset($_SESSION['user'])): ?>
                <div class="user-menu">
                    <div class="user-trigger">
                        <div class="avatar"><?= strtoupper(substr($_SESSION['user']['name'], 0, 1)) ?></div>
                        <span class="username"><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
                    </div>
                    <div class="user-dropdown">
                        <a href="/car_rental/public/profile">👤 My Profile</a>
                        <a href="/car_rental/public/my_booking">📄 My Bookings</a>
                        <a href="/car_rental/public/enquiry">💬 Support</a>
                        <?php if ($_SESSION['user']['role'] === 'partner'): ?>
                            <a href="/car_rental/public/partner/dashboard">🚗 Partner Dashboard</a>
                        <?php endif; ?>
                        <a href="/car_rental/public/logout" class="logout">🚪 Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/car_rental/app/views/auth/login.php">👤 Login</a>
            <?php endif; ?>

        </div>
    </nav>

    <header class="hero">
        <div class="hero-content">
            <h1>Drive Your Perfect Journey</h1>
            <p>Premium vehicles with flexible rental options</p>
        </div>

        <form id="searchForm">
            <div class="search-zone">
                <div class="search-card">
                    <div class="search-grid">
                        <div class="field location">
                            <label>Pickup Location</label>
                            <input type="text" name="location" id="s_location" placeholder="City or airport" required>
                        </div>
                        <div class="field">
                            <label>Pickup Date</label>
                            <input type="date" name="pickup_date" id="s_pdate" min="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="field">
                            <label>Pickup Time</label>
                            <input type="time" name="pickup_time" id="s_ptime" required>
                        </div>
                        <div class="field">
                            <label>Return Date</label>
                            <input type="date" name="return_date" id="s_rdate" min="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="field">
                            <label>Return Time</label>
                            <input type="time" name="return_time" id="s_rtime" required>
                        </div>
                        <button class="search-btn" type="submit">Find Vehicle</button>
                    </div>
                </div>
            </div>
        </form>
    </header>

    <main class="main-container">

        <section class="top-section">
            <h2 class="section-title"><i class="fas fa-fire" style="color:#ff4d4d"></i> Hot</h2>
            <div class="swiper popularSwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($popularCars as $car): ?>
                        <div class="swiper-slide">
                            <div class="vehicle-card" data-id="<?= $car['id'] ?>">
                                <img src="/car_rental/images/<?= htmlspecialchars($car['image']) ?>">
                                <div class="card-info">
                                    <h3><?= htmlspecialchars($car['name']) ?></h3>
                                    <p class="price"><?= number_format($car['price_per_day']) ?> VND</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </section>

        <section class="top-section">
            <h2 class="section-title"><i class="fas fa-crown" style="color:#ffcc00"></i> Luxury Car</h2>
            <div class="swiper luxurySwiper">
                <div class="swiper-wrapper">
                    <?php foreach ($luxuryCars as $car): ?>
                        <div class="swiper-slide">
                            <div class="vehicle-card luxury-border" data-id="<?= $car['id'] ?>">
                                <img src="/car_rental/images/<?= htmlspecialchars($car['image']) ?>">
                                <div class="card-info">
                                    <h3><?= htmlspecialchars($car['name']) ?></h3>
                                    <p class="price luxury-text"><?= number_format($car['price_per_day']) ?> VND</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </section>

        <hr class="my-5">

        <section class="car-layout">
            <aside>
                <form action="/car_rental/public/search-cars" method="GET" id="filter-form">
                    <div class="filter">
                        <h3>Filter</h3>

                        <h4>Price</h4>
                        <label class="radio"><input type="checkbox" name="price[]" value="0-800000"       onchange="searchCars()"><span></span> Below 800k</label>
                        <label class="radio"><input type="checkbox" name="price[]" value="800000-1000000" onchange="searchCars()"><span></span> 800k – 1M</label>
                        <label class="radio"><input type="checkbox" name="price[]" value="1000000-2000000" onchange="searchCars()"><span></span> 1M – 2M</label>
                        <label class="radio"><input type="checkbox" name="price[]" value="2000000-4000000" onchange="searchCars()"><span></span> Above 2M</label>

                        <h4>Seats</h4>
                        <label class="radio"><input type="checkbox" name="seats[]" value="4" onchange="searchCars()"><span></span> 4 seats</label>
                        <label class="radio"><input type="checkbox" name="seats[]" value="5" onchange="searchCars()"><span></span> 5 seats</label>
                        <label class="radio"><input type="checkbox" name="seats[]" value="7" onchange="searchCars()"><span></span> 7 seats</label>

                        <h4>Brand</h4>
                        <div class="brand-filter">
                            <?php
                            $brands = ['Vinfast','Honda','Toyota','Mazda','KIA','Suzuki','Hyundai','Mitsubishi','Ford','Audi','BMW','Lexus','Mercedes-Benz'];
                            $logos  = ['Vinfast-logo.jpg','Honda-logo.jpg','Toyota-logo.jpg','Mazda-logo.jpg','KIA-logo.jpg','Suzuki-logo.jpg','Hyundai-logo.jpg','Mitsubishi-logo.jpg','Ford-logo.jpg','Audi-logo.jpg','bmw-logo.jpg','Lexus-logo.jpg','Mer-logo.jpg'];
                            foreach ($brands as $i => $brand):
                            ?>
                                <label class="brand-option">
                                    <input type="checkbox" name="brand[]" value="<?= $brand ?>" onchange="searchCars()">
                                    <img src="/car_rental/images/<?= $logos[$i] ?>" alt="<?= $brand ?>">
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </form>
            </aside>

            <div class="main-vehicles" id="vehicle-list-section">
                <h2 class="section-title">All Cars</h2>
                <div class="vehicle-grid">
                    <?php foreach ($cars as $car): ?>
                        <div class="vehicle-card" data-id="<?= $car['id'] ?>">
                            <img src="/car_rental/images/<?= htmlspecialchars($car['image']) ?>">
                            <div class="card-info">
                                <h3><?= htmlspecialchars($car['name']) ?></h3>
                                <p><?= number_format($car['price_per_day']) ?> VND/day</p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="javascript:void(0)" onclick="searchCars(<?= $page - 1 ?>)" class="page-link">&laquo;</a>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="javascript:void(0)" onclick="searchCars(<?= $i ?>)"
                               class="page-link <?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>
                        <?php if ($page < $totalPages): ?>
                            <a href="javascript:void(0)" onclick="searchCars(<?= $page + 1 ?>)" class="page-link">&raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Chat Widget -->
    <div id="chat-wrapper">
        <div class="chat-bubble" onclick="toggleChat()">
            <i class="fab fa-facebook-messenger"></i>
        </div>
        <div class="chat-box" id="chatBox" style="display:none;">
            <div class="chat-header">
                <span>Customer Support</span>
                <button onclick="toggleChat()" style="background:none;border:none;color:white;font-size:18px;">&times;</button>
            </div>
            <div class="chat-content">
                <div id="chatDisplay" style="height:300px;overflow-y:auto;padding:10px;display:flex;flex-direction:column;gap:8px;"></div>
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="chat-input-area">
                        <textarea id="msgInput" placeholder="Aa" onkeydown="handleEnter(event)"></textarea>
                        <button onclick="sendChat()"><i class="fas fa-paper-plane"></i></button>
                    </div>
                <?php else: ?>
                    <div class="p-3 text-center">
                        <p class="small text-muted">Please <a href="/car_rental/public/login" class="text-primary fw-bold">Login</a> to chat</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Car Detail Modal -->
    <div class="car-modal" id="carModal" style="display:none;">
        <div class="modal-container">
            <div class="modal-grid">
                <div class="gallery">
                    <img id="mainImage" class="main-image">
                    <div class="thumb-list" id="thumbList"></div>
                </div>
                <div class="car-info">
                    <div class="brand-row">
                        <img id="brandLogo" class="brand-logo">
                        <h2 id="carName"></h2>
                    </div>
                    <div class="rating">⭐ <span id="carRating"></span></div>
                    <p class="price" id="carPrice"></p>
                    <div class="specs">
                        <div class="spec">🚗 <span id="carTransmission"></span></div>
                        <div class="spec">⛽ <span id="carFuel"></span></div>
                        <div class="spec">👥 <span id="carSeats"></span></div>
                    </div>
                    <p class="description" id="carDescription"></p>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center;">
                <button type="button" class="book-btn" onclick="openBookingModal()">BOOK NOW</button>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <?php if (isset($_SESSION['user'])): ?>
    <div id="profileModal" class="profile-modal" style="display:none;">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar"><?= strtoupper($_SESSION['user']['name'][0]) ?></div>
                <div>
                    <h2><?= htmlspecialchars($_SESSION['user']['name']) ?></h2>
                    <p><?= htmlspecialchars($_SESSION['user']['email']) ?></p>
                </div>
            </div>
            <div class="profile-body">
                <h3>Personal Info</h3>
                <div class="form-row">
                    <input type="text"  id="profileName"  placeholder="Name">
                    <input type="email" id="profileEmail" placeholder="Email">
                </div>
                <input type="text" id="profilePhone" placeholder="Phone Number">
                <button class="btn-primary" onclick="updateProfile()">Update Profile</button>
                <hr>
                <h3>Change Password</h3>
                <div class="form-row">
                    <input type="password" id="oldPassword" placeholder="Old password">
                    <input type="password" id="newPassword" placeholder="New password">
                </div>
                <button class="btn-primary" onclick="changePassword()">Update Password</button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Booking Modal -->
    <div class="booking-modal-wrap" id="bookingModal">
        <div class="booking-modal-box">
            <button class="booking-modal-close" onclick="closeModal()">&times;</button>
            <h3 style="font-weight:700;color:#1e293b;margin-bottom:20px;">Booking Details</h3>

            <?php if (isset($_SESSION['user'])): ?>
                <form action="/car_rental/public/checkout" method="POST" onsubmit="return validateBookingForm()">
                    <input type="hidden" name="vehicle_id" id="popup_vehicle_id">

                    <div class="booking-field">
                        <label class="booking-label">Pickup Location</label>
                        <input type="text" name="pickup_location" id="popup_location" class="booking-input" required placeholder="Enter city or airport">
                    </div>

                    <div class="booking-grid">
                        <div>
                            <label class="booking-label">Pickup Date</label>
                            <input type="text" name="pickup_date" id="popup_pdate" class="booking-input-sm" required placeholder="YYYY-MM-DD">
                        </div>
                        <div>
                            <label class="booking-label">Pickup Time</label>
                            <input type="time" name="pickup_time" id="popup_ptime" class="booking-input-sm" required oninput="calculateTotal()">
                        </div>
                    </div>

                    <div class="booking-grid">
                        <div>
                            <label class="booking-label">Return Date</label>
                            <input type="text" name="return_date" id="popup_rdate" class="booking-input-sm" required placeholder="YYYY-MM-DD">
                        </div>
                        <div>
                            <label class="booking-label">Return Time</label>
                            <input type="time" name="return_time" id="popup_rtime" class="booking-input-sm" required oninput="calculateTotal()">
                        </div>
                    </div>

                    <div class="booking-total">
                        <div class="booking-total-label">Total Estimated</div>
                        <div class="booking-total-amount" id="displayTotal">0 VND</div>
                        <div class="booking-total-days" id="dayDetail"></div>
                        <input type="hidden" name="total_price" id="checkoutTotalPrice">
                    </div>

                    <button type="submit" class="booking-submit">Confirm & Go to Payment</button>
                </form>
            <?php else: ?>
                <div style="text-align:center;padding:20px;">
                    <i class="fas fa-lock" style="font-size:3rem;color:#cbd5e1;margin-bottom:15px;"></i>
                    <h4 style="font-weight:700;">Login Required</h4>
                    <p style="color:#64748b;font-size:0.9rem;">Please log in to book your favorite car.</p>
                    <a href="/car_rental/public/login" style="display:block;background:#2563eb;color:white;text-decoration:none;padding:12px;border-radius:12px;font-weight:600;margin-top:15px;">Go to Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // ─── Global State ──────────────────────────────────────────────────────────
        let currentSelectedCarId = null;
        let currentCarPrice      = 0;

        // Flatpickr instances cho booking modal (khởi tạo khi mở modal)
        let fpPickup = null;
        let fpReturn = null;

        let currentBookedRanges = [];

        // ─── DOMContentLoaded ──────────────────────────────────────────────────────
        document.addEventListener("DOMContentLoaded", function () {

            // Swiper
            const swiperOpts = {
                slidesPerView: 3, spaceBetween: 20,
                navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
                observer: true, observeParents: true,
                breakpoints: { 0: { slidesPerView: 1 }, 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } },
                on: { init: () => setTimeout(() => window.dispatchEvent(new Event('resize')), 300) }
            };
            new Swiper(".popularSwiper", swiperOpts);
            new Swiper(".luxurySwiper",  swiperOpts);

            // Topbar hide on scroll
            const topbar = document.querySelector(".topbar");
            window.addEventListener("scroll",    () => topbar.classList.toggle("hide", window.pageYOffset > 80));
            document.addEventListener("mousemove", e => { if (e.clientY < 60) topbar.classList.remove("hide"); });
            document.getElementById("logo").onclick = () => window.location.href = "/car_rental/public/";

            // Close modals on backdrop click
            window.addEventListener("click", function (e) {
                ['carModal','bookingModal','profileModal'].forEach(id => {
                    const m = document.getElementById(id);
                    if (m && e.target === m) m.style.display = "none";
                });
                const chatBox     = document.getElementById('chatBox');
                const chatWrapper = document.getElementById('chat-wrapper');
                if (chatBox && chatBox.style.display === 'block' && !chatWrapper.contains(e.target)) {
                    chatBox.style.display = 'none';
                }
            });

            // Search bar submit → filter + scroll xuống
            document.getElementById("searchForm").addEventListener("submit", function (e) {
                e.preventDefault();

                const pDate = document.getElementById('s_pdate').value;
                const pTime = document.getElementById('s_ptime').value;
                const rDate = document.getElementById('s_rdate').value;
                const rTime = document.getElementById('s_rtime').value;

                // Validate nếu cả 4 trường đã có giá trị
                if (pDate && pTime && rDate && rTime) {
                    const start = new Date(`${pDate}T${pTime}`);
                    const end   = new Date(`${rDate}T${rTime}`);
                    if (end <= start) {
                        Swal.fire('Error', 'Return time must be after pickup time!', 'error');
                        return;
                    }
                }

                searchCars(1);
                setTimeout(() => document.getElementById("vehicle-list-section")?.scrollIntoView({ behavior: 'smooth' }), 200);
            });

            // Search bar date validation
            // Thêm vào trong DOMContentLoaded, sau dòng s_pdate.addEventListener('change', ...)
            document.getElementById('s_pdate').addEventListener('change', function () {
                document.getElementById('s_rdate').min = this.value;

                const now   = new Date();
                const today = now.toISOString().split('T')[0];
                const pTime = document.getElementById('s_ptime');

                if (this.value === today) {
                    // Làm tròn lên 30 phút gần nhất
                    const hh  = String(now.getHours()).padStart(2, '0');
                    const mm  = now.getMinutes() < 30 ? '30' : '00';
                    const hh2 = now.getMinutes() < 30 ? hh : String(now.getHours() + 1).padStart(2, '0');
                    pTime.min = `${hh2}:${mm}`;
                    if (pTime.value && pTime.value < pTime.min) pTime.value = pTime.min;
                } else {
                    pTime.removeAttribute('min');
                }
            });

            // Ad popup (chỉ hiện 1 lần mỗi session)
            if (!sessionStorage.getItem('shownPremiumAd')) {
                Swal.fire({
                    html: `<div style="text-align:left;padding:10px;font-family:'Inter',sans-serif;">
                               <div style="position:relative;overflow:hidden;border-radius:30px;margin-bottom:35px;box-shadow:0 20px 40px rgba(0,0,0,0.2);">
                                   <img src="/car_rental/assets/images/ad_popup12.png" style="width:100%;aspect-ratio:16/9;object-fit:cover;display:block;">
                               </div>
                           </div>`,
                    confirmButtonText: 'BOOK CAR & CLAIM DISCOUNT',
                    confirmButtonColor: '#3b82f6',
                    showCloseButton: true, width: '1200px',
                    background: '#ffffff04',
                    backdrop: `rgba(0,0,15,0.85) url("https://www.transparenttextures.com/patterns/black-linen-2.png") repeat`,
                    customClass: { popup: 'animate__animated animate__backInDown luxury-popup', confirmButton: 'luxury-button' }
                }).then(result => {
                    if (result.isConfirmed) {
                        const end = Date.now() + 15000;
                        const opts = { startVelocity: 55, spread: 360, ticks: 120, zIndex: 1000 };
                        const fire = (r, o) => confetti({ ...opts, ...o, particleCount: Math.floor(800 * r) });
                        fire(0.25, { spread: 40, startVelocity: 60 });
                        fire(0.2,  { spread: 80 });
                        fire(0.35, { spread: 140, decay: 0.91, scalar: 1.2 });
                        fire(0.1,  { spread: 200, startVelocity: 25, decay: 0.92 });
                        fire(0.1,  { spread: 220, startVelocity: 45, shapes: ['circle','star'], colors: ['#FFD700','#FFC300','#FF5733'] });
                        setTimeout(() => document.getElementById('vehicle-list-section')?.scrollIntoView({ behavior: 'smooth' }), 800);
                    }
                });
                sessionStorage.setItem('shownPremiumAd', 'true');
            }
        });

        // ─── Search & Filter ───────────────────────────────────────────────────────
        function searchCars(page = 1) {
            const data = new FormData();
            data.append("page", page);

            // Lấy thêm pickup/return date từ search bar nếu có
            ['location', 'pickup_date', 'return_date'].forEach(name => {
                const el = document.querySelector(`#searchForm input[name="${name}"]`);
                if (el) data.append(name, el.value);
            });
            ['price[]', 'seats[]', 'brand[]'].forEach(name => {
                document.querySelectorAll(`input[name='${name}']:checked`).forEach(el => data.append(name, el.value));
            });

            fetch("/car_rental/public/search-cars", { method: "POST", body: data })
                .then(res => res.json())
                .then(res => {
                    renderCars(res.cars);
                    renderPagination(res.totalPages, res.currentPage);
                    setTimeout(() => document.getElementById("vehicle-list-section")?.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
                });
        }

        function renderCars(cars) {
            const container = document.querySelector(".vehicle-grid");
            if (!container) return;
            container.innerHTML = cars.length
                ? cars.map(car => `
                    <div class="vehicle-card" data-id="${car.id}">
                        <img src="/car_rental/images/${car.image}">
                        <div class="card-info">
                            <h3>${car.name}</h3>
                            <p>${new Intl.NumberFormat().format(car.price_per_day)} VND/day</p>
                        </div>
                    </div>`).join('')
                : `<p style="grid-column:1/-1;text-align:center;">No vehicles found matching your criteria.</p>`;
        }

        function renderPagination(totalPages, currentPage) {
            const container = document.querySelector(".pagination");
            if (!container || totalPages <= 1) { if (container) container.innerHTML = ""; return; }

            let html = currentPage > 1 ? `<a href="javascript:void(0)" onclick="searchCars(${currentPage - 1})" class="page-link">&laquo;</a>` : "";
            for (let i = 1; i <= totalPages; i++) {
                html += `<a href="javascript:void(0)" onclick="searchCars(${i})" class="page-link ${i == currentPage ? 'active' : ''}">${i}</a>`;
            }
            if (currentPage < totalPages) html += `<a href="javascript:void(0)" onclick="searchCars(${currentPage + 1})" class="page-link">&raquo;</a>`;
            container.innerHTML = html;
        }

        // ─── Car Detail & Booking ──────────────────────────────────────────────────
        document.addEventListener("click", function (e) {
            const card = e.target.closest(".vehicle-card");
            if (!card) return;

            currentSelectedCarId = card.dataset.id;

        fetch("/car_rental/public/car-detail?id=" + currentSelectedCarId)
            .then(res => res.json())
            .then(data => {
                if (!data) return; // guard nếu xe không tồn tại

                currentCarPrice = data.price_per_day;
                window.currentCarLocation = data.location;

                document.getElementById("mainImage").src = "/car_rental/images/" + (data.image || 'default.jpg');

                const thumbs = document.getElementById("thumbList");
                if (thumbs) {
                    thumbs.innerHTML = "";
                    [data.image, data.image2, data.image3].filter(Boolean).forEach(img => {
                        const el    = document.createElement("img");
                        el.src      = "/car_rental/images/" + img;
                        el.onclick  = () => document.getElementById("mainImage").src = el.src;
                        thumbs.appendChild(el);
                    });
                }

                document.getElementById("carName").innerText         = data.name         || '';
                document.getElementById("carPrice").innerText        = new Intl.NumberFormat('vi-VN').format(data.price_per_day) + " VND/day";
                document.getElementById("carTransmission").innerText = data.transmission  || '—';
                document.getElementById("carFuel").innerText         = data.fuel_type     || '—';
                document.getElementById("carSeats").innerText        = (data.seats || '—') + " seats";
                document.getElementById("carDescription").innerText  = data.description   || 'No description available.';

                // Fix: Math.max(0, ...) để tránh repeat(-1) gây RangeError
                const rating = parseFloat(data.rating) || 0;
                document.getElementById("carRating").innerHTML = "⭐ ".repeat(Math.max(0, Math.floor(rating))) + " " + (rating || 'No rating');

                // brand_logo có thể null với xe partner mới
                const brandLogo = document.getElementById("brandLogo");
                if (data.brand_logo) {
                    brandLogo.src   = "/car_rental/images/" + data.brand_logo;
                    brandLogo.style.display = 'block';
                } else {
                    brandLogo.style.display = 'none';
                }

                document.getElementById("carModal").style.display = "flex";
            })
            .catch(err => console.error("Car detail error:", err));
        });

        function closeModal() {
            document.getElementById("carModal").style.display     = "none";
            document.getElementById("bookingModal").style.display = "none";
        }

        /**
         * Mở booking modal:
         * 1. Prefill ngày/giờ từ search bar
         * 2. Fetch các ngày đã bị đặt của xe → Flatpickr disable chúng
         */
        function openBookingModal() {
            document.getElementById("carModal").style.display     = "none";
            document.getElementById("bookingModal").style.display = "flex";

            document.getElementById('popup_vehicle_id').value = currentSelectedCarId;

            // Đọc thẳng từ search bar — luôn lấy được dù chưa submit
            const sPickupDate = document.getElementById('s_pdate')?.value || '';
            const sReturnDate = document.getElementById('s_rdate')?.value || '';

            document.getElementById('popup_location').value = document.getElementById('s_location')?.value || '';
            document.getElementById('popup_ptime').value    = document.getElementById('s_ptime')?.value   || '';
            document.getElementById('popup_rtime').value    = document.getElementById('s_rtime')?.value   || '';

            // Hủy instance cũ nếu có
            if (fpPickup) { fpPickup.destroy(); fpPickup = null; }
            if (fpReturn)  { fpReturn.destroy();  fpReturn  = null; }

            // Khởi tạo Flatpickr NGAY — lịch hiện ra liền, không chờ fetch
            fpPickup = flatpickr("#popup_pdate", {
                dateFormat: "Y-m-d", minDate: "today", disableMobile: true,
                static: true, // thêm dòng này
                defaultDate: sPickupDate || null,
                onChange: ([date]) => { if (fpReturn) fpReturn.set('minDate', date); calculateTotal(); }
            });

            fpReturn = flatpickr("#popup_rdate", {
                dateFormat: "Y-m-d", minDate: sPickupDate || "today", disableMobile: true,
                static: true, // thêm dòng này
                defaultDate: sReturnDate || null,
                onChange: () => calculateTotal(),
            });

            calculateTotal();

            // Sau đó fetch ngày đã bị đặt → cập nhật disable (không làm chậm việc mở modal)
            fetch("/car_rental/public/booked-dates?vehicle_id=" + currentSelectedCarId)
                .then(res => res.json())
                .then(ranges => {
                    currentBookedRanges = ranges; // thêm dòng này
                    if (!ranges.length) return;
                    const disabled = ranges.map(r => ({ from: r.from, to: r.to }));
                    fpPickup.set('disable', disabled);
                    fpReturn.set('disable',  disabled);
                })
                .catch(() => {});
        }

        function calculateTotal() {
            const pDate = document.getElementById('popup_pdate')?.value;
            const pTime = document.getElementById('popup_ptime')?.value;
            const rDate = document.getElementById('popup_rdate')?.value;
            const rTime = document.getElementById('popup_rtime')?.value;

            if (!pDate || !pTime || !rDate || !rTime) return;

            const start = new Date(`${pDate}T${pTime}`);
            const end   = new Date(`${rDate}T${rTime}`);

            if (end > start) {
                const days  = Math.ceil((end - start) / 86400000);
                const total = days * (currentCarPrice || 0);
                document.getElementById('displayTotal').innerText        = total.toLocaleString() + " VND";
                document.getElementById('checkoutTotalPrice').value      = total;
                document.getElementById('dayDetail').innerText           = `Total duration: ${days} day(s)`;
            } else {
                document.getElementById('displayTotal').innerText   = "0 VND";
                document.getElementById('dayDetail').innerText      = "Return time must be after pickup time";
                document.getElementById('checkoutTotalPrice').value = "";
            }
        }

        function validateBookingForm() {
            const pDate = document.getElementById('popup_pdate').value;
            const pTime = document.getElementById('popup_ptime').value;
            const rDate = document.getElementById('popup_rdate').value;
            const rTime = document.getElementById('popup_rtime').value;

            const start = new Date(`${pDate}T${pTime}`);
            const end   = new Date(`${rDate}T${rTime}`);
            
            const inputLocation = document.getElementById('popup_location').value.trim().toLowerCase();
            const carLocation   = (window.currentCarLocation || '').trim().toLowerCase();

            if (inputLocation && carLocation && !carLocation.includes(inputLocation) && !inputLocation.includes(carLocation)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Location Mismatch',
                    html: `This car is located in <strong>${window.currentCarLocation}</strong>.<br>Please update your pickup location.`,
                    confirmButtonColor: '#2563eb'
                });
                return false;
            }
            
            if (start < new Date()) {
                Swal.fire('Error', 'Pickup time cannot be in the past!', 'error');
                return false;
            }
            if (end <= start) {
                Swal.fire('Error', 'Return time must be strictly after pickup time!', 'error');
                return false;
            }

            // Kiểm tra trùng lịch với các đơn đã đặt
            const overlap = currentBookedRanges.some(r => {
                const bookedFrom = new Date(r.from);
                const bookedTo   = new Date(r.to);
                // Trùng nếu khoảng chọn giao với khoảng đã đặt
                return !(end <= bookedFrom || start >= bookedTo);
            });

            if (overlap) {
                Swal.fire('Unavailable', 'This car is already booked for the selected dates. Please choose different dates.', 'warning');
                return false;
            }

            return true;
        }

        // ─── Chat ──────────────────────────────────────────────────────────────────
        function toggleChat() {
            const chatBox = document.getElementById('chatBox');
            if (!chatBox) return;
            const isHidden = !chatBox.style.display || chatBox.style.display === 'none';
            chatBox.style.display = isHidden ? 'block' : 'none';
            if (isHidden) loadMessages();
        }

        function loadMessages() {
            const display = document.getElementById('chatDisplay');
            if (!display) return;
            fetch('/car_rental/public/get-enquiries')
                .then(res => res.json())
                .then(data => {
                    display.innerHTML = data.map(item => {
                        let html = '';
                        if (item.user_msg?.trim())  html += `<div style="display:flex;justify-content:flex-end;margin-bottom:12px;"><div style="background:#0084ff;color:white;padding:8px 15px;border-radius:18px 18px 4px 18px;max-width:80%;font-size:14px;">${item.user_msg}<div style="font-size:10px;opacity:0.7;text-align:right;margin-top:4px;">${item.time}</div></div></div>`;
                        if (item.admin_rep?.trim()) html += `<div style="display:flex;justify-content:flex-start;margin-bottom:12px;"><div style="background:#e4e6eb;color:#050505;padding:8px 15px;border-radius:18px 18px 18px 4px;max-width:80%;font-size:14px;">${item.admin_rep}<div style="font-size:10px;opacity:0.5;margin-top:4px;">Support Team</div></div></div>`;
                        return html;
                    }).join('');
                    display.scrollTop = display.scrollHeight;
                })
                .catch(console.error);
        }

        function handleEnter(e) {
            if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendChat(); }
        }

        function sendChat() {
            const input = document.getElementById('msgInput');
            if (!input?.value.trim()) return;
            fetch('/car_rental/public/send-enquiry', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'message=' + encodeURIComponent(input.value.trim())
            })
            .then(res => res.json())
            .then(data => { if (data.success) { input.value = ''; loadMessages(); } })
            .catch(console.error);
        }
    </script>
</body>
<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
</html>