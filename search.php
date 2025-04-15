<?php
if (isset($_GET['query'])) {
    $searchTerm = trim($_GET['query']);
    include 'db_connection.php';
    include 'includes/header.php'; 


    $like = "%" . $searchTerm . "%";

    // Function to perform the search on a table
    function runSearch($conn, $table, $like) {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE title LIKE ?");
        if (!$stmt) {
            die("SQL error in $table: " . $conn->error);
        }
        $stmt->bind_param("s", $like);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Perform the search on both tables
    $resultsPost = runSearch($conn, "post", $like);
    $resultsRecipe = runSearch($conn, "postrecipe", $like);

    // Check if any results were found
    if ($resultsPost->num_rows > 0 || $resultsRecipe->num_rows > 0) {
        echo "<div class='search-results-container'>";  // Start Pinterest-style grid

        // Display results from 'post' table with clickable links
        if ($resultsPost->num_rows > 0) {
            while ($row = $resultsPost->fetch_assoc()) {
                // Link to post.php with the postid as a GET parameter
                echo "<div class='search-item'>";
                echo "<a href='post.php?postid=" . htmlspecialchars($row['postId']) . "' class='search-card'>";
                echo "<img src='" . htmlspecialchars($row['image']) . "' alt='Recipe Image' />";
                echo "<div class='search-card-content'>";
                echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";

                // Check if 'recipeid' exists in 'post' table results
                if (isset($row['recipeid'])) {
                    echo "<p>Recipe ID: " . $row['recipeid'] . "</p>";
                }

                echo "</div></a></div>";  // Close the link and search item div
            }
        }

        // Display results from 'postrecipe' table without clickable links
        if ($resultsRecipe->num_rows > 0) {
            while ($row = $resultsRecipe->fetch_assoc()) {
                echo "<div class='search-item'>";
                echo "<div class='search-card'>";
                echo "<img src='" . htmlspecialchars($row['image']) . "' alt='Recipe Image' />";
                echo "<div class='search-card-content'>";
                echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";

                // Check if 'recipeid' exists in 'postrecipe' table results
                if (isset($row['recipeid'])) {
                    echo "<p>Recipe ID: " . $row['recipeid'] . "</p>";
                }

                echo "</div></div></div>";
            }
        }

        echo "</div>";  // End search-results-container
    } else {
        echo "No results found for <strong>" . htmlspecialchars($searchTerm) . "</strong>.";
    }

    $conn->close();
} else {
    echo "No search term provided.";
}
?>

<style>
    /* Search results specific styles */
    .search-results-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin: 20px;
    }

    .search-item {
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .search-item:hover {
        transform: translateY(-10px);
    }

    .search-card {
        display: block;
        padding: 15px;
        text-decoration: none;
        color: inherit;
    }

    .search-card img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .search-card-content {
        margin-top: 10px;
    }

    .search-card-content h2 {
        font-size: 18px;
        font-weight: bold;
        margin: 0 0 10px;
    }

    .search-card-content p {
        font-size: 14px;
        color: #555;
    }
</style>
