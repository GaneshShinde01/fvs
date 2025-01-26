<?php
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // 'farmer' or 'buyer'

    include('db_connection.php'); // Include your database connection

    // Farmer login check
    if ($role == 'farmer') {
        $query = "SELECT * FROM farmer_registration WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            // If the query fails, show an error
            die('Query failed: ' . mysqli_error($conn));
        }

        $user = mysqli_fetch_assoc($result);

        // Verify password and login
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['farmer_id'];
            $_SESSION['role'] = 'farmer'; // Store role in session

            // Redirect to farmer dashboard
            header('Location: farmer_dashboard.php');
            exit; // Make sure no further code is executed after redirect
        } else {
            echo "Invalid email or password!";
        }
    } elseif ($role == 'buyer') {
        // Buyer login check
        $query = "SELECT * FROM buyer_registration WHERE email = '$email'";
        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            // If the query fails, show an error
            die('Query failed: ' . mysqli_error($conn));
        }

        $user = mysqli_fetch_assoc($result);

        // Verify password and login
        if ($user && password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['buyer_id'];
            $_SESSION['role'] = 'buyer'; // Store role in session

            // Redirect to buyer dashboard
            header('Location: buyer_dashboard.php');
            exit; // Make sure no further code is executed after redirect
        } else {
            echo "Invalid email or password!";
        }
    } else {
        echo "Please select a role!";
    }
}
?>

<!-- Improved Login Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="style.css"> <!-- For styling the form -->
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <div class="role-selection">
                <label>
                    <input type="radio" name="role" value="farmer" required> Farmer
                </label>
                <label>
                    <input type="radio" name="role" value="buyer" required> Buyer
                </label>
            </div>

            <label for="email">Email:</label>
            <input type="email" name="email" placeholder="Enter your email" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="Enter your password" required><br>

            <button type="submit">Login</button>

             <!-- Forget Password Link -->
             <a href="forget_password.php">Forgot your password?</a>
             <a href="register.php">New here? Register Now</a>
        </form>
    </div>
</body>
</html>
