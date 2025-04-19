<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to rate.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rater_id = $_SESSION['user_id'];
    $rated_id = $_POST['rated_userid'];
    $rating = intval($_POST['rating']);

    if ($rater_id == $rated_id || $rating < 1 || $rating > 5) {
        die("Invalid rating.");
    }

    $sql = "INSERT INTO user_ratings (rater_userid, rated_userid, rating)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE rating = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $rater_id, $rated_id, $rating, $rating);

    if ($stmt->execute()) {
        header("Location: profile.php?userid=$rated_id");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
