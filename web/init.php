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

    // Re-create the images directory if needed
    // if (!file_exists('images/')) {
    //     mkdir('images/', 0775);
    //     chown('images/', 'www-data');
    // }

    // Remove all users except admin from the database
    try {
        $deleteQuery = "DELETE FROM users WHERE username != 'admin'";
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