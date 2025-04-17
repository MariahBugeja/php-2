<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['recipe_id'])) {
    $user_id = $_SESSION['user_id'];
    $recipe_id = intval($_POST['recipe_id']);

    // Check if already saved
    $check_sql = "SELECT * FROM save WHERE userid = ? AND recipeid = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $recipe_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        $insert_sql = "INSERT INTO save (userid, recipeid) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $user_id, $recipe_id);
        $insert_stmt->execute();
    }

    header("Location: recipe.php?recipeid=$recipe_id");
    exit();
}
?>
