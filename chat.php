<?php
session_start();
require_once 'db_connection.php';
include 'includes/header.php';

if (!isset($_GET['receiver_id']) || !isset($_SESSION['user_id'])) {
    echo "Error: User not logged in or receiver ID not provided.";
    exit;
}

$sender_id = $_SESSION['user_id'];
$receiver_id = $_GET['receiver_id'];

$receiver_name = "Unknown User";
$user_query = "SELECT username FROM user WHERE userid = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $receiver_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
if ($user_result->num_rows > 0) {
    $user_row = $user_result->fetch_assoc();
    $receiver_name = $user_row['username'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];

    if (!empty($message)) {
        $insert_query = "INSERT INTO messages (sender_id, receiver_id, message, sent_at) VALUES (?, ?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("iis", $sender_id, $receiver_id, $message);

        if ($insert_stmt->execute()) {
            $message_id = $insert_stmt->insert_id;

            $notification_query = "INSERT INTO notifications (user_id, message_id, status) VALUES (?, ?, 'unread')";
            $notification_stmt = $conn->prepare($notification_query);
            $notification_stmt->bind_param("ii", $receiver_id, $message_id);
            $notification_stmt->execute();

            echo "Message sent successfully!";
        } else {
            echo "Error: " . $insert_stmt->error;
        }
    }
}

$chat_query = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY sent_at ASC";
$chat_stmt = $conn->prepare($chat_query);
$chat_stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
$chat_stmt->execute();
$chat_result = $chat_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="chat-container">
    <h2>Chat with <?= htmlspecialchars($receiver_name) ?></h2>

    <div class="messages-container">
        <?php
        if ($chat_result->num_rows > 0) {
            while ($message = $chat_result->fetch_assoc()) {
                $message_class = ($message['sender_id'] == $sender_id) ? 'sent-message' : 'received-message';
                echo "<div class='message $message_class'>";
                echo "<p>" . htmlspecialchars($message['message']) . "</p>";
                echo "<small>Sent at: " . $message['sent_at'] . "</small>";
                echo "</div>";
            }
        } else {
            echo "<p>No messages yet.</p>";
        }
        ?>
    </div>

    <form method="POST" action="">
        <textarea name="message" placeholder="Type your message..." required></textarea>
        <button type="submit" name="send_message">Send Message</button>
    </form>
</div>

</body>
</html>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background-color: #f4f7fa;
    color: #333;
    line-height: 1.6;
}

.chat-container {
    width: 100%;
    max-width: 800px;
    margin: 50px auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.chat-container h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 1.8rem;
    color: #d32f2f;
}

.chat-container .messages-container {
    margin-bottom: 20px;
    padding: 10px;
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fafafa;
}

.chat-container .message {
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    max-width: 80%;
}

.chat-container .sent-message {
    background-color: #f8bbd0;
    align-self: flex-end;
    margin-left: 20%;
    position: relative;
}

.chat-container .received-message {
    background-color: #f1f1f1;
    align-self: flex-start;
    margin-right: 20%;
    position: relative;
}

.chat-container .sent-message::after,
.chat-container .received-message::after {
    content: '';
    position: absolute;
    top: 0;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
}

.chat-container .sent-message::after {
    border-bottom: 8px solid #f8bbd0;
    right: -8px;
}

.chat-container .received-message::after {
    border-bottom: 8px solid #f1f1f1;
    left: -8px;
}

.chat-container .message p {
    margin: 0;
    font-size: 1rem;
}

.chat-container .message small {
    font-size: 0.8rem;
    color: #888;
    text-align: right;
    margin-top: 5px;
}

.chat-container form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 20px;
}

.chat-container textarea {
    padding: 10px;
    font-size: 1rem;
    border-radius: 8px;
    border: 1px solid #ddd;
    resize: vertical;
    height: 100px;
}

.chat-container button {
    padding: 10px;
    font-size: 1.1rem;
    color: #fff;
    background-color: #d32f2f;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.chat-container button:hover {
    background-color: #b71c1c;
}
</style>
