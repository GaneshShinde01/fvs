<?php
session_start();

// Check if the user is logged in and is a farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header('Location: login.php');
    exit;
}

include('db_connection.php');
$farmer_id = $_SESSION['user_id'];

// Fetch farmer details
$query = "SELECT * FROM farmer_registration WHERE farmer_id = '$farmer_id'";
$result = mysqli_query($conn, $query);

// Check for SQL errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$farmer = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #4CAF50;
        }
        h2 {
            color: #333;
            margin-top: 30px;
        }
        p {
            font-size: 16px;
            color: #555;
        }
        nav {
            background-color: #333;
            padding: 15px;
            text-align: center;
        }
        nav a {
            text-decoration: none;
            color: white;
            margin: 0 15px;
            font-size: 18px;
        }
        nav a:hover {
            color: #4CAF50;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        ul li {
            margin: 10px 0;
        }
        ul li a {
            text-decoration: none;
            color: #4CAF50;
            font-size: 18px;
        }
        ul li a:hover {
            color: #333;
        }
        .logout {
            margin-top: 30px;
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
        }
        .logout:hover {
            background-color: #d32f2f;
        }
        .profile {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .profile a {
            text-decoration: none;
            color: #4CAF50;
        }
        .profile a:hover {
            color: #333;
        }
    </style>
</head>
<body>

    <!-- Profile Section at the Top-Right Corner -->
    <div class="profile">
        <a href="view_farmer_profile.php">Profile</a> | <a href="update_farmer_profile.php">Update Profile</a>
    </div>

    <nav>
        <a href="farmer_dashboard.php">Dashboard</a>
        <a href="add_product.php">Add Product</a>
        <a href="view_products_farmer.php">View Products</a>
        <a href="view_orders_farmer.php">View Orders</a>
        <a href="feedback_farmer.php">See Feedbacks</a>
        <a href="logout.php" class="logout">Logout</a>
    </nav>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($farmer['farmer_name']); ?>!</h1>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($farmer['email']); ?></p>
        <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($farmer['phone_number']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($farmer['address']); ?></p>

        
    </div>

</body>
</html>
