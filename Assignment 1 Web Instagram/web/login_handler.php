<?php
include('db_connect.php');

function check_credentials($pdo, $username, $password) {
    $stmt = $pdo->prepare('SELECT passwords FROM myusers WHERE name = :username');
    $stmt->execute([':username' => $username]);
  
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stored_password = $row['passwords']; 
  
        if ($password === $stored_password) {
            // Correct credentials
            return true;
        }
    }
    // Incorrect credentials or user does not exist
    return false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (check_credentials($conn, $username, $password)) {
        setcookie('user', $username, time() + 3600);
        header('Location: index.php');
        exit();
    } else {
        header('Location: index.php?error=invalid');
        exit();
    }
}
?>
