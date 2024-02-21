<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file was uploaded without errors
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        // Accepted file types
        $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
        $filename = $_FILES["photo"]["name"];
        $filetype = $_FILES["photo"]["type"];
        $filesize = $_FILES["photo"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) {
            $header = 'Location: index.php?invalid_file_type=' . $ext;
            header($header);
            exit;
        }

        if (in_array($filetype, $allowed)) {
            // Find the next available number
            $files = glob("images/*");
            $numbers = array_map(function($file) {
                preg_match('/images\/(\d+)_/', $file, $matches);
                return $matches[1] ?? 0;
            }, $files);

            $maxNumber = max($numbers);
            $nextNumber = $maxNumber + 1;

            // Determine the new filename
            $username = htmlspecialchars($_COOKIE['user']);
            $newName = $nextNumber . '_';
            $newName .= isset($_POST['mode']) && $_POST['mode'] === 'private' ? $username : 'public';
            $newName .= '.' . $ext;

            // Move the file to the images folder
            if (move_uploaded_file($_FILES["photo"]["tmp_name"], "images/" . $newName)) {
                // Redirect to the photo editor with the new image's filename
                header('Location: editor.php?image=' . urlencode($newName));
                exit;
            } else {
                echo "Error: There was a problem uploading your file. Please try again."; 
            }
        } else {
            echo "Error: Invalid file type.";
        }
    } else {
        echo "Error: " . $_FILES["photo"]["error"];
    }
}
?>
