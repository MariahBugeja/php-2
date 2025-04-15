
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

$saved_sql = "SELECT post.* FROM save 
              JOIN post ON save.postid = post.postid 
              WHERE save.userid = ?";
$saved_stmt = $conn->prepare($saved_sql);
$saved_stmt->bind_param("i", $user_id);
$saved_stmt->execute();
$saved_posts_result = $saved_stmt->get_result();

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
                    <img src="default-profile.png" alt="Default Profile Picture">
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
    <button id="showSavedPosts" onclick="togglePosts('saved')">Saved Posts</button>
</div>

<div class="created-posts" style="display: block;">
    <h3>Created Posts</h3>
    <?php if ($created_posts_result->num_rows > 0): ?>
        <div class="grid-container">
            <?php while ($post = $created_posts_result->fetch_assoc()): ?>
                <div class="grid-item">
                    <a href="post.php?postid=<?php echo $post['postId']; ?>">
                        <?php 
                        $imagePath = htmlspecialchars($post['image']);
                        if (!empty($imagePath) && file_exists($imagePath)): ?>
                            <img src="<?php echo $imagePath; ?>" alt="Post Image">
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


<!-- Saved Posts -->
<div class="saved-posts">
    <h3>Saved Posts</h3>
    <?php if ($saved_posts_result->num_rows > 0): ?>
        <div class="grid-container">
            
            <?php while ($saved_post = $saved_posts_result->fetch_assoc()): ?>
                <div class="grid-item">
                <a href="post.php?postid=<?php echo $saved_post['postId']; ?>">

                    <?php 
                    $savedImagePath = htmlspecialchars($saved_post['image']);
                    if (!empty($savedImagePath) && file_exists($savedImagePath)): ?>
                        <img src="<?php echo $savedImagePath; ?>" alt="Saved Post Image">
                    <?php else: ?>
                        <p>Image not available</p>
                        <p>Expected Path: <?php echo $savedImagePath; ?></p>
                    <?php endif; ?>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p>No saved posts found.</p>
    <?php endif; ?>
</div>

<script>
    function togglePosts(type) {
        const createdPosts = document.querySelector('.created-posts');
        const savedPosts = document.querySelector('.saved-posts');
        const createdBtn = document.getElementById('showCreatedPosts');
        const savedBtn = document.getElementById('showSavedPosts');

        if (type === 'created') {
            createdPosts.style.display = 'block';
            savedPosts.style.display = 'none';
            createdBtn.classList.add('active');
            savedBtn.classList.remove('active');
        } else {
            createdPosts.style.display = 'none';
            savedPosts.style.display = 'block';
            createdBtn.classList.remove('active');
            savedBtn.classList.add('active');
        }
    }
</script>

</body>
</html>
