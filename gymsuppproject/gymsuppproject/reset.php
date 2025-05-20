<link rel="stylesheet" href="style.css">
<?php
require_once 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid
    $sql = "SELECT * FROM users WHERE reset_token = :token AND reset_expiration > NOW() LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = $_POST['new_password'];
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password and clear the reset token
            $sql = "UPDATE users SET password = :password, reset_token = NULL, reset_expiration = NULL WHERE id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':user_id', $user['id']);
            $stmt->execute();

            echo "Your password has been successfully reset.";
        }
    } else {
        echo "Invalid or expired token.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>

<h2>Reset Password</h2>

<form action="reset.php?token=<?php echo $_GET['token']; ?>" method="post">
    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" required><br>

    <input type="submit" value="Reset Password">
</form>

</body>
</html>
