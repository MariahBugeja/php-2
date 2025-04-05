<?php
session_start();
require_once 'db_connection.php';

if (!isset($_GET['postid']) || empty($_GET['postid'])) {
    die("Post not found.");
}

$post_id = intval($_GET['postid']);
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null; // Store logged-in user ID

$sql = "SELECT post.*, user.username FROM post 
        JOIN user ON post.userid = user.userid 
        WHERE post.postid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    die("Post not found.");
}

$comment_sql = "SELECT comment.commentId, comment.userid, comment.content, comment.timestamp, user.username 
                FROM comment 
                JOIN user ON comment.userid = user.userid 
                WHERE comment.postid = ? ORDER BY timestamp DESC";
$comment_stmt = $conn->prepare($comment_sql);
$comment_stmt->bind_param("i", $post_id);
$comment_stmt->execute();
$comments_result = $comment_stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_comment'])) {
    if ($user_id) {
        $comment_id = intval($_POST['comment_id']);
        $new_content = trim($_POST['new_content']);

        if (!empty($new_content)) {
            $update_sql = "UPDATE comment SET content = ? WHERE commentId = ? AND userid = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sii", $new_content, $comment_id, $user_id);

            if ($update_stmt->execute()) {
                header("Location: post.php?postid=$post_id"); // Stay on the post
                exit();
            } else {
                echo "Error updating comment: " . $conn->error;
            }
        } else {
            echo "Comment cannot be empty.";
        }
    } else {
        echo "You must be logged in to edit comments.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment'])) {
    if ($user_id) {
        $new_comment = trim($_POST['comment']);

        if (!empty($new_comment)) {
            $insert_sql = "INSERT INTO comment (postid, userid, content, timestamp) VALUES (?, ?, ?, NOW())";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iis", $post_id, $user_id, $new_comment);

            if ($insert_stmt->execute()) {
                header("Location: post.php?postid=$post_id"); // Refresh to show new comment
                exit();
            } else {
                echo "Error posting comment: " . $conn->error;
            }
        } else {
            echo "Comment cannot be empty.";
        }
    } else {
        echo "You must be logged in to comment.";
    }
}

$random_posts_sql = "SELECT post.*, user.username FROM post 
                     JOIN user ON post.userid = user.userid 
                     WHERE post.postid != ? 
                     ORDER BY RAND() LIMIT 3";
$random_posts_stmt = $conn->prepare($random_posts_sql);
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
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
            <p class="username">By: <?php echo htmlspecialchars($post['username']); ?></p>

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

                <!-- Comment Submission -->
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
                    <a href="post.php?postid=<?php echo $random_post['postid']; ?>">
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
    </script>
</body>
</html>
