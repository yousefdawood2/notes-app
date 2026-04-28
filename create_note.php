<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $user_id = $_SESSION["user_id"];

    // Validate
    if (empty($title) || empty($content)) {
        $error = "Please fill in all fields";
    } else {
        // Sanitize
        $title = mysqli_real_escape_string($conn, $title);
        $content = mysqli_real_escape_string($conn, $content);

        $sql = "INSERT INTO notes (user_id, title, content) VALUES ('$user_id', '$title', '$content')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: notes.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
 <head>
    <title>Create Note</title>
    <link rel="stylesheet" href="style.css">
</head>
 <body>
    <div class="container">
        <h2>Create New Note</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="title" placeholder="Title">
            <textarea name="content" placeholder="Content" rows="5"></textarea>
            <button type="submit">Save Note</button>
        </form>
        <a href="notes.php">Back</a>
    </div>
</body>
</html>