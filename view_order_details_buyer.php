<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: login.php');
    exit;
}

$order_id = $_GET['order_id'];
$buyer_id = $_SESSION['user_id'];

// Fetch the order details for the selected order
$query = "SELECT om.order_id, om.total_amount, om.payment_method, om.order_status, om.delivery_address, om.date_placed
          FROM order_management om
          WHERE om.order_id = $order_id AND om.buyer_id = $buyer_id";
$order_result = mysqli_query($conn, $query);
$order = mysqli_fetch_assoc($order_result);

if (!$order) {
    echo "Order not found!";
    exit;
}

// Fetch product details for the selected order
$query = "SELECT od.product_id, od.quantity, od.price, od.total_price, pm.product_name, pm.product_image, ot.status AS delivery_status
          FROM order_details od
          JOIN product_management pm ON od.product_id = pm.product_id
          JOIN order_tracking ot ON od.order_id = ot.order_id
          WHERE od.order_id = $order_id";
$product_result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        img {
            max-width: 100px;
            max-height: 100px;
        }
        .status {
            color: green;
        }
    </style>
</head>
<body>
    <h1>Order Details - Order ID: <?php echo $order['order_id']; ?></h1>

    <p><strong>Total Amount:</strong> <?php echo $order['total_amount']; ?></p>
    <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
    <p><strong>Order Status:</strong> <?php echo $order['order_status']; ?></p>
    <p><strong>Delivery Address:</strong> <?php echo $order['delivery_address']; ?></p>
    <p><strong>Date Placed:</strong> <?php echo $order['date_placed']; ?></p>

    <h2>Products in this Order</h2>
    <table>
        <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Delivery Status</th>
        </tr>
        <?php while ($product = mysqli_fetch_assoc($product_result)): ?>
            <tr>
                <td><img src="<?php echo $product['product_image']; ?>" alt="Product Image"></td>
                <td><?php echo $product['product_name']; ?></td>
                <td><?php echo $product['price']; ?></td>
                <td><?php echo $product['quantity']; ?></td>
                <td><?php echo $product['total_price']; ?></td>
                <td class="status"><?php echo $product['delivery_status']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <a href="view_orders_buyer.php">Back to All Orders</a>
</body>
</html>

<?php mysqli_close($conn); ?>
