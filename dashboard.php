<?php
session_start();

// Redirect to respective dashboards if already logged in
if (isset($_SESSION['farmer_id'])) {
    header('Location: farmer_dashboard.php');
    exit();
}
if (isset($_SESSION['buyer_id'])) {
    header('Location: buyer_dashboard.php');
    exit();
}
?>

<h2>Welcome to Our Web Platform</h2>
<p>Please log in or register as a Farmer or a Buyer.</p>

<h3>Login</h3>
<!-- Single login option with dynamic role -->
<!-- //<a href="login.php?role=farmer">Login as Farmer</a><br> -->
<a href="login.php">Login</a><br>

<h3>Register</h3>
<!-- Separate registration options -->
<a href="register.php">Register</a><br>
<!-- <a href="buyer_register.php">Register as Buyer</a><br> -->
