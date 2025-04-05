<?php
session_start();
require_once 'db_connection.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $message = "Passwords do not match";
    } else {
        // Check if username is already taken
        $check_username_sql = "SELECT * FROM user WHERE username = ?";
        $check_username_stmt = $conn->prepare($check_username_sql);
        $check_username_stmt->bind_param("s", $username);
        $check_username_stmt->execute();
        $check_username_result = $check_username_stmt->get_result();

        if ($check_username_result->num_rows > 0) {
            $message = "Username is already taken";
        } else {
            // Check if email is already registered
            $check_email_sql = "SELECT * FROM user WHERE email = ?";
            $check_email_stmt = $conn->prepare($check_email_sql);
            $check_email_stmt->bind_param("s", $email);
            $check_email_stmt->execute();
            $check_email_result = $check_email_stmt->get_result();

            if ($check_email_result->num_rows > 0) {
                $message = "Email is already registered";
            } else {
                // Insert new user into database
                $insert_sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("sss", $username, $email, $password);

                if ($insert_stmt->execute()) {
                    $_SESSION['message'] = "Signup successful. You can now login.";
                    header("Location: Login.php");
                    exit;
                } else {
                    $message = "Error creating user: " . $conn->error;
                }
            }
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Signup</title>
</head>
<body class="signup-body">
    <div class="overlay">
        <div class="signup-container">
            <form method="post" class="signup-form">
                <h1>Sign up for Pinfood</h1>
                <?php if ($message): ?>
                    <p class="error"><?php echo $message; ?></p>
                <?php endif; ?>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                
                <button type="submit">Sign up</button>
                
                <p>Already have an account? <a href="Login.php">Log in</a></p>
            </form>
        </div>
    </div>
</body>
</html>
