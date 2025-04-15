<?php
session_start();
require_once 'db_connection.php';

header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit;
}

$userId = $_SESSION['user_id'];
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Anonymous'; 
$postId = isset($_POST['postid']) ? intval($_POST['postid']) : null;

if ($postId) {
    // Check if the post is already liked by the user
    $check_like_query = "SELECT * FROM `like` WHERE userId = ? AND postid = ?";
    $stmt = $conn->prepare($check_like_query);
    if ($stmt === false) {
        echo json_encode(["error" => "Database error: " . $conn->error]);
        exit;
    }

    $stmt->bind_param("ii", $userId, $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Unlike the post
        $delete_query = "DELETE FROM `like` WHERE userId = ? AND postid = ?";
        $delete_stmt = $conn->prepare($delete_query);
        if ($delete_stmt === false) {
            echo json_encode(["error" => "Database error: " . $conn->error]);
            exit;
        }
        $delete_stmt->bind_param("ii", $userId, $postId);
        if ($delete_stmt->execute()) {
            echo json_encode(["status" => "unliked", "postid" => $postId]);
        } else {
            echo json_encode(["error" => "Failed to unlike the post"]);
        }
    } else {
        // Like the post with username
        $insert_query = "INSERT INTO `like` (userId, postid, username) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        if ($insert_stmt === false) {
            echo json_encode(["error" => "Database error: " . $conn->error]);
            exit;
        }
        $insert_stmt->bind_param("iis", $userId, $postId, $username);
        if ($insert_stmt->execute()) {
            echo json_encode(["status" => "liked", "postid" => $postId]);
        } else {
            echo json_encode(["error" => "Failed to like the post"]);
        }
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid post ID"]);
}
?>
