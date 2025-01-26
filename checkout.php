<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: login.php');
    exit;
}

$buyer_id = $_SESSION['user_id'];

// Fetch buyer's cart items
$query = "SELECT bc.cart_id, pm.product_image, pm.product_name, pm.price, bc.quantity, (pm.price * bc.quantity) AS total_price
          FROM buyer_cart bc
          JOIN product_management pm ON bc.product_id = pm.product_id
          WHERE bc.buyer_id = $buyer_id";
$result = mysqli_query($conn, $query);

$total_amount = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
    <h1>Checkout</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><img src="<?php echo $row['product_image']; ?>" alt="Product Image" style="width: 100px; height: 100px;"></td>
                    <td><?php echo $row['product_name']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['total_price']; ?></td>
                </tr>
                <?php $total_amount += $row['total_price']; ?>
            <?php endwhile; ?>
            <tr>
                <td colspan="4"><strong>Total Amount</strong></td>
                <td><strong><?php echo $total_amount; ?></strong></td>
            </tr>
        </table>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <h2>Payment Details</h2>
    <form id="payment_form" action="process_payment.php" method="POST">
        <label for="payment_method">Payment Method:</label>
        <select name="payment_method" id="payment_method" required>
            <option value="credit_card">Credit Card</option>
            <option value="debit_card">Debit Card</option>
            <option value="paypal">PayPal</option>
            <option value="cod">Cash on Delivery</option>
        </select>
        <br>

        <label for="delivery_address">Delivery Address:</label>
        <input type="text" name="delivery_address" id="delivery_address" required>
        <br>

        <input type="hidden" name="total_amount" value="<?php echo $total_amount; ?>">
        <button type="submit">Proceed to Payment</button>
    </form>

    <a href="buyer_dashboard.php">Back to Home</a>
</body>
</html>

<?php mysqli_close($conn); ?>
