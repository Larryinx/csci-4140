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
        } else {
            error_log("Password verify failed for user: $username");
        }
    } else {
        error_log("No user found with username: $username");
    }
  
    // Incorrect credentials or user does not exist
    return false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Assuming $conn is your PDO connection from db_connect.php
    if (check_credentials($conn, $username, $password)) {
        // Use ob_start at the beginning of the script to buffer any output
        ob_start();
        setcookie('user', $username, time() + 3600);
        header('Location: index.php');
        ob_end_flush(); // Send the output buffer and turn off output buffering
        exit();
    } else {
        ob_start();
        header('Location: login.php?error=invalid');
        ob_end_flush();
        exit();
    }
}
?>
