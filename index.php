<?php
session_start();
require_once 'db_connection.php';
include 'includes/header.php';

// Fetch posts
$posts_query = "SELECT * FROM post";
$posts_result = $conn->query($posts_query);

// Fetch recipes
$recipes_query = "SELECT * FROM postrecipe"; // corrected table name
$recipes_result = $conn->query($recipes_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Home Page</title>
  <link rel="stylesheet" href="styles.css" />
  <style>
    .home .post-grid {
        column-count: 3;
        column-gap: 20px;
        padding: 2%;
    }

    .home .card {
        background-color: white;
        display: inline-block;
        margin-bottom: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        width: 100%;
        break-inside: avoid;
    }

    .home .card img {
        width: 100%;
        height: auto;
        object-fit: cover;
        display: block;
    }

    .home .card-body {
        padding: 10px;
    }

    .home .card-body h3 {
        margin: 0;
        font-size: 1rem;
        color: #333;
    }

    .home .card-body p {
        font-size: 0.9rem;
        color: #666;
    }
  </style>
</head>
<body class="home">
  <div class="post-grid">
    <!-- Display Posts -->
    <?php while($post = $posts_result->fetch_assoc()): ?>
        <div class="card">
            <a href="post.php?postid=<?php echo $post['postId']; ?>">
                <img src="<?php echo $post['image']; ?>" alt="Post Image">
            </a>
            <div class="card-body">
                <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                <p><?php echo htmlspecialchars($post['description']); ?></p>
            </div>
        </div>
    <?php endwhile; ?>

    <!-- Display Recipes -->
    <?php while($recipe = $recipes_result->fetch_assoc()): ?>
        <div class="card">
            <a href="recipe.php?recipeid=<?php echo $recipe['recipeId']; ?>">
                <img src="<?php echo $recipe['image']; ?>" alt="Recipe Image">
            </a>
            <div class="card-body">
                <h3><?php echo htmlspecialchars($recipe['title']); ?></h3>
                <p><?php echo htmlspecialchars($recipe['description']); ?></p>
            </div>
        </div>
    <?php endwhile; ?>
  </div>
</body>
</html>
