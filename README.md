# 📌 PinFood

**PinFood** is the ultimate destination for food lovers of all kinds. Whether you're searching for delectable desserts, speedy quick meals, wholesome vegetarian dishes, or world cuisines, **PinFood** has you covered. With a user-friendly platform designed for easy recipe sharing and exploration, PinFood is your go-to place for culinary inspiration.

---

## 🌟 Features

- **Search Bar** – Quickly search for recipes, cooking tips, or ingredients.
- **Create Post** – Share photos of your dishes with titles, descriptions, and tags.
- **Create Recipe** – Share recipes with step-by-step instructions and photos.
- **Save Post & Recipe** – Save your favourites into themed collections like *Weeknight Dinners*.
- **Love & Comment** – Like posts and leave feedback in the comments.
- **Share Post** – Share posts with friends via external platforms.
- **Notifications** – Stay updated on likes, comments, and messages.
- **Messaging** – Chat privately with other users in the community.
- **Profile Page** – Manage your posts, recipes, profile picture, and bio.

---

## 🎯 Target Audience

PinFood caters to:

- Food lovers exploring new recipes
- Home cooks looking for meal inspiration
- Chefs sharing skills and culinary ideas
- Food bloggers growing their audience
- Baking enthusiasts discovering techniques
- Health-conscious users seeking nutritious dishes

---

## 🚀 Getting Started

### ✅ Prerequisites

- PHP 7.x or higher
- MySQL
- Composer (for dependency management)
- Apache or Nginx server

### 📥 Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/MariahBugeja/reset.git
   cd PinFood
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Database setup:**
   - Create a new MySQL database.
   - Import the SQL file from the `database/` folder.

4. **Run the app:**
   - Start your server (e.g., MAMP/XAMPP).
   - Access the application via browser at `http://localhost/php-2/php-2/index.php`

---

## 📁 Folder Structure

```
php-2/
├── assets/                 # Static files (images, logos)
├── database/              # SQL files
├── includes/              # Header & shared components
├── uploads/               # Uploaded posts, recipes, and profile pictures
├── document/              # Documentation and task files
├── chat.php               # Chat interface
├── create.php             # Post creation
├── db_connection.php      # Database connection
├── db_function.php        # DB utility functions
├── edit_profile.php       # Edit user profile
├── follow_user.php        # Follow/unfollow logic
├── index.php              # Home page
├── login.php              # Login logic
├── logout.php             # Logout logic (if created)
├── love_post.php          # Like a post
├── love_recipe.php        # Like a recipe
├── messages.php           # Messaging inbox
├── send_message.php       # Send a new message
├── notifcations.php       # View notifications
├── post.php               # View individual post
├── recipe.php             # View individual recipe
├── save_post.php          # Save post logic
├── save_recipe.php        # Save recipe logic
├── search.php             # Search functionality
├── share_post.php         # Share logic
├── sign.php               # Sign up form
├── profile.php            # User profile
├── rating_post.php        # Rate a post
├── rating_user.php        # Rate a user
├── rate_recipe.php        # Rate a recipe
├── delete_post.php        # Delete post
├── delete_recipe.php      # Delete recipe
├── delete_comment.php     # Delete comment
├── update_comment.php     # Update comment
├── update-profile.php     # Update profile logic
├── upload.php             # Upload recipe or post
├── upload_profile_pic.php # Upload user avatar
├── style.css              # Main CSS
├── README.md              # Project documentation
```

---

## 🧑‍🍳 Usage Instructions

- **Creating a Recipe:**  
  Click on "Recipe", fill in ingredients, instructions, and upload a photo.

- **Creating a Post:**  
  Click on "Create", add title, description, type, and an image.

- **Loving a Post:**  
  Tap the ❤️ icon next to the Save button.

- **Saving a Post:**  
  Tap the 📌 icon to save to your collections.

- **Commenting:**  
  Scroll below the post, type in your comment, and hit "Submit".

- **Sharing a Post:**  
  Click "Share" and choose a platform.

- **Messaging a User:**  
  Go to their profile or chat directly through the "Messages" section. Send and receive private messages in real time.

---

## 📄 License

This project is licensed under the **MIT License**.  
See the `LICENSE` file for details.

---

## 📬 Contact

Questions, feedback, or just want to say hi?  
Reach out at: **[mariahbugeja82@gmail.com](mailto:mariahbugeja82@gmail.com)**

