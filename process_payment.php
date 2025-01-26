<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: login.php');
    exit;
}

$buyer_id = $_SESSION['user_id'];
$payment_method = $_POST['payment_method'];
$total_amount = $_POST['total_amount'];
$delivery_address = $_POST['delivery_address'];

// Simulate payment process (in a real system, you would integrate a payment gateway)
if ($payment_method == 'cod') {
    $payment_status = 'Pending';
    $order_status = 'Pending';
} else {
    $payment_status = 'Success';
    $order_status = 'Processing';
}

// Insert order details into `order_management`
$order_query = "INSERT INTO order_management (buyer_id, farmer_id, total_amount, payment_method, order_status, delivery_address) 
                VALUES ('$buyer_id', '1', '$total_amount', '$payment_method', '$order_status', '$delivery_address')";
if (mysqli_query($conn, $order_query)) {
    $order_id = mysqli_insert_id($conn);

    // Insert payment details into `payment_history`
    $payment_query = "INSERT INTO payment_history (order_id, payment_method, payment_status, payment_amount) 
                      VALUES ('$order_id', '$payment_method', '$payment_status', '$total_amount')";
    mysqli_query($conn, $payment_query);

    // Fetch the buyer's cart items
    $cart_query = "SELECT product_id, quantity FROM buyer_cart WHERE buyer_id = '$buyer_id'";
    $cart_result = mysqli_query($conn, $cart_query);

    while ($cart_item = mysqli_fetch_assoc($cart_result)) {
        $product_id = $cart_item['product_id'];
        $quantity = $cart_item['quantity'];

        // Get the product price from `product_management`
        $price_query = "SELECT price FROM product_management WHERE product_id = '$product_id'";
        $price_result = mysqli_query($conn, $price_query);
        $product = mysqli_fetch_assoc($price_result);
        $price = $product['price'];
        $total_price = $price * $quantity;

        // Insert each cart item into `order_details`
        $order_details_query = "INSERT INTO order_details (order_id, product_id, quantity, price, total_price) 
                                VALUES ('$order_id', '$product_id', '$quantity', '$price', '$total_price')";
        mysqli_query($conn, $order_details_query);
    }

    // Clear the cart after order is placed
    $clear_cart_query = "DELETE FROM buyer_cart WHERE buyer_id = '$buyer_id'";
    mysqli_query($conn, $clear_cart_query);

    echo "Payment successful! Your order has been placed.";
    header("Location: order_confirmation.php?order_id=$order_id");
} else {
    echo "Error: Could not process your order.";
}

mysqli_close($conn);
?>
