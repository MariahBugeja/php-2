
<?php
session_start();
require_once 'db_connection.php';
include 'includes/header.php';

$posts_query = "SELECT * FROM post";
$posts_result = $conn->query($posts_query);

$recipes_query = "SELECT * FROM postrecipe";
$recipes_result = $conn->query($recipes_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="home">

    

    <div class="post-grid">
        <?php while($post = $posts_result->fetch_assoc()): ?>
            <div class="post">
                <a href="viewpost.php?postid=<?php echo $post['postId']; ?>"> 
                    <img src="<?php echo $post['image']; ?>" alt="Post Image"> 
                </a>
            </div>
        <?php endwhile; ?>

        <div class="post-grid">
    <?php while($recipe = $recipes_result->fetch_assoc()): ?>
        <div class="recipe">
            <a href="viewrecipe.php?recipeid=<?php echo $recipe['recipeId']; ?>"> 
                <img src="<?php echo $recipe['image']; ?>" alt="Recipe Image"> 
            </a>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
