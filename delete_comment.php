<?php
session_start();
require_once 'db_connection.php';

if (!isset($_POST['comment_id']) || !isset($_SESSION['user_id'])) {
    die("Unauthorized action.");
}

$comment_id = intval($_POST['comment_id']);
$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM comment WHERE commentid = ? AND userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $comment_id, $user_id);

if ($stmt->execute()) {
    header("Location: ".$_SERVER['HTTP_REFERER']);
} else {
    echo "Error deleting comment.";
}

$conn->close();
?>
