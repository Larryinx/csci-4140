<!DOCTYPE html>
<html>
<head>
  <title>Login Page</title>
  <?php
  // If there's an error parameter in the URL, show an alert
  if (isset($_GET['error'])) {
      echo "<script>alert('Invalid username or password. Please try again.');</script>";
  }
  ?>
</head>
<body>
  <form action="login_handler.php" method="post">
    <input type="text" name="username" placeholder="username" required>
    <input type="password" name="password" placeholder="password" required>
    <button type="submit">LOGIN</button>
  </form>
</body>
</html>
