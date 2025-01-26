<?php
session_start();
include('db_connection.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: login.php');
    exit;
}

$buyer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Check if the product is already in the cart
    $check_cart_query = "SELECT * FROM buyer_cart WHERE buyer_id = $buyer_id AND product_id = $product_id";
    $check_cart_result = mysqli_query($conn, $check_cart_query);

    if (mysqli_num_rows($check_cart_result) > 0) {
        // Update the quantity if the product is already in the cart
        $update_cart_query = "UPDATE buyer_cart SET quantity = quantity + $quantity WHERE buyer_id = $buyer_id AND product_id = $product_id";
        mysqli_query($conn, $update_cart_query);
    } else {
        // Insert the product into the cart if it's not already there
        $insert_cart_query = "INSERT INTO buyer_cart (buyer_id, product_id, quantity) VALUES ($buyer_id, $product_id, $quantity)";
        mysqli_query($conn, $insert_cart_query);
    }
    
    header('Location: buyer_dashboard.php');
    exit;
}
?>
