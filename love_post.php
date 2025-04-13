<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['postid'], $_POST['love_status'], $_SESSION['user_id'])) {
        $post_id = $_POST['postid'];
        $user_id = $_SESSION['user_id'];
        $love_status = $_POST['love_status'];

        // Handle the like/unlike action
        if ($love_status === 'unloved') {
            // Insert like into the database
            $query = "INSERT INTO `like` (userid, postid) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $user_id, $post_id);
            $stmt->execute();
        } else {
            // Remove like from the database
            $query = "DELETE FROM `like` WHERE userid = ? AND postid = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $user_id, $post_id);
            $stmt->execute();
        }

        // Redirect back to the post page
        header("Location: post.php?postid=$post_id");
        exit;
    }
} else {
    echo "Invalid request.";
}

?>
