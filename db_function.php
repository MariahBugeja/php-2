<?php
include 'db_connection.php';

function getAllPosts($conn, $searchKeyword = null) {
    $results = []; 
    
    $sql = "SELECT * FROM post WHERE LOWER(title) = LOWER(?) OR LOWER(description) = LOWER(?)";
    
    if ($searchKeyword !== null) {
        $searchKeyword = strtolower($searchKeyword); 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $searchKeyword, $searchKeyword);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $results['sql'] = $sql; 
            $results['posts'] = $result->fetch_all(MYSQLI_ASSOC); 
        }
    } else {
        $result = $conn->query("SELECT * FROM post");
        if ($result->num_rows > 0) {
            $results['sql'] = "SELECT * FROM post"; 
            $results['posts'] = $result->fetch_all(MYSQLI_ASSOC); 
        }
    }
    
    return $results; 
}
?>
