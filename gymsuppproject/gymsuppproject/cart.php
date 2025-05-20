<?php
session_start();
require_once 'db.php';


// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle item removal
if (isset($_GET['remove_item'])) {
    $remove_item_id = $_GET['remove_item'];

    // Delete the item from the cart in the database
    $delete_sql = "DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id";
    $delete_stmt = $pdo->prepare($delete_sql);
    $delete_stmt->bindParam(':user_id', $user_id);
    $delete_stmt->bindParam(':product_id', $remove_item_id);
    $delete_stmt->execute();

    // Redirect to refresh the page after deletion
    header('Location: cart.php');
    exit();
}

// Fetch the cart items from the database
$sql = "SELECT p.name, p.price, c.quantity, p.id as product_id
        FROM cart c 
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total price
$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
    <link rel="stylesheet" href="cart.css"> <!-- Link to your CSS file -->
</head>
<body>

<h1>Your Cart</h1>

<?php if ($cart_items): ?>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td>$<?php echo $item['price']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
                    <td>
                        <!-- Link to remove the item from the cart -->
                        <a href="cart.php?remove_item=<?php echo $item['product_id']; ?>" onclick="return confirm('Are you sure you want to remove this item from your cart?')">Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Total Price: $<?php echo $total_price; ?></h2>
<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>

</body>
</html>
