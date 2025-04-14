<?php
session_start();

require_once 'db_connection.php';

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

// Use the correct session variable names
$userId = $_SESSION['user_id'];
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anonymous'; 

$postId = isset($_POST['postid']) ? intval($_POST['postid']) : null;

if ($postId) {
    $query = "INSERT INTO `like` (UserId, postid, username) VALUES (?, ?, ?)";

    if ($conn) {
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            echo json_encode(["error" => "Database error: " . $conn->error]);
            exit;
        }

        $stmt->bind_param("iis", $userId, $postId, $username);
        if ($stmt->execute()) {
            echo json_encode(["status" => "liked", "postid" => $postId]);
        } else {
            echo json_encode(["error" => "Failed to like the post"]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Database connection failed"]);
    }
} else {
    echo json_encode(["error" => "Invalid post ID"]);
}
?>
