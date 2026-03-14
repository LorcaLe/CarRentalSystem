<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<link rel="stylesheet" href="/car_rental/assets/css/auth.css">

</head>

<body>

<div class="login-container">

<div class="login-card">

<h2>Welcome Back</h2>
<p class="sub">Login to continue your journey</p>

<form method="POST" action="/car_rental/public/login">

<input type="text" name="identifier" placeholder="Email or Phone number" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit">Login</button>

<p>

<a href="/car_rental/public/forgot-password">

Forgot password?

</a>

</p>
</form>

<div class="divider">OR</div>

<a href="/car_rental/app/views/auth/register.php" class="create">
Create account
</a>

</div>

</div>

</body>
</html>