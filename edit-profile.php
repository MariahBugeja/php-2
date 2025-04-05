<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM user WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="styles.css">
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: #fafafa;
    margin: 0;
    padding: 0;
    color: #333;
}

/* Edit profile container */
.edit-profile-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 40px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

h2 {
    font-size: 28px;
    margin-bottom: 20px;
    text-align: center;
    color: #333;
}

/* Form elements */
.edit-profile-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.input-field {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.input-field label {
    font-size: 14px;
    font-weight: bold;
    color: #666;
}

.input-field input, .input-field textarea {
    padding: 12px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #f9f9f9;
}

.input-field input:disabled {
    background-color: #e6e6e6;
}

textarea {
    resize: none;
}

.hidden-input {
    display: none;
}

.profile-picture-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
}

.profile-picture-container {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #ddd;
}

.profile-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.save-changes-btn {
    padding: 12px 24px;
    font-size: 16px;
    color: white;
    background-color: #0078d4;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.save-changes-btn:hover {
    background-color: #005a99;
}

@media (max-width: 768px) {
    .edit-profile-container {
        padding: 20px;
    }
    .input-field input, .input-field textarea {
        font-size: 14px;
    }
}
</style>
</head>
<body>
    <div class="edit-profile-container">
        <h2>Edit Profile</h2>
        <form action="update-profile.php" method="POST" enctype="multipart/form-data" class="edit-profile-form">
            <input type="hidden" name="userid" value="<?= $user['userid'] ?>">

            <!-- Profile Picture Section -->
            <div class="profile-picture-section">
    <label for="profile_picture" class="profile-picture-label">
        <div class="profile-picture-container">
            <?php if (!empty($user['profile_picture']) && file_exists("uploads/" . $user['profile_picture'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>?<?php echo time(); ?>" alt="Profile Picture" class="profile-img" id="profile-img">
            <?php else: ?>
                <img src="default-profile.png" alt="Default Profile Picture" class="profile-img" id="profile-img">
            <?php endif; ?>
        </div>
        <span class="edit-icon">✎</span>
    </label>
    <input type="file" name="profile_picture" id="profile_picture" class="hidden-input" onchange="previewImage(event)">
</div>

            <!-- Username Field -->
            <div class="input-field">
                <label for="username">Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>

            <!-- Email Field (Disabled) -->
            <div class="input-field">
                <label for="email">Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
            </div>

            <!-- Password Field -->
            <div class="input-field">
                <label for="password">Password</label>
                <input type="password" name="password" value="<?= htmlspecialchars($user['password']) ?>" required>
            </div>

            <!-- About Field -->
            <div class="input-field">
                <label for="about">About</label>
                <textarea name="about" rows="4"><?= htmlspecialchars($user['about']) ?></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="save-changes-btn">Save Changes</button>
        </form>
    </div>
</body>
</html>
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update the profile image preview
                const imgElement = document.getElementById('profile-img');
                imgElement.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
</script>