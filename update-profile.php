<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userid = $_POST['userid'];
$username = $_POST['username'];
$password = $_POST['password'];
$about = $_POST['about'];

$sql = "SELECT COUNT(*) AS count FROM user WHERE username = ? AND userid != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $username, $userid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] > 0) {
    $_SESSION['error'] = "Username is already taken. Please choose a different one.";
    header("Location: edit-profile.php");
    exit();
}

$profile_picture = NULL;

if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
    $target_dir = "uploads/";
    $profile_picture = uniqid() . basename($_FILES["profile_picture"]["name"]);
    $target_file = $target_dir . $profile_picture;
    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
}

if ($profile_picture) {
    $sql = "UPDATE user SET username=?, password=?, about=?, profile_picture=? WHERE userid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $password, $about, $profile_picture, $userid);
} else {
    $sql = "UPDATE user SET username=?, password=?, about=? WHERE userid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $password, $about, $userid);
}

if ($stmt->execute()) {
   
    header("Location: profile.php?userid=$userid");
    exit();
} else {
    echo "Update failed: " . $stmt->error;
}
?>
