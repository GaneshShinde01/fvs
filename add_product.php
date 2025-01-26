<?php
session_start(); // Ensure session is started

// Check if the farmer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    echo "You must be logged in as a farmer to add products.";
    exit;
}

$message = ''; // To store success or error messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity_available = $_POST['quantity_available'];
    $farmer_id = $_SESSION['user_id']; // farmer_id from session

    // Handle file upload
    $target_dir = "uploads/"; // Directory to store the images
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["product_image"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $message = "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        // Generate a unique name for the file if it already exists
        $target_file = $target_dir . time() . '_' . basename($_FILES["product_image"]["name"]);
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message = "Sorry, your file was not uploaded.";
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            // Include the database connection
            include('db_connection.php');

            // Store the product details including the image path in the database
            $query = "INSERT INTO product_management (farmer_id,product_image, product_name, description, price, quantity_available)
            VALUES ('$farmer_id' , '$target_file', '$product_name', '$description', '$price', '$quantity_available')";

            if (mysqli_query($conn, $query)) {
                $message = "Product and image added successfully!";
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        } else {
            $message = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>

    <h1>Add New Product</h1>

    <?php if ($message != ''): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="add_product.php" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label>
        <input type="text" name="product_name" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br>

        <label for="price">Price:</label>
        <input type="number" name="price" required><br>

        <label for="quantity_available">Quantity Available:</label>
        <input type="number" name="quantity_available" required><br>

        <label for="product_image">Product Image:</label>
        <input type="file" name="product_image" required><br>

        <button type="submit">Add Product</button>
    </form>
    <a href="farmer_dashboard.php">Back to Dashboard</a>


</body>
</html>
