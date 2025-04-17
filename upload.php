<?php
session_start();
require_once 'db_connection.php';
include 'includes/header.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get common form values
$type = $_POST['type'] ?? '';
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$user_id = $_SESSION['user_id'];

$image_path = 'uploads/' . $image_name;

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    $image_name = basename($_FILES['image']['name']);
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $upload_dir = 'uploads/';
    $image_path = $upload_dir . $image_name;

    if (!move_uploaded_file($image_tmp_name, $image_path)) {
        die("Failed to upload image. Check the 'uploads/' folder permissions.");
    }
} else {
    die("No image uploaded or there was an error.");
}

// Insert data based on type
if ($type === 'recipe') {
    $ingredients = $_POST['ingredients'] ?? '';

    $sql = "INSERT INTO postrecipe (title, description, ingredients, userid, image)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssis", $title, $description, $ingredients, $user_id, $image_path);

} elseif ($type === 'pin') {
    $type_of_food = $_POST['typeoffood'] ?? '';

    $sql = "INSERT INTO post (title, description, image, userid, typeoffood)
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssis", $title, $description, $image_path, $user_id, $type_of_food);

} else {
    die("Invalid post type.");
}

// Execute and redirect
if ($stmt->execute()) {
    header("Location: index.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
