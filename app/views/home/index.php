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

<div class="top-trigger"></div>
<!-- TOPBAR -->

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

<a onclick="openProfile()">
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


<!-- HERO -->

<div class="hero">

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
<input type="text" name="location" placeholder="City or airport">
</div>

<div class="field">
<label>Pickup Date</label>
<input type="date" name="pickup_date">
</div>

<div class="field">
<label>Pickup Time</label>
<input type="time" name="pickup_time" step="900">
</div>

<div class="field">
<label>Return Date</label>
<input type="date" name="return_date">
</div>

<div class="field">
<label>Return Time</label>
<input type="time" name="return_time" step="900">
</div>

<button class="search-btn" type="submit">
Find Vehicle
</button>

</div>

</div>

</div>

</div>

</form>

<section class="car-layout">

<!-- FILTER -->
<div class="filter">

<h3>Filter</h3>

<h4>Price</h4>

<label class="radio">
<input type="checkbox" name="price[]" value="0-800000">
<span></span>
Below 800k
</label>

<label class="radio">
<input type="checkbox" name="price[]" value="800000-1000000">
<span></span>
800k - 1M
</label>

<label class="radio">
<input type="checkbox" name="price[]" value="1000000-2000000">
<span></span>
1M - 2M
</label>

<label class="radio">
<input type="checkbox" name="price[]" value="2000000-4000000">
<span></span>
Above 2M
</label>

<h4>Seats</h4>

<label class="radio">
<input type="checkbox" name="seats[]" value="4">
<span></span>
4 seats
</label>

<label class="radio">
<input type="checkbox" name="seats[]" value="5">
<span></span>
5 seats
</label>

<label class="radio">
<input type="checkbox" name="seats[]" value="7">
<span></span>
7 seats
</label>

<h4>Brand</h4>

<div class="brand-filter">

<label class="brand-option">
<input type="checkbox" name="brand[]" value="Vinfast">
<img src="/car_rental/images/Vinfast-logo.jpg">
</label>

<label class="brand-option">
<input type="checkbox" name="brand[]" value="Honda">
<img src="/car_rental/images/Honda-logo.jpg">
</label>

<label class="brand-option">
<input type="checkbox" name="brand[]" value="Toyota">
<img src="/car_rental/images/Toyota-logo.jpg">
</label>

<label class="brand-option">
<input type="checkbox" name="brand[]" value="Mazda">
<img src="/car_rental/images/Mazda-logo.jpg">
</label>

<label class="brand-option">
<input type="checkbox" name="brand[]" value="KIA">
<img src="/car_rental/images/KIA-logo.jpg">
</label>

<label class="brand-option">
<input type="checkbox" name="brand[]" value="Suzuki">
<img src="/car_rental/images/Suzuki-logo.jpg">
</label>

<label class="brand-option">
<input type="checkbox" name="brand[]" value="Hyundai">
<img src="/car_rental/images/Hyundai-logo.jpg">
</label>

<label class="brand-option">
<input type="checkbox" name="brand[]" value="Mitsubishi">
<img src="/car_rental/images/Mitsubishi-logo.jpg">
</label>

<label class="brand-option">
<input type="checkbox" name="brand[]" value="Ford">
<img src="/car_rental/images/Ford-logo.jpg">
</label>

<label class="brand-option">
<input type="checkbox" name="brand[]" value="Audi">
<img src="/car_rental/images/Audi-logo.jpg">
</label>

<label class="brand-option">
<input type="checkbox" name="brand[]" value="BMW">
<img src="/car_rental/images/bmw-logo.jpg">
</label>

<label class="brand-option">
<input type="checkbox" name="brand[]" value="Lexus">
<img src="/car_rental/images/Lexus-logo.jpg">
</label>

<label class="brand-option">
<input type="checkbox" name="brand[]" value="Mercedes-Benz">
<img src="/car_rental/images/Mer-logo.jpg">
</label>

</div>

</div>


<!-- VEHICLES -->
<div class="vehicle-grid">
<?php foreach($cars as $car){ ?>

<div class="vehicle-card" data-id="<?= $car['id'] ?>">

<img src="/car_rental/images/<?= $car['image'] ?>">

<h3><?= $car['name'] ?></h3>

<p><?= number_format($car['price_per_day']) ?> VND/day</p>

</div>

<?php } ?>
</div>
</section>

<div id="chat-wrapper">
    <div class="chat-bubble" onclick="toggleChat()">
        <i class="fab fa-facebook-messenger"></i>
    </div>

    <div class="chat-box" id="chatBox" style="display: none;">
        <div class="chat-header">
            <span>Customer Support</span>
            <button onclick="toggleChat()" style="background:none; border:none; color:white; font-size:18px;">&times;</button>
        </div>
        
        <div class="chat-content">
            <div id="chatDisplay" style="height: 300px; overflow-y: auto; padding: 10px; display: flex; flex-direction: column; gap: 8px;">
            </div>
            
            <?php if(isset($_SESSION['user'])): ?>
                <div class="chat-input-area">
                    <textarea id="msgInput" placeholder="Aa" onkeydown="handleEnter(event)"></textarea>
                    
                    <button onclick="sendChat()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            <?php else: ?>
                <div class="p-3 text-center">
                    <p class="small text-muted">Please <a href="/car_rental/public/login" class="text-primary fw-bold">Login</a> to chat</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="car-modal" id="carModal">

<div class="modal-container">

<div class="modal-grid">

<!-- LEFT : IMAGE GALLERY -->

<div class="gallery">

<img id="mainImage" class="main-image">

<div class="thumb-list" id="thumbList"></div>

</div>

<!-- RIGHT : CAR INFO -->

<div class="car-info">

<div class="brand-row">

<img id="brandLogo" class="brand-logo">

<h2 id="carName"></h2>

</div>

<div class="rating">
⭐ <span id="carRating"></span>
</div>

<p class="price" id="carPrice"></p>

<div class="specs">

<div class="spec">
🚗 <span id="carTransmission"></span>
</div>

<div class="spec">
⛽ <span id="carFuel"></span>
</div>

<div class="spec">
👥 <span id="carSeats"></span>
</div>

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

<div id="profileModal" class="profile-modal">
<div class="profile-card">

<div class="profile-header">

<div class="profile-avatar">
<?= strtoupper($_SESSION['user']['name'][0]) ?>
</div>

<div>

<h2><?= $_SESSION['user']['name'] ?></h2>
<p><?= $_SESSION['user']['email'] ?></p>

</div>

</div>


<div class="profile-body">

<h3>Personal Info</h3>

<div class="form-row">

<input type="text" id="profileName" placeholder="Name">

<input type="email" id="profileEmail" placeholder="Email">

</div>

<input type="text" id="profilePhone" placeholder="Phone Number">

<button class="btn-primary" onclick="updateProfile()">
Update Profile
</button>

<hr>

<h3>Change Password</h3>

<div class="form-row">

<input type="password"
id="oldPassword"
placeholder="Old password">

<input type="password"
id="newPassword"
placeholder="New password">

</div>

<button class="btn-primary" onclick="changePassword()">
Update Password
</button>

</div>

</div>

</div>

<!-- Book Modal -->

<div class="car-modal" id="bookingModal" style="display: none; align-items: center; justify-content: center;">
    <div class="modern-modal-container">
        <div class="modern-modal-header">
            <h3 class="modern-modal-title">Booking Details</h3>
        </div>

        <div class="modern-modal-body">
            <form action="/car_rental/public/checkout" method="POST">
                <input type="hidden" name="vehicle_id" id="popup_vehicle_id">

                <div class="modern-input-group">
                    <label class="modern-label">Pickup Location</label>
                    <input type="text" name="pickup_location" id="popup_location" class="modern-input" required placeholder="Enter city or airport">
                </div>

                <div class="modern-grid">
                    <div class="modern-input-group">
                        <label class="modern-label">Pickup Date</label>
                        <input type="date" name="pickup_date" id="popup_pdate" class="modern-input" required oninput="calculateTotal()">
                    </div>
                    <div class="modern-input-group">
                        <label class="modern-label">Pickup Time</label>
                        <input type="time" name="pickup_time" id="popup_ptime" class="modern-input" required oninput="calculateTotal()">
                    </div>
                </div>

                <div class="modern-grid">
                    <div class="modern-input-group">
                        <label class="modern-label">Return Date</label>
                        <input type="date" name="return_date" id="popup_rdate" class="modern-input" required oninput="calculateTotal()">
                    </div>
                    <div class="modern-input-group">
                        <label class="modern-label">Return Time</label>
                        <input type="time" name="return_time" id="popup_rtime" class="modern-input" required oninput="calculateTotal()">
                    </div>
                </div>

                <div style="background: #f8fafc; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center; border: 1px solid #e2e8f0;">
                    <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">TOTAL ESTIMATED</span>
                    <h3 id="displayTotal" style="margin: 5px 0 2px 0; color: #2563eb; font-weight: 700;">0 VND</h3>
                    
                    <small id="dayDetail" style="color: #64748b; font-style: italic; font-size: 0.8rem;"></small>
                    
                    <input type="hidden" name="total_price" id="checkoutTotalPrice">
                </div>

                <button type="submit" class="modern-btn">Confirm & Go to Payment</button>
            </form>
        </div>
    </div>
</div>

<script>

document.addEventListener("DOMContentLoaded", function(){

const topbar = document.querySelector(".topbar");

let lastScroll = 0;

window.addEventListener("scroll", function(){

let currentScroll = window.pageYOffset;

if(currentScroll > 80){

topbar.classList.add("hide");

}else{

topbar.classList.remove("hide");

}

});

/* Hover vào đầu trang */

document.addEventListener("mousemove", function(e){

if(e.clientY < 60){

topbar.classList.remove("hide");

}

});

});

// 1. Hàm đóng/mở và Load tin nhắn
function toggleChat() {
    const chatBox = document.getElementById('chatBox');
    if (chatBox.style.display === 'none' || chatBox.style.display === '') {
        chatBox.style.display = 'block';
        loadMessages(); // Gọi hàm load tin nhắn từ server
    } else {
        chatBox.style.display = 'none';
    }
}

// 2. Hàm lấy tin nhắn từ Database (getEnquiries)
function loadMessages() {
    const display = document.getElementById('chatDisplay');
    if (!display) return;

    fetch('/car_rental/public/get-enquiries')
    .then(res => res.json())
    .then(data => {
        let html = '';
        
        data.forEach(item => {
            // Hiển thị tin nhắn của Người dùng (User) - Nằm bên PHẢI, màu XANH
            if (item.user_msg && item.user_msg.trim() !== "") {
                html += `
                    <div style="display: flex; justify-content: flex-end; margin-bottom: 12px;">
                        <div style="background: #0084ff; color: white; padding: 8px 15px; border-radius: 18px 18px 4px 18px; max-width: 80%; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            ${item.user_msg}
                            <div style="font-size: 10px; opacity: 0.7; text-align: right; margin-top: 4px;">${item.time}</div>
                        </div>
                    </div>`;
            }

            // Hiển thị tin nhắn của Admin (Reply) - Nằm bên TRÁI, màu XÁM
            if (item.admin_rep && item.admin_rep.trim() !== "") {
                html += `
                    <div style="display: flex; justify-content: flex-start; margin-bottom: 12px;">
                        <div style="background: #e4e6eb; color: #050505; padding: 8px 15px; border-radius: 18px 18px 18px 4px; max-width: 80%; font-size: 14px;">
                            ${item.admin_rep}
                            <div style="font-size: 10px; opacity: 0.5; margin-top: 4px;">Support Team</div>
                        </div>
                    </div>`;
            }
        });

        display.innerHTML = html;
        // Tự động cuộn xuống tin nhắn cuối cùng
        display.scrollTop = display.scrollHeight;
    })
    .catch(err => console.error("Lỗi tải tin nhắn:", err));
}

// 3. Hàm gửi tin nhắn (sendEnquiry)
function handleEnter(event) {
    // Nếu nhấn Enter mà không giữ Shift
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault(); // Không cho xuống dòng
        sendChat(); // Gọi hàm gửi
    }
}

function sendChat() {
    const input = document.getElementById('msgInput');
    const message = input.value.trim();

    if (!message) return;

    // Gửi dữ liệu
    fetch('/car_rental/public/send-enquiry', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'message=' + encodeURIComponent(message)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            input.value = ''; // Xóa nội dung ô nhập
            loadMessages();   // Load lại khung chat để hiện tin mới
        }
    })
    .catch(err => console.error("Lỗi:", err));
}

// Hàm bổ trợ để tự động cuộn (dùng trong cả loadMessages)
function scrollToBottom() {
    const display = document.getElementById('chatDisplay');
    display.scrollTop = display.scrollHeight;
}

document.addEventListener("click",function(e){

let card=e.target.closest(".vehicle-card");

if(!card) return;

let id=card.dataset.id;


fetch("/car_rental/public/car-detail?id="+id)

.then(res=>res.json())

.then(data=>{

/* MAIN IMAGE */

document.getElementById("mainImage").src=
"/car_rental/images/"+data.image;


/* THUMB GALLERY */

let thumbs=document.getElementById("thumbList");

thumbs.innerHTML="";

let images=[data.image,data.image2,data.image3];

images.forEach(img=>{

if(!img) return;

let el=document.createElement("img");

el.src="/car_rental/images/"+img;

el.onclick=function(){
document.getElementById("mainImage").src=this.src;
}

thumbs.appendChild(el);

});


/* INFO */

document.getElementById("carName").innerText=data.name;

document.getElementById("carPrice").innerText=
new Intl.NumberFormat().format(data.price_per_day)+" VND/day";

let rating=data.rating || 0;

document.getElementById("carRating").innerHTML =
"⭐ ".repeat(Math.floor(rating)-1) + " " + rating;

document.getElementById("carTransmission").innerText=data.transmission;

document.getElementById("carFuel").innerText=data.fuel_type;

document.getElementById("carSeats").innerText=data.seats+" seats";

document.getElementById("carDescription").innerText=data.description;


/* BRAND LOGO */

document.getElementById("brandLogo").src=
"/car_rental/images/"+data.brand_logo;

document.getElementById("bookNowBtn").href = "/car_rental/public/booking-form?vehicle_id=" + data.id;

/* SHOW MODAL */

document.getElementById("carModal").style.display="flex";

});

});


function closeModal(){

document.getElementById("carModal").style.display="none";

}


/* CLICK OUTSIDE CLOSE */

window.addEventListener("click",function(e){

const profileModal=document.getElementById("profileModal");
const carModal=document.getElementById("carModal");
const logo=document.getElementById("logo");
/* close profile modal */

if(e.target===profileModal){
profileModal.style.display="none";
}

/* close car modal */

if(e.target===carModal){
carModal.style.display="none";
}

if(e.target===logo){
window.location.href="/car_rental/public/";
}
});

function openProfile(){

fetch("/car_rental/public/profile")

.then(res=>res.json())

.then(data=>{

document.getElementById("profileName").value=data.name;
document.getElementById("profileEmail").value=data.email;
document.getElementById("profilePhone").value=data.phone;

document.getElementById("profileModal").style.display="flex";

});

}

function updateProfile(){

let name=document.getElementById("profileName").value;
let email=document.getElementById("profileEmail").value;
let phone=document.getElementById("profilePhone").value;

fetch("/car_rental/public/update-profile",{

method:"POST",

headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:`name=${name}&email=${email}&phone=${phone}`

})
.then(res=>res.text())
.then(data=>alert(data));

}

function changePassword(){

let oldPass=document.getElementById("oldPassword").value;
let newPass=document.getElementById("newPassword").value;

fetch("/car_rental/public/change-password",{

method:"POST",

headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:`old_password=${oldPass}&new_password=${newPass}`

})
.then(res=>res.text())
.then(data=>alert(data));

}

function closeProfile(){

document.getElementById("profileModal").style.display="none";

}

function searchCars(){

let data=new FormData();

/* search bar */

let location=document.querySelector("input[name=location]");
let pickup=document.querySelector("input[name=pickup_date]");
let returnDate=document.querySelector("input[name=return_date]");

if(location) data.append("location",location.value);
if(pickup) data.append("pickup_date",pickup.value);
if(returnDate) data.append("return_date",returnDate.value);

/* filter price */

document.querySelectorAll("input[name='price[]']:checked")
.forEach(el=>data.append("price[]",el.value));

/* seats */

data.delete("seats[]"); 
document.querySelectorAll("input[name='seats[]']:checked")
    .forEach(el => data.append("seats[]", el.value));

/* brand */

document.querySelectorAll("input[name='brand[]']:checked")
.forEach(el=>data.append("brand[]",el.value));

fetch("/car_rental/public/search-cars",{

method:"POST",
body:data

})
.then(res=>res.json())
.then(renderCars);

}

/* search button */

document.getElementById("searchForm")
.addEventListener("submit",function(e){

e.preventDefault();

searchCars();

});

/* filter change */

document
.querySelectorAll(".filter input")
.forEach(el=>{

el.addEventListener("change",searchCars);

});

function renderCars(cars){

let container=document.querySelector(".vehicle-grid");

container.innerHTML="";

cars.forEach(car=>{

container.innerHTML+=`

<div class="vehicle-card" data-id="${car.id}" onclick="openCarModal(${car.id})">

<img src="/car_rental/images/${car.image}">

<h3>${car.name}</h3>

<p>${new Intl.NumberFormat().format(car.price_per_day)} VND/day</p>

</div>

`;

});

}

function openCarModal(id){

fetch("/car_rental/public/car-detail?id="+id)

.then(res=>res.json())

.then(data=>{

document.getElementById("mainImage").src=
"/car_rental/images/"+data.image;

document.getElementById("carName").innerText=data.name;

document.getElementById("carPrice").innerText=
new Intl.NumberFormat().format(data.price_per_day)+" VND/day";

document.getElementById("carModal").style.display="flex";

});

}

/* Open booking modal from car detail */

// Biến toàn cục để lưu ID chiếc xe đang được xem

/* 1. SỰ KIỆN CLICK MỞ XE (Dành cho xe hiển thị sẵn ở trang chủ) */
document.addEventListener("click", function(e){
    let card = e.target.closest(".vehicle-card");
    if(!card) return;

    let id = card.dataset.id;
    currentSelectedCarId = id; // LƯU ID XE

    fetch("/car_rental/public/car-detail?id="+id)
    .then(res => res.json())
    .then(data => {
        document.getElementById("mainImage").src = "/car_rental/images/"+data.image;
        
        let thumbs = document.getElementById("thumbList");
        thumbs.innerHTML = "";
        let images = [data.image, data.image2, data.image3];
        images.forEach(img => {
            if(!img) return;
            let el = document.createElement("img");
            el.src = "/car_rental/images/"+img;
            el.onclick = function(){ document.getElementById("mainImage").src=this.src; }
            thumbs.appendChild(el);
        });

        document.getElementById("carName").innerText = data.name;
        document.getElementById("carPrice").innerText = new Intl.NumberFormat().format(data.price_per_day)+" VND/day";
        
        let rating = data.rating || 0;
        document.getElementById("carRating").innerHTML = "⭐ ".repeat(Math.floor(rating)-1) + " " + rating;
        document.getElementById("carTransmission").innerText = data.transmission;
        document.getElementById("carFuel").innerText = data.fuel_type;
        document.getElementById("carSeats").innerText = data.seats+" seats";
        document.getElementById("carDescription").innerText = data.description;
        document.getElementById("brandLogo").src = "/car_rental/images/"+data.brand_logo;

        document.getElementById("carModal").style.display = "flex";
    });
});

/* 2. HÀM MỞ XE TỪ KẾT QUẢ TÌM KIẾM */
function openCarModal(id){
    currentSelectedCarId = id; // LƯU ID XE

    fetch("/car_rental/public/car-detail?id="+id)
    .then(res => res.json())
    .then(data => {
        document.getElementById("mainImage").src = "/car_rental/images/"+data.image;
        document.getElementById("carName").innerText = data.name;
        document.getElementById("carPrice").innerText = new Intl.NumberFormat().format(data.price_per_day)+" VND/day";
        document.getElementById("carModal").style.display = "flex";
    });
}


/* 1. HÀM TÍNH TIỀN CHUẨN (Fix lỗi không chạy) */
function calculateTotal() {
    const pDate = document.getElementById("popup_pdate").value;
    const pTime = document.getElementById("popup_ptime").value || "00:00";
    const rDate = document.getElementById("popup_rdate").value;
    const rTime = document.getElementById("popup_rtime").value || "00:00";
    const display = document.getElementById("displayTotal");
    const detail = document.getElementById("dayDetail");
    const inputTotal = document.getElementById("checkoutTotalPrice");

    if (pDate && rDate) {
        const start = new Date(`${pDate}T${pTime}`);
        const end = new Date(`${rDate}T${rTime}`);
        const diffMs = end - start;

        if (diffMs <= 0) {
            display.innerText = "0 VND";
            detail.innerText = "Return time must be after pickup time";
            inputTotal.value = 0;
            return;
        }

        const totalHours = diffMs / (1000 * 60 * 60);
        const fullDays = Math.floor(totalHours / 24);
        const remainingHours = totalHours % 24;
        let chargedDays = fullDays;

        let note = "";
        if (fullDays === 0) {
            chargedDays = 1;
            note = "(Minimum 1 day charge)";
        } else {
            if (remainingHours > 12) {
                chargedDays += 0.5;
                note = `(${fullDays} days + 0.5 day for over 12h extra)`;
            } else if (remainingHours > 0) {
                note = `(${fullDays} days + ${remainingHours.toFixed(1)}h extra)`;
            } else {
                note = `(${fullDays} days)`;
            }
        }

        const total = chargedDays * currentCarPrice;
        display.innerText = new Intl.NumberFormat('vi-VN').format(total) + " VND";
        detail.innerText = `Charged for ${chargedDays} day(s) ${note}`;
        inputTotal.value = total;
    }
}

/* 2. XỬ LÝ ĐÓNG MODAL KHI BẤM RA NGOÀI */
window.addEventListener("click", function(e) {
    const carModal = document.getElementById("carModal");
    const bookingModal = document.getElementById("bookingModal");
    const profileModal = document.getElementById("profileModal");
    
    const chatBox = document.getElementById('chatBox');
    const chatWrapper = document.getElementById('chat-wrapper');
    const chatBubble = document.querySelector('.chat-bubble');
    // Nếu click đúng vào vùng nền đen (class car-modal hoặc profile-modal)
    if (e.target === carModal) carModal.style.display = "none";
    if (e.target === bookingModal) bookingModal.style.display = "none";
    if (e.target === profileModal) profileModal.style.display = "none";

    if (chatBox.style.display === 'block') {
        
        // Kiểm tra xem vị trí click có nằm NGOÀI chat-wrapper hay không
        // .contains(e.target) kiểm tra xem phần tử bị click có phải con của wrapper không
        if (!chatWrapper.contains(e.target)) {
            
            // Nếu click ra ngoài, ẩn khung chat đi
            chatBox.style.display = 'none';}}
});

    // Cập nhật hàm openBookingModal để điền dữ liệu từ thanh Search Bar vào
    function openBookingModal() {
        document.getElementById("carModal").style.display = "none";
        document.getElementById("bookingModal").style.display = "flex";

        // Đồng bộ ID xe
        document.getElementById("popup_vehicle_id").value = currentSelectedCarId;

        // Đồng bộ từ Search Bar
        document.getElementById("popup_location").value = document.querySelector("input[name=location]").value;
        document.getElementById("popup_pdate").value = document.querySelector("input[name=pickup_date]").value;
        document.getElementById("popup_ptime").value = document.querySelector("input[name=pickup_time]").value;
        document.getElementById("popup_rdate").value = document.querySelector("input[name=return_date]").value;
        document.getElementById("popup_rtime").value = document.querySelector("input[name=return_time]").value;

        // Chạy tính tiền lần đầu ngay khi mở modal
        calculateTotal();
    }

    // Nếu người dùng đã nhập ngày ở Search Bar trước đó, tự điền vào lịch luôn
    let sDate = document.querySelector("input[name=pickup_date]").value;
    let eDate = document.querySelector("input[name=return_date]").value;
    if(sDate && eDate) {
        bookingPicker.setDate([sDate, eDate], true);
    }
    
    // Đồng bộ địa điểm từ search bar
    document.getElementById("popup_location").value = document.querySelector("input[name=location]").value;


// Cập nhật lại hàm click mở xe của bạn để gán đơn giá vào biến TOÀN CỤC
document.addEventListener("click", function(e){
    let card = e.target.closest(".vehicle-card");
    if(!card) return;

    let id = card.dataset.id;
    currentSelectedCarId = id; 

    fetch("/car_rental/public/car-detail?id="+id)
    .then(res => res.json())
    .then(data => {
        currentCarPrice = data.price_per_day; // QUAN TRỌNG: Lưu đơn giá vào biến toàn cục
        
        // ... Các code hiển thị ảnh/tên xe của bạn giữ nguyên ...
        document.getElementById("mainImage").src = "/car_rental/images/"+data.image;
        document.getElementById("carName").innerText = data.name;
        document.getElementById("carPrice").innerText = new Intl.NumberFormat().format(data.price_per_day)+" VND/day";
        document.getElementById("carModal").style.display = "flex";
    });
});
</script>
</body>

<?php require_once __DIR__ . "/../layouts/footer.php"; ?>
</html>