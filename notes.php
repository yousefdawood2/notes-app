<?php
session_start();
require 'db.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

// Delete note
if (isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    mysqli_query($conn, "DELETE FROM notes WHERE id=$id AND user_id=$user_id");
    header("Location: notes.php");
    exit();
}

// Get all notes
$sql = "SELECT * FROM notes WHERE user_id=$user_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Notes</title>
    <link rel="stylesheet" href="style.css">
</head>
 <body>
    <div class="notes-container">
        <div class="header">
            <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
            <div>
                <a href="create_note.php" class="btn-add">+ Add Note</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
        <h3>My Notes</h3>
        <?php while ($note = mysqli_fetch_assoc($result)): ?>
            <div class="note-card">
                <h4><?php echo htmlspecialchars($note["title"]); ?></h4>
                <p><?php echo htmlspecialchars($note["content"]); ?></p>
                <small><?php echo $note["created_at"]; ?></small>
                <div class="note-actions">
                    <a href="edit_note.php?id=<?php echo $note["id"]; ?>">Edit</a>
                    <a href="notes.php?delete=<?php echo $note["id"]; ?>" 
                       class="delete" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>