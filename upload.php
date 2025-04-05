<?php
session_start();
require_once 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$type = $_POST['type']; 
$title = $_POST['title'];
$description = $_POST['description'];
$user_id = $_SESSION['user_id']; 

$image_path = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);  
    }
    
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_type = $_FILES['image']['type'];

    $upload_dir = 'uploads/';
    $image_path = $upload_dir . basename($image_name);

    if (!move_uploaded_file($image_tmp_name, $image_path)) {
        die("Failed to upload image. Check the permissions of the 'uploads/' directory.");
    }
}

if ($type == 'recipe') {
    $ingredients = $_POST['ingredients'];
    
    $insert_recipe_sql = "INSERT INTO createrecipe (title, description, ingredients, userid, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_recipe_sql);

    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error); 
    }

    $stmt->bind_param("sssis", $title, $description, $ingredients, $user_id, $image_path);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} elseif ($type == 'pin') {
    $type_of_food = $_POST['typeoffood'];
    
    $insert_pin_sql = "INSERT INTO post (title, description, image, userid, typeoffood) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_pin_sql);

    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error); 
    }

    $stmt->bind_param("sssis", $title, $description, $image_path, $user_id, $type_of_food);
    
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();
?>
