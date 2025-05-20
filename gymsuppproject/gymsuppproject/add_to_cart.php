<?php
session_start();
require_once 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Check if the product already exists in the cart
$sql = "SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':product_id', $product_id);
$stmt->execute();
$product_in_cart = $stmt->fetch(PDO::FETCH_ASSOC);

if ($product_in_cart) {
    // Update the quantity if the product is already in the cart
    $new_quantity = $product_in_cart['quantity'] + $quantity;
    $sql = "UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':quantity', $new_quantity);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
} else {
    // Add the product to the cart
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->execute();
}

// Redirect back to the home page
header('Location: home.php');
exit();
?>
