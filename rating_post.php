<?php
session_start();
require 'db_connection.php'; 

if (isset($_POST['postid'], $_POST['rating']) && isset($_SESSION['userid'])) {
    $userid = $_SESSION['userid'];
    $postid = $_POST['postid'];
    $rating = (int) $_POST['rating']; 

    if (empty($userid)) {
        echo "User is not logged in.";
        exit; 
    }

    $check = $conn->prepare("SELECT * FROM post_ratings WHERE userid = ? AND postid = ?");
    $check->bind_param("ii", $userid, $postid);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $update = $conn->prepare("UPDATE post_ratings SET rating = ?, created_at = CURRENT_TIMESTAMP WHERE userid = ? AND postid = ?");
        $update->bind_param("iii", $rating, $userid, $postid);
        $update->execute();
        echo "Your rating has been updated!";
    } else {
        $stmt = $conn->prepare("INSERT INTO post_ratings (userid, postid, rating, created_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)");
        $stmt->bind_param("iii", $userid, $postid, $rating);
        $stmt->execute();
        echo "Thank you for your rating!";
    }
} else {
    if (!isset($_SESSION['userid'])) {
        echo "User is not logged in.";
    } elseif (!isset($_POST['postid']) || !isset($_POST['rating'])) {
        echo "Missing postid or rating.";
    } else {
        echo "There was an unexpected error.";
    }
}
?>
