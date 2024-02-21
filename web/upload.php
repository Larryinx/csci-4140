<?php
include('db_connect.php');

// Check if the user is logged in
$username = isset($_COOKIE['user']) ? htmlspecialchars($_COOKIE['user']) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $username) {
    // Check if file is uploaded
    if (isset($_FILES['photo']) && isset($_POST['mode'])) {
        $file = $_FILES['photo'];
        $mode = $_POST['mode'];
        $uploadDir = 'images/';
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Check for allowed file extensions
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions)) {
            die('Invalid file type.');
        }

        // Determine the next number for the file name
        $files = glob($uploadDir . '*');
        $numbers = array_map(function ($f) {
            return (int) filter_var(basename($f), FILTER_SANITIZE_NUMBER_INT);
        }, $files);
        $nextNumber = count($numbers) > 0 ? max($numbers) + 1 : 1;

        // Prepare the file name
        $newFileName = sprintf("%d_%s.%s", $nextNumber, $mode === 'private' ? $username : 'public', $ext);

        // Move the uploaded file to the images directory
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
            // Redirect to the photo editor or the index page
            header('Location: index.php');
            exit;
        } else {
            die('File upload failed.');
        }
    }
} else {
    // Redirect non-logged-in users back to the index page
    header('Location: index.php?error=notloggedin');
    exit;
}
?>
