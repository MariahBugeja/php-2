<?php
session_start();
require_once 'db_connection.php';

if (!isset($_POST['postid'])) {
    die("Post ID is missing.");
}

$post_id = (int)$_POST['postid'];
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch the post URL to share
$sql = "SELECT * FROM post WHERE postid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$post_result = $stmt->get_result();

if ($post_result->num_rows > 0) {
    $post = $post_result->fetch_assoc();
    $post_url = "http://localhost:8888/php-2/post.php?postid=" . $post_id; 

    // Redirecting to Facebook share URL for simplicity
    $facebook_url = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($post_url);
    
    // Redirect the user to the Facebook share link
    header("Location: " . $facebook_url);
    exit;
} else {
    die("Post not found.");
}
?>
