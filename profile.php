<?php
session_start();
require_once 'db_connection.php';
include 'includes/header.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM user WHERE userid = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("MySQL prepare error: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$created_sql = "SELECT * FROM post WHERE userid = ?";
$created_stmt = $conn->prepare($created_sql);
$created_stmt->bind_param("i", $user_id);
$created_stmt->execute();
$created_posts_result = $created_stmt->get_result();

$created_recipe_sql = "SELECT * FROM postrecipe WHERE userid = ?";
$created_recipe_stmt = $conn->prepare($created_recipe_sql);
$created_recipe_stmt->bind_param("i", $user_id);
$created_recipe_stmt->execute();
$created_recipes_result = $created_recipe_stmt->get_result();

// Fetch saved posts
$saved_sql = "SELECT post.* FROM save 
              JOIN post ON save.postid = post.postid 
              WHERE save.userid = ?";
$saved_stmt = $conn->prepare($saved_sql);
if ($saved_stmt === false) {
    die("Prepare failed for saved posts: " . $conn->error);
}
$saved_stmt->bind_param("i", $user_id);
$saved_stmt->execute();
$saved_posts_result = $saved_stmt->get_result();

// Fetch saved recipes
$saved_recipe_sql = "SELECT postrecipe.* FROM save 
                     JOIN postrecipe ON save.recipeid = postrecipe.recipeid 
                     WHERE save.userid = ?";
$saved_recipe_stmt = $conn->prepare($saved_recipe_sql);
if ($saved_recipe_stmt === false) {
    die("Prepare failed for saved recipes: " . $conn->error);
}
$saved_recipe_stmt->bind_param("i", $user_id);
$saved_recipe_stmt->execute();
$saved_recipes_result = $saved_recipe_stmt->get_result();



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="profile-header">
    <div class="profile-picture">
        <form action="upload_profile_pic.php" method="POST" enctype="multipart/form-data">
            <label for="profile_pic">
                <?php if (!empty($user['profile_picture']) && file_exists("uploads/" . $user['profile_picture'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>?<?php echo time(); ?>" alt="Profile Picture" class="profile-img">
                <?php else: ?>
                    <img src="assets/image.jpg" alt="Description of image">
                    <?php endif; ?>
            </label>
            <input type="file" name="profile_pic" id="profile_pic" accept="image/*" class="hidden-input" onchange="this.form.submit();">
        </form>
    </div>
    
    <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?></h2>
    <p><?php echo htmlspecialchars($user['email']); ?></p>

    <div class="about-section">
        <p>
            <?php 
                echo !empty($user['about']) ? nl2br(htmlspecialchars($user['about'])) : "No bio yet";
            ?>
        </p>
    </div>

    <form action="Login.php" method="POST">
        <button type="submit" class="logout-button">Logout</button>
        <a href="edit-profile.php" class="btn">Edit Profile</a>
    </form>
</header>

<div class="toggle-buttons">
    <button id="showCreatedPosts" onclick="togglePosts('created')" class="active">Created Posts</button>
    <button id="showCreatedRecipes" onclick="togglePosts('recipe')">Created Recipes</button>
    <button id="showSavedPosts" onclick="togglePosts('saved')">Save</button>
</div>

<!-- Created Posts -->
<div class="created-posts" style="display: block;">
    <h3>Created Posts</h3>
    <?php if ($created_posts_result->num_rows > 0): ?>
        <div class="grid-container">
            <?php while ($post = $created_posts_result->fetch_assoc()): ?>
                <div class="grid-item">
                    <a href="post.php?postid=<?php echo $post['postId']; ?>">
                        <?php 
                        if (!empty($post['image']) && file_exists($post['image'])): ?>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image">
                        <?php else: ?>
                            <p>Image not available</p>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No created posts found.</p>
    <?php endif; ?>
</div>

<!-- Created Recipes -->
<div class="created-recipes" style="display: none;">
    <h3>Created Recipes</h3>
    <?php if ($created_recipes_result->num_rows > 0): ?>
        <div class="grid-container">
            <?php while ($recipe = $created_recipes_result->fetch_assoc()): ?>
                <div class="grid-item">
                    <a href="recipe.php?recipeid=<?php echo $recipe['recipeId']; ?>"> <!-- Adjust link to your recipe page -->
                        <?php 
                        if (!empty($recipe['image']) && file_exists($recipe['image'])): ?>
                            <img src="<?php echo htmlspecialchars($recipe['image']); ?>" alt="Recipe Image">
                        <?php else: ?>
                            <p>Image not available</p>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No created recipes found.</p>
    <?php endif; ?>
</div>

<!-- Saved Posts + Recipes -->
<div class="saved-posts" style="display: none;">
    <h3>Saved Posts & Recipes</h3>

    <div class="grid-container">
        <?php if ($saved_posts_result->num_rows > 0): ?>
            <?php while ($saved_post = $saved_posts_result->fetch_assoc()): ?>
                <div class="grid-item">
                    <a href="post.php?postid=<?= $saved_post['postId'] ?>">
                        <?php if (!empty($saved_post['image']) && file_exists($saved_post['image'])): ?>
                            <img src="<?= htmlspecialchars($saved_post['image']) ?>" alt="Saved Post Image">
                        <?php else: ?>
                            <p>Image not available</p>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

        <?php if ($saved_recipes_result->num_rows > 0): ?>
            <?php while ($saved_recipe = $saved_recipes_result->fetch_assoc()): ?>
                <div class="grid-item">
                    <a href="recipe.php?recipeid=<?= $saved_recipe['recipeId'] ?>">
                        <?php if (!empty($saved_recipe['image']) && file_exists($saved_recipe['image'])): ?>
                            <img src="<?= htmlspecialchars($saved_recipe['image']) ?>" alt="Saved Recipe Image">
                        <?php else: ?>
                            <p>Image not available</p>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

        <?php if ($saved_posts_result->num_rows === 0 && $saved_recipes_result->num_rows === 0): ?>
            <p>No saved posts or recipes found.</p>
        <?php endif; ?>
    </div>
</div>


    <!-- Saved Recipes -->
    <?php if ($saved_recipes_result->num_rows > 0): ?>
        <div class="grid-container">
            <?php while ($saved_recipe = $saved_recipes_result->fetch_assoc()): ?>
                <div class="grid-item">
                    <a href="recipe.php?recipeid=<?php echo $saved_recipe['recipeId']; ?>">
                        <?php 
                        if (!empty($saved_recipe['image']) && file_exists($saved_recipe['image'])): ?>
                            <img src="<?php echo htmlspecialchars($saved_recipe['image']); ?>" alt="Saved Recipe Image">
                        <?php else: ?>
                            <p>Image not available</p>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No saved recipes found.</p>
    <?php endif; ?>
</div>


<script>
    function togglePosts(type) {
        const createdPosts = document.querySelector('.created-posts');
        const createdRecipes = document.querySelector('.created-recipes');
        const savedPosts = document.querySelector('.saved-posts');
        const createdBtn = document.getElementById('showCreatedPosts');
        const createdRecipeBtn = document.getElementById('showCreatedRecipes');
        const savedBtn = document.getElementById('showSavedPosts');

        if (type === 'created') {
            createdPosts.style.display = 'block';
            createdRecipes.style.display = 'none';
            savedPosts.style.display = 'none';
            createdBtn.classList.add('active');
            createdRecipeBtn.classList.remove('active');
            savedBtn.classList.remove('active');
        } else if (type === 'recipe') {
            createdPosts.style.display = 'none';
            createdRecipes.style.display = 'block';
            savedPosts.style.display = 'none';
            createdBtn.classList.remove('active');
            createdRecipeBtn.classList.add('active');
            savedBtn.classList.remove('active');
        } else {
            createdPosts.style.display = 'none';
            createdRecipes.style.display = 'none';
            savedPosts.style.display = 'block';
            createdBtn.classList.remove('active');
            createdRecipeBtn.classList.remove('active');
            savedBtn.classList.add('active');
        }
    }
</script>

</body>
</html>
