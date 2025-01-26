<?php
session_start();

// Check if the user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: login.php');
    exit;
}

include('db_connection.php');
$buyer_id = $_SESSION['user_id']; // Retrieve buyer ID from session

// Fetch buyer details from the database
$query = "SELECT * FROM buyer_registration WHERE buyer_id = $buyer_id";
$result = mysqli_query($conn, $query);

// Check if the query returned a valid result
if (mysqli_num_rows($result) > 0) {
    $buyer = mysqli_fetch_assoc($result);
} else {
    // If no result is returned, display an error message or handle it as needed
    die("Error: Buyer profile not found.");
}

// Handle profile update functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Update the buyer's profile
    $update_query = "UPDATE buyer_registration 
                     SET name = '$name', email = '$email', phone = '$phone', address = '$address' 
                     WHERE buyer_id = $buyer_id";
    
    if (mysqli_query($conn, $update_query)) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Profile</title>
    <style>
        /* Basic style for the profile page */
        .profile-container {
            width: 80%;
            margin: auto;
            padding: 20px;
        }
        .profile-container h1 {
            text-align: center;
        }
        .profile-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .profile-form input {
            padding: 10px;
            font-size: 16px;
            width: 100%;
        }
        .profile-form button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .profile-form button:hover {
            background-color: #218838;
        }
        .message {
            text-align: center;
            color: green;
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <h1>Buyer Profile</h1>
        
        <?php if (isset($message)) { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>

        <!-- Profile Form -->
        <form class="profile-form" method="POST" action="buyer_profile.php">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo isset($buyer['name']) ? htmlspecialchars($buyer['name']) : ''; ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo isset($buyer['email']) ? htmlspecialchars($buyer['email']) : ''; ?>" required>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value="<?php echo isset($buyer['phone']) ? htmlspecialchars($buyer['phone']) : ''; ?>" required>

            <label for="address">Address</label>
            <input type="text" id="address" name="address" value="<?php echo isset($buyer['address']) ? htmlspecialchars($buyer['address']) : ''; ?>" required>

            <button type="submit">Update Profile</button>
        </form>
    </div>

</body>
</html>
