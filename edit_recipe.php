<?php
session_start();
require_once 'db_connection.php';

if (!isset($_GET['recipeid']) || empty($_GET['recipeid'])) {
    die("Recipe ID is missing or invalid.");
}

$recipe_id = (int)$_GET['recipeid'];
if ($recipe_id <= 0) {
    die("Invalid Recipe ID.");
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$sql = "SELECT * FROM postrecipe WHERE recipeid = ? AND userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $recipe_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$recipe = $result->fetch_assoc();

if (!$recipe) {
    die("Recipe not found or you don't have permission to edit it.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Update the post in the database 
    $update_sql = "UPDATE postrecipe SET title = ?, description = ? WHERE recipeid = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $title, $description, $recipe_id);

    if ($update_stmt->execute()) {
        header("Location: recipe.php?recipeid=" . $recipe_id);
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
    <title>Edit Recipe</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>

<header class="header">
    <?php include 'includes/header.php'; ?>
</header>

<div class="edit-recipe-container">
    <h2>Edit Recipe</h2>
    
    <!-- Image Preview Section -->
    <div class="edit-recipe-image-preview-container">
        <img src="<?php echo htmlspecialchars($recipe['image']); ?>" alt="Recipe Image" class="edit-recipe-image-preview">
    </div>

    <form action="edit_recipe.php?recipeid=<?php echo $recipe_id; ?>" method="POST">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>
        <button type="submit">Update Recipe</button>
    </form>
</div>

</body>
</html>
