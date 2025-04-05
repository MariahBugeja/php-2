<?php
session_start();
require_once 'db_connection.php';
include 'includes/header.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM user WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    $profilePic = $_FILES['profile_pic'];

    if (getimagesize($profilePic['tmp_name'])) {
        $targetDirectory = "uploads/";
        $fileExtension = pathinfo($profilePic['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid() . "." . $fileExtension; 

        if (move_uploaded_file($profilePic['tmp_name'], $targetDirectory . $newFileName)) {
            $sql = "UPDATE user SET profile_picture = ? WHERE userid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $newFileName, $user_id);

            if ($stmt->execute()) {
                header("Location: profile.php");
                exit();
            } else {
                echo "Error updating profile picture.";
            }
        } else {
            echo "Failed to upload profile picture.";
        }
    } else {
        echo "File is not a valid image.";
    }
}

$conn->close();
?>
