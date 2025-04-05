<?php
session_start();
require_once 'db_connection.php';
include 'includes/header.php';

function generateCard($row) {
    $link = '';
    if ($row['source'] === 'post') {
        $link = 'viewpost.php?postid=' . $row['id'];
    } elseif ($row['source'] === 'postrecipe') {
        $link = 'viewrecipe.php?recipeid=' . $row['id'];
    }
    return '
    <a href="' . $link . '" class="search-card-link"> <!-- Added link to viewpost or viewrecipe -->
        <div class="search-card"> <!-- Added class "search-card" -->
            <img src="' . htmlspecialchars($row["image"]) . '" alt="' . htmlspecialchars($row["title"]) . '" class="search-card-image"> <!-- Added class "search-card-image" -->
            <div class="search-card-content"> <!-- Added class "search-card-content" -->
                <h3 class="search-card-content-h3">' . htmlspecialchars($row["title"]) . '</h3> <!-- Added class "search-card-content-h3" -->
            </div>
        </div>
    </a>';
}

$resultsHtml = '';

if (isset($_GET['query'])) {
    $query = '%' . $_GET['query'] . '%';

    $stmt1 = $conn->prepare("SELECT 'post' AS source, postId AS id, title, description, image, Userid, Typeoffood AS additional FROM post WHERE title LIKE ? OR Typeoffood LIKE ?");
    $stmt1->bind_param("ss", $query, $query);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    $stmt2 = $conn->prepare("SELECT 'postrecipe' AS source, recipeId AS id, title, description, image, userid, ingredients AS additional FROM postrecipe WHERE title LIKE ? OR ingredients LIKE ?");
    $stmt2->bind_param("ss", $query, $query);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    if ($result1->num_rows > 0 || $result2->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            $resultsHtml .= generateCard($row);
        }
        while ($row = $result2->fetch_assoc()) {
            $resultsHtml .= generateCard($row);
        }
    } else {
        $resultsHtml = '<p>No results found.</p>';
    }

    $stmt1->close();
    $stmt2->close();
} else {
    $resultsHtml = '<p>No search query provided.</p>';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
<style>

</style>
</head>
<body>
    <div class="search-results">
        <?php echo $resultsHtml; ?>
    </div>
</body>
</html>
