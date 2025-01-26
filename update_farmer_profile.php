<?php
session_start();

// Check if the user is logged in and is a farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header('Location: login.php');
    exit;
}

include('db_connection.php');
$farmer_id = $_SESSION['user_id']; // Get farmer ID from session

// Fetch current farmer information from the database
$query = "SELECT * FROM farmer_registration WHERE farmer_id = '$farmer_id'";
$result = mysqli_query($conn, $query);
$farmer_data = mysqli_fetch_assoc($result);

// Handle form submission for updating profile
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form input data
    $farmer_name = mysqli_real_escape_string($conn, $_POST['farmer_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    // Update farmer information in the database
    $update_query = "UPDATE farmer_registration 
                     SET farmer_name = '$farmer_name', 
                         email = '$email', 
                         phone_number = '$phone_number', 
                         address = '$address' 
                     WHERE farmer_id = '$farmer_id'";

    if (mysqli_query($conn, $update_query)) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile. Please try again.";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Farmer Profile</title>
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
        .message {
            text-align: center;
            padding: 10px;
            margin: 10px;
            color: green;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .form-container label {
            font-size: 16px;
            display: block;
            margin-bottom: 5px;
        }
        .form-container input[type="text"], 
        .form-container input[type="email"], 
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .form-container input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .form-container textarea {
            resize: vertical;
        }
    </style>
</head>
<body>

    <!-- Display message if profile update was successful or failed -->
    <?php if (isset($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="container">
        <h1>Update Your Profile</h1>

        <!-- Profile Update Form -->
        <div class="form-container">
            <form method="POST" action="update_farmer_profile.php">
                <label for="farmer_name">Name:</label>
                <input type="text" name="farmer_name" id="farmer_name" value="<?php echo htmlspecialchars($farmer_data['farmer_name']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($farmer_data['email']); ?>" required>

                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($farmer_data['phone_number']); ?>" required>

                <label for="address">Address:</label>
                <textarea name="address" id="address" rows="4" required><?php echo htmlspecialchars($farmer_data['address']); ?></textarea>

                <input type="submit" value="Update Profile">
            </form>
        </div>
    </div>
    <a href="farmer_dashboard.php">Back to Dashboard</a>

</body>
</html>
