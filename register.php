<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all fields are set
    if (isset($_POST['role'], $_POST['name'], $_POST['phone_number'], $_POST['address'], $_POST['email'], $_POST['password'])) {
        $role = $_POST['role']; // 'farmer' or 'buyer'
        $name = trim($_POST['name']);
        $phone_number = trim($_POST['phone_number']);
        $address = trim($_POST['address']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Validate phone number (ensure it's numeric and of correct length)
        if (!is_numeric($phone_number) || strlen($phone_number) != 10) {
            echo "Invalid phone number. It must be a 10-digit number.";
            exit;
        }

        // Include the database connection
        include('db_connection.php');

        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query based on the role selected
        if ($role == 'farmer') {
            $query = "INSERT INTO farmer_registration (farmer_name, email, phone_number, address, password, created_at)
                      VALUES ('$name', '$email', '$phone_number', '$address', '$hashed_password', NOW())";
        } elseif ($role == 'buyer') {
            $query = "INSERT INTO buyer_registration (buyer_name, email, phone_number, shipping_address, password, created_at)
                      VALUES ('$name', '$email', '$phone_number', '$address', '$hashed_password', NOW())";
        } else {
            echo "Invalid role selected.";
            exit;
        }

        // Execute the query and check for errors
        if (mysqli_query($conn, $query)) {
            echo "Registration successful! <a href='login.php'>Login</a>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Please fill all the required fields.";
    }
}
?>

<!-- Registration Form with Role Selection -->
<form method="POST" action="register.php">
    <label for="role">Select Role:</label><br>
    <label>
        <input type="radio" name="role" value="farmer" required> Farmer
    </label>
    <label>
        <input type="radio" name="role" value="buyer" required> Buyer
    </label><br><br>

    <label for="name">Name:</label>
    <input type="text" name="name" required><br>

    <label for="phone_number">Phone Number:</label>
    <input type="text" name="phone_number" required><br>

    <label for="address">Address:</label>
    <input type="text" name="address" required><br>

    <label for="email">Email:</label>
    <input type="email" name="email" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br>

    <button type="submit">Register</button>
</form>

<a href="login.php">Already have an account? Login here</a>
