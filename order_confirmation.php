<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: login.php');
    exit;
}

// Get the order ID from the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
} else {
    echo "No order ID provided.";
    exit;
}

// Fetch order details from `order_management`
$order_query = "SELECT om.*, ph.payment_status
                FROM order_management om
                JOIN payment_history ph ON om.order_id = ph.order_id
                WHERE om.order_id = '$order_id'";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) > 0) {
    $order = mysqli_fetch_assoc($order_result);
} else {
    echo "Order not found.";
    exit;
}

// Fetch order items from `order_details`
$order_items_query = "SELECT od.*, pm.product_name, pm.product_image 
                      FROM order_details od
                      JOIN product_management pm ON od.product_id = pm.product_id
                      WHERE od.order_id = '$order_id'";
$order_items_result = mysqli_query($conn, $order_items_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Order Confirmation</h1>

    <h2>Order Details</h2>
    <p>Order ID: <?php echo $order['order_id']; ?></p>
    <p>Total Amount: <?php echo $order['total_amount']; ?></p>
    <p>Payment Method: <?php echo $order['payment_method']; ?></p>
    <p>Payment Status: <?php echo $order['payment_status']; ?></p>
    <p>Order Status: <?php echo $order['order_status']; ?></p>
    <p>Delivery Address: <?php echo $order['delivery_address']; ?></p>

    <h2>Ordered Items</h2>
    <table border="1" cellpadding="10">
        <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total Price</th>
        </tr>
        <?php while ($item = mysqli_fetch_assoc($order_items_result)): ?>
            <tr>
                <td><img src="<?php echo $item['product_image']; ?>" alt="Product Image" style="width: 100px; height: 100px;"></td>
                <td><?php echo $item['product_name']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo $item['price']; ?></td>
                <td><?php echo $item['total_price']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="buyer_dashboard.php">Back to Home</a>
</body>
</html>

<?php
mysqli_close($conn);
?>
