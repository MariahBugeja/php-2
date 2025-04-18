<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginpage.php");
    exit;
}

if (isset($_POST['follow'])) {
    $follower_user_id = $_SESSION['user_id'];
    $followed_user_id = $_POST['followed_user_id'];

    $check_follow_query = "SELECT * FROM follow WHERE Userid = ? AND FollowedUserid = ?";
    $check_follow_stmt = $conn->prepare($check_follow_query);
    $check_follow_stmt->bind_param("ii", $follower_user_id, $followed_user_id);
    $check_follow_stmt->execute();
    $check_follow_result = $check_follow_stmt->get_result();

    if ($check_follow_result->num_rows > 0) {
        $unfollow_query = "DELETE FROM follow WHERE Userid = ? AND FollowedUserid = ?";
        $unfollow_stmt = $conn->prepare($unfollow_query);
        $unfollow_stmt->bind_param("ii", $follower_user_id, $followed_user_id);
        $unfollow_stmt->execute();
    } else {
        $follow_query = "INSERT INTO follow (Userid, FollowedUserid, timestamp) VALUES (?, ?, NOW())";
        $follow_stmt = $conn->prepare($follow_query);
        $follow_stmt->bind_param("ii", $follower_user_id, $followed_user_id);
        $follow_stmt->execute();
    }

    header("Location: user_profile.php?userid=" . $followed_user_id);
    exit;
}
?>
