<link rel="stylesheet" href="forget.css">
<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(50));
        $expiration = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token expires in 1 hour

        // Store the token and expiration in the database
        $sql = "UPDATE users SET reset_token = :token, reset_expiration = :expiration WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiration', $expiration);
        $stmt->bindParam(':email', $email);
        

        // Send the reset email
$reset_link = "http://yourwebsite.com/reset.php?token=$token";
$subject = "Password Reset Request";
$message = "Click the link to reset your password: <a href='$reset_link'>$reset_link</a>";
$headers = "From: no-reply@yourwebsite.com\r\n";
$headers .= "Reply-To: support@yourwebsite.com\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

if (mail("your_email@gmail.com", "Test Email", "This is a test email.")) {
    echo "Mail sent successfully.";
} else {
    echo "Mail failed to send.";
}
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
</head>
<body>

<h2>Forget Password</h2>

<form action="forget.php" method="post">
    <label for="email">Email:</label>
    <input type="email" name="email" required><br>

    <input type="submit" value="Send Reset Link">
</form>

</body>
</html>
