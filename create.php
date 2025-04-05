<?php
session_start();
require_once 'db_connection.php';
include 'includes/header.php'; 


// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");

    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a Pin or Recipe</title>
    <link rel="stylesheet" href="styles.css">   
</head>
<body class="bodyform">
    <div class="container">
        <h2 class="h2form">Create a Pin or Recipe</h2>
        <form action="upload.php" method="POST" enctype="multipart/form-data" class="form-container">
            <!-- Select Type: Pin or Recipe -->
            <label class="labelform">Select Type:</label>
            <select class="selectform" id="type" name="type" required onchange="toggleFields()">
                <option value="pin">Pin</option>
                <option value="recipe">Recipe</option>
            </select>
            
            <label class="labelform">Title:</label>
            <input class="inputform" type="text" name="title" required>
            
            <label class="labelform">Description:</label>
            <textarea class="textareaform" name="description" required></textarea>
            
            <!-- Image Upload -->
            <label class="labelform">Image:</label>
            <div class="image-uploadform" id="imageDropArea">
                <p>Drag & Drop an image or click to select</p>
                <input type="file" name="image" id="imageInput" accept="image/*" required hidden>
                <img id="previewImage" alt="Preview Image" style="display: none;">
            </div>
            
            <div id="recipeFields" class="hidden">
                <label class="labelform">Ingredients:</label>
                <textarea class="textareaform" name="ingredients"></textarea>
                
                <label class="labelform">Instructions:</label>
                <textarea class="textareaform" name="instructions"></textarea>
            </div>

            <div id="pinFields" class="hidden">
                <label class="labelform">Type of Food:</label>
                <input class="inputform" type="text" name="typeoffood" required>
            </div>
            
            <button class="buttonform" type="submit">Submit</button>
        </form>
    </div>
    
    <script>
        function toggleFields() {
            const type = document.getElementById("type").value;
            const recipeFields = document.getElementById("recipeFields");
            const pinFields = document.getElementById("pinFields");
            recipeFields.style.display = (type === "recipe") ? "block" : "none";
            pinFields.style.display = (type === "pin") ? "block" : "none";
        }

        const imageDropArea = document.getElementById("imageDropArea");
        const imageInput = document.getElementById("imageInput");
        const previewImage = document.getElementById("previewImage");

        imageDropArea.addEventListener("click", () => imageInput.click());

        imageInput.addEventListener("change", (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    previewImage.src = reader.result;
                    previewImage.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });

        imageDropArea.addEventListener("dragover", (event) => {
            event.preventDefault();
            imageDropArea.classList.add("dragover");
        });

        imageDropArea.addEventListener("dragleave", () => {
            imageDropArea.classList.remove("dragover");
        });

        imageDropArea.addEventListener("drop", (event) => {
            event.preventDefault();
            imageDropArea.classList.remove("dragover");

            const file = event.dataTransfer.files[0];
            if (file) {
                imageInput.files = event.dataTransfer.files;

                const reader = new FileReader();
                reader.onload = () => {
                    previewImage.src = reader.result;
                    previewImage.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });

        window.addEventListener('DOMContentLoaded', toggleFields);
    </script>
</body>
</html>
