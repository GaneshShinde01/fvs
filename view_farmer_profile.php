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
    <title>Farmer Profile</title>
</head>
<body>
    <h1>Farmer Profile</h1>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($farmer['farmer_name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($farmer['email']); ?></p>
    <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($farmer['phone_number']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($farmer['address']); ?></p>

    <a href="farmer_dashboard.php">Back to Dashboard</a>

</body>
</html>
