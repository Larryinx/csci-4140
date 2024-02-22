<?php
include('db_connect.php');

// Function to remove all files in the "images/" directory
function clearImagesDirectory() {
    $files = glob('images/*'); // Get all file names
    foreach ($files as $file) { // Iterate files
        if (is_file($file)) {
            unlink($file); // Delete file
        }
    }
}

if (isset($_POST['init'])) {
    clearImagesDirectory();

    // Remove all users except admin from the database
    try {
        $deleteQuery = "DELETE FROM myusers WHERE name != 'admin'";
        $conn->exec($deleteQuery);

        // $conn->exec("ALTER SEQUENCE users_id_seq RESTART WITH 1");

        header('Location: index.php?init=success');
    } catch (PDOException $e) {
        // Handle exception
        echo '<p>Error during system initialization: ' . $e->getMessage() . '</p>';
        exit;
    }
} elseif (isset($_POST['back'])) {
    header('Location: index.php');
    exit;
}

?>