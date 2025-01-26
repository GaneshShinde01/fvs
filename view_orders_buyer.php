<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: login.php');
    exit;
}

$buyer_id = $_SESSION['user_id'];

// Fetch all orders placed by the logged-in buyer (paid or pending payment)
$query = "SELECT om.order_id, om.total_amount, om.payment_method, om.order_status, om.delivery_address, om.date_placed, 
                 ph.payment_status 
          FROM order_management om
          LEFT JOIN payment_history ph ON om.order_id = ph.order_id
          WHERE om.buyer_id = $buyer_id";

$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
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
        .button {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Your Orders</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Total Amount</th>
                <th>Payment Method</th>
                <th>Payment Status</th>
                <th>Order Status</th>
                <th>Delivery Address</th>
                <th>Date Placed</th>
                <th>Details</th>
            </tr>
            <?php while ($order = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo $order['total_amount']; ?></td>
                    <td><?php echo $order['payment_method']; ?></td>
                    <td><?php echo $order['payment_status'] ? $order['payment_status'] : 'Pending Payment'; ?></td>
                    <td><?php echo $order['order_status']; ?></td>
                    <td><?php echo $order['delivery_address']; ?></td>
                    <td><?php echo $order['date_placed']; ?></td>
                    <td><a href="view_order_details_buyer.php?order_id=<?php echo $order['order_id']; ?>" class="button">View Details</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have no orders.</p>
    <?php endif; ?>
    <a href="buyer_dashboard.php">Back to Home</a>

</body>
</html>

<?php mysqli_close($conn); ?>
