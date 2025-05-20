<?php
require_once 'db.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch the image from the database
    #$sql = "SELECT image FROM products WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
    #$stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product && !empty($product['image'])) {
        // Output the image in an HTML <img> tag
        echo '<img src="data:image/jpeg;base64,' . htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8') . '" alt="Image" />';
    } else {
        echo "Image not found.";
    }
} else {
    echo "Invalid product ID.";
}
?>
