<?php
session_start();
require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Validate
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } else {
        // Sanitize
        $username = mysqli_real_escape_string($conn, $username);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Username already exists!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
 <body>
    <div class="container">
    <h2>Register</h2>
    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username"><br><br>
        <input type="password" name="password" placeholder="Password"><br><br>
        <button type="submit">Register</button>
    </form>
    <a href="login.php">Already have an account? Login</a>
    </div>
</body>
</html>
