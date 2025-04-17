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

// Get recipe details
$sql = "SELECT postrecipe.*, user.username FROM postrecipe 
        JOIN user ON postrecipe.userid = user.userid 
        WHERE postrecipe.recipeid = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) die("Error preparing query: " . $conn->error);
$stmt->bind_param("i", $recipe_id);
$stmt->execute();
$result = $stmt->get_result();
$recipe = $result->fetch_assoc();
if (!$recipe) die("Recipe not found.");

// Check if the user has loved the post
$user_has_loved = false;
if ($user_id) {
    $check_love_query = $conn->prepare("SELECT * FROM `like` WHERE userid = ? AND recipeid = ?");
    $check_love_query->bind_param("ii", $user_id, $recipe_id);
    $check_love_query->execute();
    $check_love_result = $check_love_query->get_result();
    $user_has_loved = ($check_love_result && $check_love_result->num_rows > 0);
}

// Check if saved
$is_saved = false;
if ($user_id) {
    $saved_stmt = $conn->prepare("SELECT * FROM save WHERE userid = ? AND recipeid = ?");
    $saved_stmt->bind_param("ii", $user_id, $recipe_id);
    $saved_stmt->execute();
    $is_saved = $saved_stmt->get_result()->num_rows > 0;
}

// Save/Unsave
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_recipe']) && $user_id) {
    if ($is_saved) {
        $delete_stmt = $conn->prepare("DELETE FROM save WHERE userid = ? AND recipeid = ?");
        $delete_stmt->bind_param("ii", $user_id, $recipe_id);
        $delete_stmt->execute();
        $is_saved = false;
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO save (userid, recipeid) VALUES (?, ?)");
        $insert_stmt->bind_param("ii", $user_id, $recipe_id);
        $insert_stmt->execute();
        $is_saved = true;
    }
    header("Location: recipe.php?recipeid=" . $recipe_id);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && $user_id) {
    $comment_content = $_POST['comment'];

    $comment_content = htmlspecialchars($comment_content, ENT_QUOTES, 'UTF-8');

    // Insert the comment into the database
    $insert_comment_sql = "INSERT INTO comment (userid, recipeid, content, timestamp) VALUES (?, ?, ?, NOW())";
    $insert_comment_stmt = $conn->prepare($insert_comment_sql);

    if ($insert_comment_stmt === false) {
        die("Error preparing comment insertion query: " . $conn->error);
    }

    $insert_comment_stmt->bind_param("iis", $user_id, $recipe_id, $comment_content);
    
    if ($insert_comment_stmt->execute()) {
        header("Location: recipe.php?recipeid=" . $recipe_id);
        exit;
    } else {
        echo "Error posting comment: " . $conn->error;
    }
}


// Handle comment editing
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_comment']) && isset($_POST['new_content'])) {
    $comment_id = $_POST['comment_id'];
    $new_content = $_POST['new_content'];

    $new_content = htmlspecialchars($new_content, ENT_QUOTES, 'UTF-8');

    // Update the comment in the database
    $update_comment_sql = "UPDATE comment SET content = ? WHERE commentId = ? AND userid = ?";
    $update_comment_stmt = $conn->prepare($update_comment_sql);

    if ($update_comment_stmt === false) {
        die("Error preparing comment update query: " . $conn->error);
    }

    $update_comment_stmt->bind_param("sii", $new_content, $comment_id, $user_id);

    if ($update_comment_stmt->execute()) {
        header("Location: recipe.php?recipeid=" . $recipe_id);
        exit;
    } else {
        echo "Error updating comment: " . $conn->error;
    }
}

$comment_sql = "SELECT comment.commentId, comment.userid, comment.content, comment.timestamp, user.username 
                FROM comment 
                JOIN user ON comment.userid = user.userid 
                WHERE comment.recipeid = ? ORDER BY timestamp DESC";
$comment_stmt = $conn->prepare($comment_sql);
if ($comment_stmt === false) {
    die("Error preparing comment query: " . $conn->error);
}

$comment_stmt->bind_param("i", $recipe_id);
$comment_stmt->execute();
$comments_result = $comment_stmt->get_result();

// Fetch other random posts to display
$random_recipes_sql = "SELECT postrecipe.*, user.username FROM postrecipe 
                     JOIN user ON postrecipe.userid = user.userid 
                     WHERE postrecipe.recipeId != ? 
                     ORDER BY RAND() LIMIT 3";
$random_recipes_stmt = $conn->prepare($random_recipes_sql);
if ($random_recipes_stmt === false) {
    die("Error preparing random posts query: " . $conn->error);
}

$random_recipes_stmt->bind_param("i", $recipe_id);
$random_recipes_stmt->execute();
$random_recipes_result = $random_recipes_stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($recipe['title']); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body class="bodypost">
<header class="header">
    <?php include 'includes/header.php'; ?>
</header>

<div class="post-container">
    <div class="post-wrapper">
        <div class="post-image">
            <img src="<?php echo htmlspecialchars($recipe['image']); ?>" alt="Recipe Image">
        </div>

        <div class="post-content">
            <div class="title-save-wrapper">
                <h2><?php echo htmlspecialchars($recipe['title']); ?></h2>
                <?php if ($user_id): ?>
                    <form action="recipe.php?recipeid=<?php echo $recipe_id; ?>" method="POST">
                        <button type="submit" name="save_recipe" class="save-button">
                            <?php echo $is_saved ? "Saved" : "Save"; ?>
                        </button>
                    </form>
                <?php else: ?>
                    <p>You must be logged in to save recipes.</p>
                <?php endif; ?>
            </div>

            <p><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
            <p class="username">By: <?php echo htmlspecialchars($recipe['username']); ?></p>

            <!-- Like Button -->
            <form method="POST" action="love_recipe.php" class="like-form">
                <input type="hidden" name="recipeid" value="<?php echo $recipe_id; ?>">
                <button 
                    id="loveButton-<?php echo $recipe_id; ?>" 
                    data-liked="<?php echo $user_has_loved ? 'true' : 'false'; ?>"
                    class="fa-heart <?php echo $user_has_loved ? 'fa-solid' : 'fa-regular'; ?>"
                    style="background: none; border: none; font-size: 24px; color: <?php echo $user_has_loved ? 'red' : '#ccc'; ?>; cursor: pointer;">
                </button>
            </form>

            <!-- Comments -->
            <div class="comments-section">
                <h3>Comments</h3>
                <?php while ($comment = $comments_result->fetch_assoc()): ?>
                    <div class="comment">
                        <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                           <?php echo htmlspecialchars($comment['content']); ?>
                        </p>
                        <?php if ($user_id && $comment['userid'] == $user_id): ?>
                            <button onclick="toggleEditForm(<?php echo $comment['commentId']; ?>)">Edit</button>
                            <form action="delete_comment.php" method="POST" style="display:inline;">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['commentId']; ?>">
                                <button type="submit" onclick="return confirm('Delete this comment?');">Delete</button>
                            </form>
                            <form action="recipe.php?recipeid=<?php echo $recipe_id; ?>" method="POST" class="edit-form" id="edit-form-<?php echo $comment['commentId']; ?>" style="display:none;">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['commentId']; ?>">
                                <textarea name="new_content" required><?php echo htmlspecialchars($comment['content']); ?></textarea>
                                <button type="submit" name="edit_comment">Save</button>
                                <button type="button" onclick="toggleEditForm(<?php echo $comment['commentId']; ?>)">Cancel</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>

                <?php if ($user_id): ?>
                    <form action="recipe.php?recipeid=<?php echo $recipe_id; ?>" method="POST" class="comment-box">
    <textarea name="comment" placeholder="Add a comment..." required></textarea>
    <button type="submit">Comment</button>
</form>

                <?php else: ?>
                    <p>You must be logged in to comment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="random-posts">
        <h3>Other Posts You Might Like</h3>
        <div class="random-posts-wrapper">
            <?php while ($random_recipe = $random_recipes_result->fetch_assoc()): ?>
                <div class="random-post">
                    <a href="recipe.php?recipeid=<?php echo $random_recipe['recipeId']; ?>">
                        <img src="<?php echo htmlspecialchars($random_recipe['image']); ?>" alt="Random Post">
                        <h4><?php echo htmlspecialchars($random_recipe['title']); ?></h4>
                        <p><?php echo htmlspecialchars($random_recipe['description']); ?></p>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>


<script>
function toggleEditForm(commentId) {
    let form = document.getElementById("edit-form-" + commentId);
    form.style.display = (form.style.display === "none" || form.style.display === "") ? "block" : "none";
}

document.addEventListener("DOMContentLoaded", function () {
    const likeButton = document.getElementById("loveButton-<?php echo $recipe_id; ?>");

    if (likeButton) {
        likeButton.addEventListener("click", function (e) {
            e.preventDefault();

            const isLiked = likeButton.getAttribute("data-liked") === "true";
            const action = isLiked ? "unliked" : "liked";

            // Optimistically update UI
            likeButton.setAttribute("data-liked", (!isLiked).toString());
            likeButton.classList.toggle("fa-solid", !isLiked);
            likeButton.classList.toggle("fa-regular", isLiked);
            likeButton.style.color = !isLiked ? "red" : "#ccc";

            fetch("love_recipe.php", {
                method: "POST",
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `recipeid=<?php echo $recipe_id; ?>&action=${action}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status !== "liked" && data.status !== "unliked") {
                    alert("Something went wrong: " + data.error);

                    // Revert UI on failure
                    likeButton.setAttribute("data-liked", isLiked.toString());
                    likeButton.classList.toggle("fa-solid", isLiked);
                    likeButton.classList.toggle("fa-regular", !isLiked);
                    likeButton.style.color = isLiked ? "red" : "#ccc";
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("An error occurred.");

                likeButton.setAttribute("data-liked", isLiked.toString());
                likeButton.classList.toggle("fa-solid", isLiked);
                likeButton.classList.toggle("fa-regular", !isLiked);
                likeButton.style.color = isLiked ? "red" : "#ccc";
            });
        });
    }
});
</script>
