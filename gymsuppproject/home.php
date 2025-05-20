<?php
session_start();
require_once 'db.php';

// Fetch products from the database (limit to 16 products)
$sql = "SELECT * FROM products LIMIT 16";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<h1 align="center">GYM SUPPLEMENTS SHOP</h1>
<body>
    <div class="navbar">
        <a href="home.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <a href="cart.php">Cart</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="signup.php">Sign Up</a>
        <?php endif; ?>
    </div>

    <h1>Our Products</h1>
    <?php if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
    <p align="center">Please <a href="login.php">Login</a> to add to cart.</p>
    <?php endif; ?> 
    <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($product['image']); ?>" 
    <div class="products-container">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <!-- Use image.php to dynamically fetch the image -->
                <img src="data:image/jpeg;base64,<?php echo htmlspecialchars($product['image']); ?>" 
     class="product-image">
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                    <span class="cart-emoji" title="Add to Cart">🛒</span>



                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                        <form action="add_to_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="number" name="quantity" value="1" min="1" required>
                            <input type="submit" value="Add to Cart">
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

       


</body>
</html>
