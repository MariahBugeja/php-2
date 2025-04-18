<?php
session_start();
require_once 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to delete a post.");
}

if (!isset($_POST['postid']) || empty($_POST['postid'])) {
    die("Post ID is missing or invalid.");
}

$post_id = (int)$_POST['postid'];

$sql = "SELECT Userid FROM post WHERE postid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

var_dump($_SESSION['user_id']);
var_dump($post);

if ($post['Userid'] !== $_SESSION['user_id']) {
    die("You are not authorized to delete this post.");
}

$delete_comments_sql = "DELETE FROM comment WHERE postid = ?";
$delete_comments_stmt = $conn->prepare($delete_comments_sql);
$delete_comments_stmt->bind_param("i", $post_id);
$delete_comments_stmt->execute();

// Delete associated likes
$delete_likes_sql = "DELETE FROM `like` WHERE postid = ?";
$delete_likes_stmt = $conn->prepare($delete_likes_sql);
$delete_likes_stmt->bind_param("i", $post_id);
$delete_likes_stmt->execute();

$delete_saves_sql = "DELETE FROM save WHERE postid = ?";
$delete_saves_stmt = $conn->prepare($delete_saves_sql);
$delete_saves_stmt->bind_param("i", $post_id);
$delete_saves_stmt->execute();

// Delete the post
$delete_post_sql = "DELETE FROM post WHERE postid = ?";
$delete_post_stmt = $conn->prepare($delete_post_sql);
$delete_post_stmt->bind_param("i", $post_id);

if ($delete_post_stmt->execute()) {
    header("Location: index.php");
    exit;
} else {
    die("Error deleting post: " . $conn->error);
}
?>
