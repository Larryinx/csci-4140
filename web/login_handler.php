<?php
include('db_connect.php');

function check_credentials($pdo, $username, $password) {
    // Prepare a query to fetch the user's hashed password from the database
    $stmt = $pdo->prepare('SELECT passwords FROM myusers WHERE name = :username');
    $stmt->execute([':username' => $username]);
  
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $hashed_password = $row['passwords'];
  
        // Use password_verify to compare the plaintext password with the hashed password
        if (password_verify($password, $hashed_password)) {
            // Correct credentials
            return true;
        }
    }
  
    // Incorrect credentials or user does not exist
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Assuming $conn is your PDO connection from db_connect.php
    if (check_credentials($conn, $username, $password)) {
        setcookie('user', $username, time() + 3600); // Set cookie for 1 hour
        header('Location: index.php'); // Redirect to index page
        exit();
    } else {
        // Redirect back to the login page or show the error
        header('Location: login.php?error=invalid'); // Redirect to login with error
        exit();
    }
}
?>
