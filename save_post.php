<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['post_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = intval($_POST['post_id']);

    // Check if already saved
    $check_sql = "SELECT * FROM save WHERE userid = ? AND postid = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $post_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        $insert_sql = "INSERT INTO save (userid, postid) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $user_id, $post_id);
        $insert_stmt->execute();
    }

    header("Location: post.php?postid=$post_id");
    exit();
}
?>
