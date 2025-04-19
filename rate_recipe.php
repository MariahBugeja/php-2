<?php
session_start();
require 'db_connection.php';

if (isset($_POST['recipe_id'], $_POST['rating']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $recipe_id = $_POST['recipe_id'];
    $rating = (int) $_POST['rating'];

    $check = $conn->prepare("SELECT * FROM recipe_ratings WHERE user_id = ? AND recipe_id = ?");
    $check->bind_param("ii", $user_id, $recipe_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $update = $conn->prepare("UPDATE recipe_ratings SET rating = ?, rated_at = CURRENT_TIMESTAMP WHERE user_id = ? AND recipe_id = ?");
        $update->bind_param("iii", $rating, $user_id, $recipe_id);
        $update->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO recipe_ratings (user_id, recipe_id, rating) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $recipe_id, $rating);
        $stmt->execute();
    }

    echo "success";
} else {
    echo "error";
}
?>
