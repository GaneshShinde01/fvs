<?php
session_start();

// Check if the user is logged in and is a farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header('Location: login.php');
    exit;
}

include('db_connection.php');
$farmer_id = $_SESSION['user_id'];

// Debugging output to check the farmer ID
//echo "Farmer ID: " . $farmer_id . "<br>";

// Fetch products purchased by buyers from the logged-in farmer
$query = "
    SELECT p.product_id, p.product_name, b.buyer_name, b.shipping_address, od.quantity, od.price, od.total_price, o.order_status
    FROM product_management p
    JOIN order_details od ON p.product_id = od.product_id
    JOIN order_management o ON od.order_id = o.order_id
    JOIN buyer_registration b ON o.buyer_id = b.buyer_id
    WHERE p.farmer_id = '$farmer_id'
    ORDER BY o.created_at DESC";

// Debugging: print the SQL query to verify
//echo "SQL Query: " . $query . "<br>";

$result = mysqli_query($conn, $query);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));  // Print error if query fails
}

// Display purchased products
if (mysqli_num_rows($result) > 0) {
    echo "<h1>Products Purchased by Buyers</h1>";
    echo "<table border='1'>
            <tr>
                <th>Product Name</th>
                <th>Buyer Name</th>
                <th>Shipping Address</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total Price</th>
                <th>Order Status</th>
            </tr>";

    while ($product = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$product['product_name']}</td>
                <td>{$product['buyer_name']}</td>
                <td>{$product['shipping_address']}</td>
                <td>{$product['quantity']}</td>
                <td>{$product['price']}</td>
                <td>{$product['total_price']}</td>
                <td>{$product['order_status']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No products have been purchased from this farmer.</p>";
}

?>

<!-- Dashboard Navigation (with Farmer Profile at the top right corner) -->
<nav>
    <ul>
        <li><a href="farmer_dashboard.php">Dashboard</a></li>
    </ul>
</nav>
