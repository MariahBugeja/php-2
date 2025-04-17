<?php
session_start();
require_once 'db_connection.php';

if (!isset($_GET['postid']) || empty($_GET['postid'])) {
    die("Post ID is missing or invalid.");
}

$post_id = (int)$_GET['postid'];
if ($post_id <= 0) {
    die("Invalid Post ID.");
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$sql = "SELECT * FROM post WHERE postid = ? AND userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    die("Post not found or you don't have permission to edit it.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Update the post in the database 
    $update_sql = "UPDATE post SET title = ?, description = ? WHERE postid = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $title, $description, $post_id);

    if ($update_stmt->execute()) {
        header("Location: post.php?postid=" . $post_id);
        exit;
    } else {
        echo "Error updating post: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Post</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<header class="header">
    <?php include 'includes/header.php'; ?>
</header>

<div class="edit-recipe-container">
    <h2>Edit Post</h2>
    
    <!-- Image Preview Section -->
    <div class="edit-recipe-image-preview-container">
                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image" class="edit-recipe-image-preview">
    </div>

    <form action="edit_post.php?postid=<?php echo $post_id; ?>" method="POST">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($post['description']); ?></textarea>
        
        <button type="submit">Update Post</button>
    </form>
</div>

</body>
</html>
