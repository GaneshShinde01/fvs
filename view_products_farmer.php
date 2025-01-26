<?php
session_start();

// Check if the session is valid (i.e., logged in as a farmer)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'farmer') {
    header('Location: login.php');
    exit;
}

include('db_connection.php');
$farmer_id = $_SESSION['user_id'];

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM product_management WHERE product_id = '$delete_id' AND farmer_id = '$farmer_id'"; // Ensure farmer owns the product
    if (mysqli_query($conn, $delete_query)) {
        // Optionally add a success message
        header("Location: view_products.php"); // Redirect to refresh the page
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn); // Handle errors
    }
}

// Query to fetch all products
$query = "SELECT product_id, product_image, product_name, description, price, quantity_available FROM product_management WHERE farmer_id = '$farmer_id'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View All Products</title>
    <style>
        /* Styling for your page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            width: 90%;
            max-width: 1500px;
            margin: 20px auto;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            /* margin-top: 20px; */
        }
        .product-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            text-align: center;
            transition: transform 0.2s;
            display: flex;
            flex-direction: column;
            min-height: 400px;
        }
                /* Styles for single product view */
        .product-grid:has(.product-card:only-child) {
            grid-template-columns: 1fr; /* Single column layout */
            max-width: 800px; /* Limit width of single product */
            margin: 20px auto;
        }
        .product-grid:has(.product-card:only-child) .product-card {
            text-align: left; /* Align text left in single product view */
            min-height: auto; /* Remove fixed height for single product */
        }
        .product-grid:has(.product-card:only-child) .product-image img{
            max-height: 400px; /* Adjust max height as needed */
            width: auto;
            display: block;
            margin: 0 auto;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-image img {
            width: 100%;
            height: auto;
            max-height: 250px;
            object-fit: contain;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .product-info {
            text-align: left;
        }
        .product-info h3 {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .product-info p {
            margin: 5px 0;
        }
        .product-info .price {
            font-size: 1.1em;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Products</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="product-grid">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if (!empty($row['product_image'])): ?>
                                <img src="<?php echo $row['product_image']; ?>" alt="Product Image">
                            <?php else: ?>
                                <img src="uploads/default.jpg" alt="No Image Available">
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="price"><?php echo htmlspecialchars($row['price']); ?> / unit</p>
                            <p><?php echo htmlspecialchars($row['quantity_available']); ?> available</p>
                        </div>
                        <div>
                            <a href="edit_product.php?id=<?php echo $row['product_id']; ?>" class="edit-btn">Edit</a>
                            <a href="view_products.php?delete_id=<?php echo $row['product_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>

        <a href="farmer_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>