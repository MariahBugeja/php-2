<?php
session_start();
require_once 'db_connection.php';

if (!isset($_GET['postid']) || empty($_GET['postid'])) {
    die("Post ID is missing or invalid.");
}

$post_id = isset($_GET['postid']) ? (int)$_GET['postid'] : 0;
if ($post_id <= 0) {
    die("Invalid Post ID.");
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$sql = "SELECT post.*, user.username FROM post 
        JOIN user ON post.userid = user.userid 
        WHERE post.postid = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error preparing query: " . $conn->error);
}

$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    die("Post not found.");
}
// Check if the user has loved the post
$check_love_query = $conn->prepare("SELECT * FROM `like` WHERE userid = ? AND postid = ?");
if (!$check_love_query) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}
$check_love_query->bind_param("ii", $_SESSION['user_id'], $post_id);
$check_love_query->execute();
$check_love_result = $check_love_query->get_result();
$user_has_loved = ($check_love_result && $check_love_result->num_rows > 0);

// Check if the user has already saved this post
$saved_sql = "SELECT * FROM save WHERE userid = ? AND postid = ?";
$saved_stmt = $conn->prepare($saved_sql);
if ($saved_stmt === false) {
    die("Error preparing saved check query: " . $conn->error);
}

$saved_stmt->bind_param("ii", $user_id, $post_id);
$saved_stmt->execute();
$saved_result = $saved_stmt->get_result();
$is_saved = $saved_result->num_rows > 0;

// Handle saving or unsaving the post
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_post'])) {
    if ($user_id) {
        if ($is_saved) {
            // If the post is already saved, unsave it
            $delete_sql = "DELETE FROM save WHERE userid = ? AND postid = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            if ($delete_stmt === false) {
                die("Error preparing unsave post query: " . $conn->error);
            }

            $delete_stmt->bind_param("ii", $user_id, $post_id);
            if ($delete_stmt->execute()) {
                $is_saved = false; 
            } else {
                echo "Error unsaving post: " . $conn->error;
            }
        } else {
            // If the post is not saved, save it
            $insert_sql = "INSERT INTO save (userid, postid) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            if ($insert_stmt === false) {
                die("Error preparing save post query: " . $conn->error);
            }

            $insert_stmt->bind_param("ii", $user_id, $post_id);
            if ($insert_stmt->execute()) {
                $is_saved = true; 
            } else {
                echo "Error saving post: " . $conn->error;
            }
        }

        header("Location: post.php?postid=" . $post_id);
        exit;
    } else {
        echo "You must be logged in to save posts.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && $user_id) {
    $comment_content = $_POST['comment'];

    $comment_content = htmlspecialchars($comment_content, ENT_QUOTES, 'UTF-8');

    // Insert the comment into the database
    $insert_comment_sql = "INSERT INTO comment (userid, postid, content, timestamp) VALUES (?, ?, ?, NOW())";
    $insert_comment_stmt = $conn->prepare($insert_comment_sql);

    if ($insert_comment_stmt === false) {
        die("Error preparing comment insertion query: " . $conn->error);
    }

    $insert_comment_stmt->bind_param("iis", $user_id, $post_id, $comment_content);
    
    if ($insert_comment_stmt->execute()) {
        header("Location: post.php?postid=" . $post_id);
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
        header("Location: post.php?postid=" . $post_id);
        exit;
    } else {
        echo "Error updating comment: " . $conn->error;
    }
}

$comment_sql = "SELECT comment.commentId, comment.userid, comment.content, comment.timestamp, user.username 
                FROM comment 
                JOIN user ON comment.userid = user.userid 
                WHERE comment.postid = ? ORDER BY timestamp DESC";
$comment_stmt = $conn->prepare($comment_sql);
if ($comment_stmt === false) {
    die("Error preparing comment query: " . $conn->error);
}

$comment_stmt->bind_param("i", $post_id);
$comment_stmt->execute();
$comments_result = $comment_stmt->get_result();

// Fetch other random posts to display
$random_posts_sql = "SELECT post.*, user.username FROM post 
                     JOIN user ON post.userid = user.userid 
                     WHERE post.postid != ? 
                     ORDER BY RAND() LIMIT 3";
$random_posts_stmt = $conn->prepare($random_posts_sql);
if ($random_posts_stmt === false) {
    die("Error preparing random posts query: " . $conn->error);
}

$random_posts_stmt->bind_param("i", $post_id);
$random_posts_stmt->execute();
$random_posts_result = $random_posts_stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
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
                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image">
            </div>

            <div class="post-content">
                <div class="title-save-wrapper">
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <?php if ($user_id): ?>
                        <form action="post.php?postid=<?php echo $post_id; ?>" method="POST">
                            <button type="submit" name="save_post" class="save-button">
                                <?php echo $is_saved ? "Saved " : "Save"; ?>
                            </button>
                        </form>
                    <?php else: ?>
                        <p>You must be logged in to save posts.</p>
                    <?php endif; ?>
                </div>

                <p><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
                <p class="username">By: <?php echo htmlspecialchars($post['username']); ?></p>
                <form method="POST" action="love_post.php" class="like-form">
    <input type="hidden" name="postid" value="<?php echo $post_id; ?>">
    <button 
    id="loveButton-<?php echo $post_id; ?>" 
    data-liked="<?php echo $user_has_loved ? 'true' : 'false'; ?>"
    class="fa-heart <?php echo $user_has_loved ? 'fa-solid' : 'fa-regular'; ?>"
    style="background: none; border: none; font-size: 24px; color: <?php echo $user_has_loved ? 'red' : '#ccc'; ?>; cursor: pointer;">
</button>
</form> 


                
                <!-- Comments Section -->
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
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</button>
                                </form>
                                <form action="post.php?postid=<?php echo $post_id; ?>" method="POST" class="edit-form" id="edit-form-<?php echo $comment['commentId']; ?>" style="display:none;">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment['commentId']; ?>">
                                    <textarea name="new_content" required><?php echo htmlspecialchars($comment['content']); ?></textarea>
                                    <button type="submit" name="edit_comment">Save Changes</button>
                                    <button type="button" onclick="toggleEditForm(<?php echo $comment['commentId']; ?>)">Cancel</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>

                    <!-- Comment submisson -->
                    <?php if ($user_id): ?>
                        <form action="post.php?postid=<?php echo $post_id; ?>" method="POST" class="comment-box">
                            <textarea name="comment" placeholder="Add a comment..." required></textarea>
                            <button type="submit">Comment</button>
                        </form>
                    <?php else: ?>
                        <p>You must be logged in to comment.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Other Posts You Might Like Section -->
        <div class="random-posts">
            <h3>Other Posts You Might Like</h3>
            <div class="random-posts-wrapper">
                <?php while ($random_post = $random_posts_result->fetch_assoc()): ?>
                    <div class="random-post">
                        <a href="post.php?postid=<?php echo $random_post['postId']; ?>">
                            <img src="<?php echo htmlspecialchars($random_post['image']); ?>" alt="Random Post Image">
                            <h4><?php echo htmlspecialchars($random_post['title']); ?></h4>
                            <p><?php echo htmlspecialchars($random_post['description']); ?></p>
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
        const likeButton = document.getElementById("loveButton-<?php echo $post_id; ?>");

        if (likeButton) {
            likeButton.addEventListener("click", function (e) {
                e.preventDefault();

                const postId = <?php echo $post_id; ?>;
                const isLiked = likeButton.getAttribute("data-liked") === "true";
                const action = isLiked ? "unliked" : "liked";

                likeButton.setAttribute("data-liked", !isLiked);
                likeButton.classList.toggle("fa-solid", !isLiked);
                likeButton.classList.toggle("fa-regular", isLiked);
                likeButton.style.color = !isLiked ? "red" : "#ccc";

                fetch("love_post.php", {
                    method: "POST",
                    body: new URLSearchParams({
                        postid: postId,
                        action: action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status !== "liked" && data.status !== "unliked") {
                        alert("Something went wrong: " + data.error);
                        likeButton.setAttribute("data-liked", isLiked);
                        likeButton.classList.toggle("fa-solid", isLiked);
                        likeButton.classList.toggle("fa-regular", !isLiked);
                        likeButton.style.color = isLiked ? "red" : "#ccc";
                    }
                })
                .catch(err => {
                    console.error("Error:", err);
                    alert("An error occurred.");
                    // Revert UI in case of error
                    likeButton.setAttribute("data-liked", isLiked);
                    likeButton.classList.toggle("fa-solid", isLiked);
                    likeButton.classList.toggle("fa-regular", !isLiked);
                    likeButton.style.color = isLiked ? "red" : "#ccc";
                });
            });
        }
    });
</script>

</body>
</html>
