<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment_id = intval($_POST['comment_id']);
    $new_content = trim($_POST['new_content']);
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        die("You must be logged in to edit a comment.");
    }

    if (empty($new_content)) {
        die("Comment content cannot be empty.");
    }

    echo "Updating comment ID: " . $comment_id . "<br>";
    echo "New Content: " . htmlspecialchars($new_content) . "<br>";

    $sql = "UPDATE comment SET content = ? WHERE commentId = ? AND userid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $new_content, $comment_id, $user_id);

    if ($stmt->execute()) {
        header("Location: post.php?postid=" . $_POST['postid']); 
        exit();
    } else {
        die("Error updating comment: " . $conn->error);
    }
}
?>
