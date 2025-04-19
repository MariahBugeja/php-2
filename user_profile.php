<?php
session_start();
require_once 'db_connection.php';
include 'includes/header.php'; 

if (!isset($_GET['userid'])) {
    echo "User ID not provided.";
    exit;
}

$profile_user_id = $_GET['userid'];

$user_query = "SELECT * FROM user WHERE userid = ?";
$user_stmt = $conn->prepare($user_query);

if (!$user_stmt) {
    die("Prepare failed: " . $conn->error);
}

$user_stmt->bind_param("i", $profile_user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    echo "User not found.";
    exit;
}

$user = $user_result->fetch_assoc();

$created_recipe_sql = "SELECT * FROM postrecipe WHERE userid = ?";
$created_recipe_stmt = $conn->prepare($created_recipe_sql);
$created_recipe_stmt->bind_param("i", $profile_user_id);
$created_recipe_stmt->execute();
$created_recipes_result = $created_recipe_stmt->get_result();

$isFollowing = false;
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $profile_user_id) {
    $check_follow_query = "SELECT * FROM follow WHERE Userid = ? AND FollowedUserid = ?";
    $check_follow_stmt = $conn->prepare($check_follow_query);
    $check_follow_stmt->bind_param("ii", $_SESSION['user_id'], $profile_user_id);
    $check_follow_stmt->execute();
    $check_follow_result = $check_follow_stmt->get_result();
    $isFollowing = ($check_follow_result->num_rows > 0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($user['username']) ?>'s Profile</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
  
</head>
<body>

<div class="specific-profile-container">
    <div class="specific-profile-header">
        <div class="specific-profile-picture">
            <?php
                $profile_picture_path = !empty($user['ProfilePicture']) ? 'uploads/' . $user['ProfilePicture'] : 'assets/image.png';
            ?>
            <img src="<?= $profile_picture_path ?>" alt="Profile Picture">
        </div>
        <h2><?= htmlspecialchars($user['username']) ?>'s Profile</h2>
        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
        <p><?= !empty($user['about']) ? nl2br(htmlspecialchars($user['about'])) : "No bio yet" ?></p>

        <?php
        $avg_query = "SELECT AVG(rating) AS avg_rating FROM user_ratings WHERE rated_userid = ?";
        $avg_stmt = $conn->prepare($avg_query);
        $avg_stmt->bind_param("i", $profile_user_id);
        $avg_stmt->execute();
        $avg_result = $avg_stmt->get_result();
        $avg_row = $avg_result->fetch_assoc();
        $avg_rating = round($avg_row['avg_rating'], 1);
        ?>
        <p>User Rating: <?= $avg_rating ? "$avg_rating / 5 ⭐" : "Not yet rated" ?></p>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $profile_user_id): ?>
            <div class="interaction-buttons">
                <form method="POST" action="follow_user.php">
                    <input type="hidden" name="followed_user_id" value="<?= $profile_user_id ?>">
                    <button type="submit" name="follow" class="<?= $isFollowing ? "specific-unfollow-btn" : "specific-follow-btn" ?>">
                        <?= $isFollowing ? "Unfollow" : "Follow" ?>
                    </button>
                </form>
                <form method="POST" action="chat.php?receiver_id=<?= $profile_user_id ?>">
                    <button type="submit" name="message" class="specific-message-btn">Send Message</button>
                </form>
            </div>

            <form method="POST" action="rating_user.php" class="rating-form">
                <input type="hidden" name="rated_userid" value="<?= $profile_user_id ?>">
                <label for="rating">Rate this user:</label>
                <select name="rating" id="rating" required>
                    <option value="">Choose</option>
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> Star<?= $i > 1 ? 's' : '' ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit">Submit Rating</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="specific-toggle-buttons">
        <button id="specificPostButton" class="active">Posts</button>
        <button id="specificRecipeButton">Recipes</button>
    </div>

    <div class="specific-posts-container">
        <div class="specific-created-posts">
            <h3>Created Posts:</h3>
            <div class="specific-grid-container">
                <?php
                $post_query = "SELECT * FROM post WHERE Userid = ?";
                $post_stmt = $conn->prepare($post_query);
                $post_stmt->bind_param("i", $profile_user_id);
                $post_stmt->execute();
                $post_result = $post_stmt->get_result();

                while ($post = $post_result->fetch_assoc()) {
                    echo "<div class='specific-grid-item'>";
                    echo "<a href='post.php?postid=" . $post['postId'] . "'>";
                    echo "<img src='" . htmlspecialchars($post['image']) . "' alt='Post Image'>";
                    echo "</a>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="specific-created-recipes">
            <h3>Created Recipes:</h3>
            <div class="specific-grid-container">
                <?php if ($created_recipes_result->num_rows > 0): ?>
                    <?php while ($recipe = $created_recipes_result->fetch_assoc()): ?>
                        <div class="grid-item">
                            <a href="recipe.php?recipeid=<?= $recipe['recipeId']; ?>">
                                <?php if (!empty($recipe['image']) && file_exists($recipe['image'])): ?>
                                    <img src="<?= htmlspecialchars($recipe['image']); ?>" alt="Recipe Image">
                                <?php else: ?>
                                    <p>Image not available</p>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No created recipes found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    const specificPostButton = document.getElementById('specificPostButton');
    const specificRecipeButton = document.getElementById('specificRecipeButton');
    const specificCreatedPosts = document.querySelector('.specific-created-posts');
    const specificCreatedRecipes = document.querySelector('.specific-created-recipes');

    document.addEventListener('DOMContentLoaded', function () {
        specificPostButton.classList.add('active');
        specificRecipeButton.classList.remove('active');
        specificCreatedPosts.style.display = 'block';
        specificCreatedRecipes.style.display = 'none';
    });

    specificPostButton.addEventListener('click', function () {
        specificPostButton.classList.add('active');
        specificRecipeButton.classList.remove('active');
        specificCreatedPosts.style.display = 'block';
        specificCreatedRecipes.style.display = 'none';
    });

    specificRecipeButton.addEventListener('click', function () {
        specificRecipeButton.classList.add('active');
        specificPostButton.classList.remove('active');
        specificCreatedPosts.style.display = 'none';
        specificCreatedRecipes.style.display = 'block';
    });
</script>

</body>
</html>
