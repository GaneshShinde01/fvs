<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and is a farmer
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'farmer') {
    header('Location: login.php');
    exit;
}

$farmer_id = $_SESSION['user_id'];

// Fetch products belonging to the logged-in farmer
$query = "SELECT pf.feedback_id, pf.feedback, pf.rating, pf.created_at, pf.product_id, pm.product_name, pf.buyer_id, br.buyer_name, pf.response
          FROM product_feedback pf
          JOIN product_management pm ON pf.product_id = pm.product_id
          JOIN buyer_registration br ON pf.buyer_id = br.buyer_id
          WHERE pm.farmer_id = $farmer_id";
$result = mysqli_query($conn, $query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $feedback_id = $_POST['feedback_id'];
    $response = $_POST['response'];

    // Update the feedback with the farmer's response
    $update_response = "UPDATE product_feedback SET response = '$response' WHERE feedback_id = $feedback_id";
    mysqli_query($conn, $update_response);
    echo "Response submitted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View and Respond to Feedback</title>
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
    <h1>Feedback on Your Products</h1>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Buyer</th>
                <th>Rating</th>
                <th>Feedback</th>
                <th>Date</th>
                <th>Response</th>
                <th>Submit Response</th>
            </tr>
            <?php while ($feedback = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $feedback['product_name']; ?></td>
                    <td><?php echo $feedback['buyer_name']; ?></td>
                    <td><?php echo $feedback['rating']; ?></td>
                    <td><?php echo $feedback['feedback']; ?></td>
                    <td><?php echo $feedback['created_at']; ?></td>
                    <td>
                        <?php echo $feedback['response'] ? $feedback['response'] : 'No response yet'; ?>
                    </td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="feedback_id" value="<?php echo $feedback['feedback_id']; ?>">
                            <textarea name="response" rows="2" cols="30" required><?php echo $feedback['response']; ?></textarea><br>
                            <button type="submit" class="button">Submit Response</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No feedback found for your products.</p>
    <?php endif; ?>

    <a href="farmer_dashboard.php">Back to Dashboard</a>

</body>
</html>

<?php mysqli_close($conn); ?>
