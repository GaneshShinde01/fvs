<?php
session_start();

// Check if the user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: login.php');
    exit;
}

include('db_connection.php'); // Make sure this path is correct

$buyer_id = $_SESSION['user_id'];

// Fetch current buyer information
$query = "SELECT * FROM buyer_registration WHERE buyer_id = ?"; // Use prepared statement
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $buyer_id); // "i" for integer
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}

$buyer_data = mysqli_fetch_assoc($result);

if (!$buyer_data) {
    die("Buyer data not found.");
}

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $buyer_name = mysqli_real_escape_string($conn, $_POST['buyer_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $shipping_address = mysqli_real_escape_string($conn, $_POST['shipping_address']);

    // Prepared statement for update
    $update_query = "UPDATE buyer_registration 
                     SET buyer_name = ?, 
                         email = ?, 
                         phone_number = ?, 
                         shipping_address = ? 
                     WHERE buyer_id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssi", $buyer_name, $email, $phone_number, $shipping_address, $buyer_id); // "ssssi" for strings and integer

    if (mysqli_stmt_execute($stmt)) {
        header("Location: buyer_dashboard.php?message=Profile updated successfully!");
        exit;
    } else {
        header("Location: update_buyer_profile.php?message=Error updating profile. Please try again.&error=" . mysqli_error($conn));
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Profile</title>
</head>
<body>
    <h1>Update Profile</h1>

    <?php if (isset($_GET['message'])): ?>
        <p><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <p style="color:red;">Error: <?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <form action="update_buyer_profile.php" method="POST">
        <label for="buyer_name">Name:</label>
        <input type="text" id="buyer_name" name="buyer_name" value="<?php echo htmlspecialchars($buyer_data['buyer_name']); ?>" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($buyer_data['email']); ?>" required><br><br>

        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($buyer_data['phone_number']); ?>" required><br><br>

        <label for="shipping_address">Shipping Address:</label>
        <textarea id="shipping_address" name="shipping_address" required><?php echo htmlspecialchars($buyer_data['shipping_address']); ?></textarea><br><br>

        <input type="submit" name="update_profile" value="Update Profile">
    </form>
    <a href="buyer_dashboard.php">Back to Dashboard</a>
</body>
</html>