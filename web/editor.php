<?php
include('db_connect.php'); // Ensure this points to your database connection script

// Function to apply filters using ImageMagick
function applyFilter($imagePath, $filterType) {
    $imagick = new Imagick($imagePath);

    switch ($filterType) {
        case 'border':
            $imagick->borderImage(new ImagickPixel('black'), 5, 5); // Adds a black border
            break;
        case 'bw':
            $imagick->setImageColorspace(Imagick::COLORSPACE_GRAY); // Converts to grayscale
            break;
        case 'original':
            // If 'original' is selected, restore the original image
            if (file_exists($imagePath . '.original')) {
                copy($imagePath . '.original', $imagePath);
            }
            break;
    }

    if ($filterType !== 'original') {
        $imagick->writeImage($imagePath); // Write the changes to the same image file
    }
    
    $imagick->destroy(); // Clears the Imagick object from memory
}

if (isset($_GET['image'])) {
    $imagePath = 'images/' . $_GET['image'];
    $originalImagePath = $imagePath . '.original';

    if (!file_exists($originalImagePath)) {
        copy($imagePath, $originalImagePath);
    }

    if (isset($_POST['filter'])) {
        applyFilter($imagePath, $_POST['filter']);
    }

    if (isset($_POST['discard'])) {
        unlink($originalImagePath);
        unlink($imagePath);
        header('Location: index.php');
        exit;
    }

    // Handle the finish action
    if (isset($_POST['finish'])) {
        // If the original image was not chosen, delete the original backup
        if ($_POST['finish'] !== 'original' && file_exists($originalImagePath)) {
            unlink($originalImagePath);
        }
        // Redirect to the index page
        header('Location: index.php');
        exit;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Photo Editor</title>
</head>
<body>

<h1>Photo Editor</h1>

<?php if (isset($imagePath)): ?>
    <!-- Display the current image -->
    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Uploaded Image" />
    
    <!-- Form for applying filters -->
    <form action="editor.php?image=<?php echo urlencode($_GET['image']); ?>" method="post">
        <input type="submit" name="filter" value="border" />
        <input type="submit" name="filter" value="bw" />
        <input type="submit" name="filter" value="original" /> 
        <input type="submit" name="discard" value="Discard" />
        <input type="submit" name="finish" value="<?php echo isset($_POST['filter']) ? $_POST['filter'] : 'original'; ?>" />
    </form>
<?php else: ?>
    <p>No image specified.</p>
<?php endif; ?>

</body>
</html>
