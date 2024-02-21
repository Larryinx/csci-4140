<!DOCTYPE html>
<html>

<head>
  <title>Example PHP page for CSCI4140 24SP</title>
</head>

<body>
  <?php
  echo '<h1>Hello world!</h1>';
  echo '<p>This page uses PHP version ' . phpversion() . '.</p>';
  
  if(isset($_COOKIE['user'])) {
    echo '<p>Welcome, ' . htmlspecialchars($_COOKIE['user']) . '!</p>';
    echo '<a href="logout.php">Logout</a>';
    // Include private images
  } else {
    echo '<p><a href="login.php">Login</a></p>';
    // Show public photos
  }
  include('db_connect.php');
  ?>
  <!-- <img src="generate_image.php" alt="Generated Image" /> -->
</body>

</html>
