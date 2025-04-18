<?php
session_start();
require_once 'db_connection.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "Error: User not logged in.";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch unread notifications for the logged-in user, along with the sender's username
$notification_query = "
    SELECT 
        n.notification_id, 
        m.message, 
        n.timestamp, 
        u.username AS sender_name,
        u.userid AS sender_id
    FROM 
        notifications n
    JOIN 
        messages m ON n.message_id = m.message_id
    JOIN 
        user u ON m.sender_id = u.userid
    WHERE 
        n.user_id = ? 
        AND n.status = 'unread'
    ORDER BY 
        n.timestamp DESC
";

$notification_stmt = $conn->prepare($notification_query);

// Check if the prepare() method failed
if ($notification_stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$notification_stmt->bind_param("i", $user_id);
$notification_stmt->execute();
$notification_result = $notification_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="notifications-container">
    <h2>Notifications</h2>

    <?php
    if ($notification_result->num_rows > 0) {
        while ($notification = $notification_result->fetch_assoc()) {
            echo "<a href='chat.php?receiver_id=" . $notification['sender_id'] . "' class='notification-link'>";
            echo "<div class='notification'>";
            echo "<p><strong>New Message from " . htmlspecialchars($notification['sender_name']) . ":</strong> " . htmlspecialchars($notification['message']) . "</p>";
            echo "<small>Received on: " . $notification['timestamp'] . "</small>";
            echo "</div>";
            echo "</a>";

            // Mark the notification as read
            $mark_as_read_query = "UPDATE notifications SET status = 'read' WHERE notification_id = ?";
            $mark_as_read_stmt = $conn->prepare($mark_as_read_query);
            $mark_as_read_stmt->bind_param("i", $notification['notification_id']);
            $mark_as_read_stmt->execute();
        }
    } else {
        echo "<p>No unread notifications.</p>";
    }
    ?>

</div>

</body>
</html>
