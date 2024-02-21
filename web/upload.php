<?php
// Include database connection
include('db_connect.php');

// Check if the form has been submitted
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
        if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

        // Verify MIME type of the file
        if (in_array($filetype, $allowed)) {
            // Check whether file exists before uploading it
            if (file_exists("images/" . $filename)) {
                echo $filename . " already exists.";
            } else {
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
                move_uploaded_file($_FILES["photo"]["tmp_name"], "images/" . $newName);
                echo "Your file was uploaded successfully.";
            } 
        } else {
            echo "Error: There was a problem uploading your file. Please try again."; 
        }
    } else {
        echo "Error: " . $_FILES["photo"]["error"];
    }
}
?>
