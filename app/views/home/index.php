<!DOCTYPE html>
<html>

<head>

<title>Luxury Car Rental</title>

<link rel="stylesheet" href="/car_rental/assets/css/style.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
<input type="checkbox" name="seats" value="4">
<span></span>
4 seats
</label>

<label class="radio">
<input type="checkbox" name="seats" value="5">
<span></span>
5 seats
</label>

<label class="radio">
<input type="checkbox" name="seats" value="7">
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

<!-- CHAT BUTTON -->

<div class="chat-bubble" onclick="toggleChat()">

💬

</div>


<!-- CHAT BOX -->

<div class="chat-box" id="chatBox">

<div class="chat-header">

Customer Support

<span onclick="toggleChat()">✖</span>

</div>

<div class="chat-content" id="chatContent">

<?php if(isset($_SESSION['user'])){ ?>

<form action="/car_rental/app/views/support/enquiry.php" method="POST">

<textarea name="message" placeholder="Type your question..."></textarea>

<button type="submit">Send</button>

</form>

<?php } else { ?>

<p>Bạn hãy đăng nhập để có thể đặt câu hỏi</p>

<a href="/car_rental/app/views/auth/login.php" class="login-btn">Login</a>

<?php } ?>

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
    <button class="book-btn">BOOK NOW</button>
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

function toggleChat(){

let chat = document.getElementById("chatBox");

if(chat.style.display === "flex"){

chat.style.display = "none";

}else{

chat.style.display = "flex";

}
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

document.querySelectorAll("input[name=seats]:checked")
.forEach(el=>data.append("seats[]",el.value));

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
</script>
</body>
</html>