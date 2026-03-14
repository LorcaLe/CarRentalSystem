<!DOCTYPE html>
<html>

<head>

<title>Forgot Password</title>

<link rel="stylesheet" href="/car_rental/assets/css/auth.css">

</head>

<body>

<div class="auth-container">

<h2>Forgot Password</h2>

<p>Enter your email or phone number</p>

<!-- STEP 1 : SEND OTP -->

<form id="forgotForm" method="POST">

<input type="text"
name="identifier"
placeholder="Email or phone number"
required>

<button type="submit">
Send OTP
</button>

</form>


<!-- STEP 2 : VERIFY OTP -->

<div id="otpSection" style="display:none">

<h3>Enter OTP</h3>

<input type="text"
id="otp"
placeholder="6 digit code">

<button onclick="verifyOTP()">
Verify OTP
</button>

</div>


<!-- STEP 3 : RESET PASSWORD -->

<div id="resetSection" style="display:none">

<h3>Reset Password</h3>

<input type="password"
id="newPassword"
placeholder="New password">

<button onclick="resetPassword()">
Update Password
</button>

</div>


<p>

<a href="/car_rental/public/login">
Back to Login
</a>

</p>

</div>

<script>


/* SEND OTP */

document.getElementById("forgotForm").onsubmit=function(e){

e.preventDefault();

let formData=new FormData(this);

fetch("/car_rental/public/send-otp",{

method:"POST",

body:formData

})

.then(res=>res.text())

.then(data=>{

alert(data);

document.getElementById("otpSection").style.display="block";

});

};



/* VERIFY OTP */

function verifyOTP(){

let otp=document.getElementById("otp").value;

fetch("/car_rental/public/verify-otp",{

method:"POST",

headers:{

"Content-Type":"application/x-www-form-urlencoded"

},

body:"otp="+otp

})

.then(res=>res.text())

.then(data=>{

if(data=="OTP verified"){

document.getElementById("resetSection").style.display="block";

}else{

alert(data);

}

});

}



/* RESET PASSWORD */

function resetPassword(){

let password=document.getElementById("newPassword").value;
let otp=document.getElementById("otp").value;

fetch("/car_rental/public/reset-password",{

method:"POST",

headers:{
"Content-Type":"application/x-www-form-urlencoded"
},

body:"password="+password+"&otp="+otp

})
.then(res=>res.text())
.then(data=>{

alert(data);

window.location="/car_rental/public/login";

});

}

</script>

</body>

</html>