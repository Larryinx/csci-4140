<!DOCTYPE html>
<html>
<head>
  <title>Login Page</title>
</head>
<body>
  <h1>Login</h1>
  <p>Administrator (initialized authority):</p>
  <p>username: admin, password: minda123</p>
  <p>Normal user (two users for testing private images, could be deleted by initialization): </p>
  <p>username: Student, password: csci4140sp24</p>
  <p>username: Alice, password: password</p>
  <form action="login_handler.php" method="post">
    <input type="text" name="username" placeholder="username" required>
    <input type="password" name="password" placeholder="password" required>
    <button type="submit">LOGIN</button>
    <p><a href="index.php">Back to home</a></p>
  </form>
</body>
</html>
