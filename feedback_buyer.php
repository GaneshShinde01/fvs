<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and is a buyer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'buyer') {
    header('Location: login.php');
    exit;
}

// Fetch products that the buyer has ordered (for feedback purposes)
$buyer_id = $_SESSION['user_id'];
$query = "SELECT od.product_id, pm.product_name
          FROM order_details od
          JOIN product_management pm ON od.product_id = pm.product_id
          JOIN order_management om ON om.order_id = od.order_id
          WHERE om.buyer_id = $buyer_id";
$product_result = mysqli_query($conn, $query);

// Handle feedback submission
$feedback_message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']); // Prevent SQL injection

    // Check if the buyer has already provided feedback for this product
    $check_feedback = "SELECT * FROM product_feedback WHERE product_id = $product_id AND buyer_id = $buyer_id";
    $feedback_check_result = mysqli_query($conn, $check_feedback);
    if (mysqli_num_rows($feedback_check_result) > 0) {
        $feedback_message = "You have already provided feedback for this product.";
    } else {
        // Insert the feedback into the database
        $insert_feedback = "INSERT INTO product_feedback (product_id, buyer_id, rating, feedback)
                            VALUES ($product_id, $buyer_id, $rating, '$feedback')";
        if (mysqli_query($conn, $insert_feedback)) {
            $feedback_message = "Feedback submitted successfully!";
        } else {
            $feedback_message = "Error: " . mysqli_error($conn);
        }
    }
}

// Fetch all feedback given by the buyer along with the farmer's response
$fetch_feedback_query = "SELECT pf.rating, pf.feedback, pf.created_at, pf.response, pm.product_name
                         FROM product_feedback pf
                         JOIN product_management pm ON pf.product_id = pm.product_id
                         WHERE pf.buyer_id = $buyer_id";
$feedback_history_result = mysqli_query($conn, $fetch_feedback_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provide Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label {
            font-weight: bold;
        }
        select, textarea, input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .back-button {
            margin-top: 20px;
            display: block;
            text-align: center;
            font-size: 16px;
        }
        .message {
            text-align: center;
            margin-top: 10px;
            font-size: 16px;
            color: green;
        }
        .error {
            color: red;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
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
    </style>
</head>
<body>
    <h1>Provide Feedback</h1>

    <form action="" method="POST">
        <label for="product_id">Select Product:</label>
        <select name="product_id" id="product_id" required>
            <option value="">--Select Product--</option>
            <?php while ($product = mysqli_fetch_assoc($product_result)): ?>
                <option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="rating">Rating (1-5):</label>
        <input type="number" id="rating" name="rating" min="1" max="5" required>

        <label for="feedback">Feedback/Suggestions:</label>
        <textarea id="feedback" name="feedback" rows="4" cols="50" required></textarea>

        <button type="submit">Submit Feedback</button>
    </form>

    <!-- Display feedback message (success or error) -->
    <?php if ($feedback_message): ?>
        <div class="message <?php echo strpos($feedback_message, "Error") !== false ? 'error' : ''; ?>">
            <?php echo $feedback_message; ?>
        </div>
    <?php endif; ?>

    <!-- Display feedback history -->
    <h2>Your Feedback History</h2>
    <?php if (mysqli_num_rows($feedback_history_result) > 0): ?>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Rating</th>
                <th>Feedback</th>
                <th>Farmer's Response</th>
                <th>Date</th>
            </tr>
            <?php while ($feedback = mysqli_fetch_assoc($feedback_history_result)): ?>
                <tr>
                    <td><?php echo $feedback['product_name']; ?></td>
                    <td><?php echo $feedback['rating']; ?></td>
                    <td><?php echo $feedback['feedback']; ?></td>
                    <td><?php echo $feedback['response'] ? $feedback['response'] : 'No response yet'; ?></td>
                    <td><?php echo $feedback['created_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have not provided feedback for any products yet.</p>
    <?php endif; ?>

    <div class="back-button">
        <a href="buyer_dashboard.php">Back to Home</a>
    </div>
</body>
</html>

<?php mysqli_close($conn); ?>
