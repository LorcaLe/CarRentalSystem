<!DOCTYPE html>
<html>
<head>

<title>Register</title>

<link rel="stylesheet" href="/car_rental/assets/css/auth.css">

</head>

<body>

<div class="login-container">

<div class="login-card">

<h2>Create Account</h2>
<p class="sub">Register to rent your first car</p>

<form method="POST" action="/car_rental/public/register">

<input type="text" name="name" placeholder="Full name" required>

<input type="text" name="identifier" placeholder="Email or Phone number" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit">Register</button>

</form>

<div class="divider">OR</div>

<a href="/car_rental/app/views/auth/login.php" class="create">
Already have account?
</a>

</div>

</div>

</body>
</html>