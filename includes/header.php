<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Pinfood</title>
</head>
<body>

<header class="pinfood-header">
  <!-- Left: Logo/Brand -->
  <a href="index.php" class="logo">Pinfood</a>
  
  <div class="search-bar">
    <form action="search.php" method="GET">
      <input type="text" name="query" placeholder="Search" required />
      <button type="submit" class="search-icon">&#128269;</button>
    </form>
  </div>

  <!-- Right: Navigation Links (or icons) -->
  <div class="nav-links">
  <a href="notifcations.php">Message</a>
    <a href="create.php">Create</a>
    <a href="profile.php">Profile</a>
  </div>
</header>
</body>
</html>
