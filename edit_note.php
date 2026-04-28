<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$error = "";

// Get note
$id = (int)$_GET["id"];
$sql = "SELECT * FROM notes WHERE id=$id AND user_id=$user_id";
$result = mysqli_query($conn, $sql);
$note = mysqli_fetch_assoc($result);

if (!$note) {
    header("Location: notes.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    // Validate
    if (empty($title) || empty($content)) {
        $error = "Please fill in all fields";
    } else {
        // Sanitize
        $title = mysqli_real_escape_string($conn, $title);
        $content = mysqli_real_escape_string($conn, $content);

        $sql = "UPDATE notes SET title='$title', content='$content' WHERE id=$id AND user_id=$user_id";
        
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
    <title>Edit Note</title>
    <link rel="stylesheet" href="style.css">
</head>
 <body>
    <div class="container">
        <h2>Edit Note</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="title" value="<?php echo htmlspecialchars($note['title']); ?>">
            <textarea name="content" rows="5"><?php echo htmlspecialchars($note['content']); ?></textarea>
            <button type="submit">Update Note</button>
        </form>
        <a href="notes.php">Back</a>
    </div>
</body>
</html>