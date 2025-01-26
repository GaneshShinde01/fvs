<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: login.php');
    exit;
}

include('db_connection.php');
$buyer_id = $_SESSION['user_id'];

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
}

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "SELECT product_id, product_name, product_image, price 
          FROM product_management 
          WHERE product_name LIKE '%$search_query%' 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$count_query = "SELECT COUNT(*) AS total_products 
                FROM product_management 
                WHERE product_name LIKE '%$search_query%'";
$count_result = mysqli_query($conn, $count_query);
if (!$count_result) {
    die("Count query failed: " . mysqli_error($conn));
}
$count_data = mysqli_fetch_assoc($count_result);
$total_products = $count_data['total_products'];
$total_pages = ceil($total_products / $limit);

$cart_query = "SELECT SUM(quantity) AS total_items FROM buyer_cart WHERE buyer_id = $buyer_id";
$cart_result = mysqli_query($conn, $cart_query);
if (!$cart_result) {
    die("Cart query failed: " . mysqli_error($conn));
}
$cart_data = mysqli_fetch_assoc($cart_result);
$total_items_in_cart = $cart_data['total_items'] ? $cart_data['total_items'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Buyer Dashboard</title>
    <style>
        /* Style for the navigation bar */
        .navbar {
            background-color: #333;
            padding: 10px;
            position: sticky;
            top: 0;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .navbar ul li {
            margin-right: 20px;
        }
        .navbar ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .navbar ul li a:hover {
            background-color: #555;
        }

        .search-form {
            margin-left: 20px;
        }

        .search-form input[type="text"] {
            padding: 5px;
        }

        .search-form input[type="submit"] {
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border: none;
        }

        /* Wrapper for profile, cart and logout buttons */
        .navbar-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 20px; /* Space between the buttons */
        }

        .cart-button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            position: relative;
        }
        .cart-button .cart-count {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 3px 7px;
            position: absolute;
            top: -10px;
            right: -10px;
            font-size: 12px;
        }

        .product-card {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .quantity-controls {
            display: flex;
            align-items: center;
        }
        .quantity-controls button {
            padding: 5px 10px;
        }
        /* Responsive design */
        @media (max-width: 768px) {
            .product-card {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    <script>
        function increaseQuantity(productId) {
            const quantityInput = document.getElementById('quantity_' + productId);
            let currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        }

        function decreaseQuantity(productId) {
            const quantityInput = document.getElementById('quantity_' + productId);
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        }
    </script>
</head>
<body>

    <div class="navbar">
        <ul>
            <li><a href="buyer_dashboard.php">Home</a></li>
            <li><a href="view_orders_buyer.php">View Orders</a></li>
            <li><a href="update_buyer_profile.php">Update Profile</a></li>
            <li><a href="feedback_buyer.php">FeedBack</a></li>
            <li><a href="reset_password.php">Change Password</a></li>
        </ul>
        <form class="search-form" method="GET" action="buyer_dashboard.php">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>">
            <input type="submit" value="Search">
        </form>
        <div class="navbar-right">
            <a href="view_cart.php" class="cart-button">View Cart
                <span class="cart-count"><?php echo $total_items_in_cart; ?></span>
            </a>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
    </div>

    <?php if (isset($_GET['message'])): ?>
        <p><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>

    <h1>Browse Products</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($product = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <div>
                    <img src="<?php echo empty($product['product_image']) ? 'default_image.jpg' : $product['product_image']; ?>" alt="Product Image" width="100">
                    <h3><?php echo $product['product_name']; ?></h3>
                    <p>Price: <?php echo $product['price']; ?></p>
                </div>

                <div class="quantity-controls">
                    <button onclick="decreaseQuantity(<?php echo $product['product_id']; ?>)">-</button>
                    <input type="text" id="quantity_<?php echo $product['product_id']; ?>" value="1" size="2" readonly>
                    <button onclick="increaseQuantity(<?php echo $product['product_id']; ?>)">+</button>
                </div>

                <div>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <input type="hidden" name="quantity" id="hidden_quantity_<?php echo $product['product_id']; ?>" value="1">
                        <button type="submit" onclick="document.getElementById('hidden_quantity_<?php echo $product['product_id']; ?>').value = document.getElementById('quantity_<?php echo $product['product_id']; ?>').value;">
                            Add to Cart
                        </button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?search=<?php echo htmlspecialchars($search_query); ?>&page=<?php echo $page - 1; ?>">Previous</a>
            <?php endif; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?search=<?php echo htmlspecialchars($search_query); ?>&page=<?php echo $page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <p>No products available<?php if (!empty($search_query)) echo " matching your search"; ?>.</p>
    <?php endif; ?>

</body>
</html>