<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'farmer') {
    header('Location: login.php');
    exit;
}

include('db_connection.php');

if (isset($_GET['id'])) {
    $product_id = mysqli_real_escape_string($conn, $_GET['id']);
    $farmer_id = $_SESSION['user_id'];

    // Fetch product details
    $query = "SELECT product_name, description, price, quantity_available, product_image FROM product_management WHERE product_id = '$product_id' AND farmer_id = '$farmer_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $product_name = $row['product_name'];
        $description = $row['description'];
        $price = $row['price'];
        $quantity_available = $row['quantity_available'];
        $product_image = $row['product_image'];
    } else {
        // Product not found or doesn't belong to the farmer
        echo "Product not found.";
        exit;
    }
} else {
    echo "Invalid product ID.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity_available = mysqli_real_escape_string($conn, $_POST['quantity_available']);

    // Handle image upload (if a new image was uploaded)
    if (!empty($_FILES['product_image']['name'])) {
        $target_dir = "uploads/";
        $image_name = $target_dir . basename($_FILES["product_image"]["name"]);
        $target_file = $target_dir . $image_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["product_image"]["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                $product_image = $image_name; // Update the image filename in the database
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    $update_query = "UPDATE product_management SET product_name = '$product_name', description = '$description', price = '$price', quantity_available = '$quantity_available', product_image = '$product_image' WHERE product_id = '$product_id' AND farmer_id = '$farmer_id'";
    if (mysqli_query($conn, $update_query)) {
        header("Location: view_products.php"); // Redirect after successful update
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
    <h1>Edit Product</h1>
    <form method="post" enctype="multipart/form-data">
        Product Name: <input type="text" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>"><br>
        Description: <textarea name="description"><?php echo htmlspecialchars($description); ?></textarea><br>
        Price: <input type="text" name="price" value="<?php echo htmlspecialchars($price); ?>"><br>
        Quantity Available: <input type="text" name="quantity_available" value="<?php echo htmlspecialchars($quantity_available); ?>"><br>
        Product Image: <input type="file" name="product_image"><br>
        <img src="<?php echo $product_image; ?>" width="100px"><br>
        <input type="submit" value="Update">
    </form>
        <a href="view_products.php">Cancel</a>
</body>
</html>

<?php mysqli_close($conn); ?>