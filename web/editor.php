<?php
include('db_connect.php'); // Make sure this points to your actual database connection script

// Function to apply filters using ImageMagick
function applyFilter($imagePath, $filterType)
{
    $imagick = new Imagick($imagePath);

    switch ($filterType) {
        case 'border':
            $imagick->borderImage(new ImagickPixel('black'), 5, 5); // Adds a black border
            break;
        case 'bw':
            $imagick->setImageColorspace(Imagick::COLORSPACE_GRAY); // Converts to grayscale
            break;
        case 'original':
            // No need to do anything here, as we just display the original image
            break;
    }

    if ($filterType !== 'original') {
        $imagick->writeImage($imagePath); // Write the changes to the same image file
    }

    $imagick->destroy(); // Clears the Imagick object from memory
}

// Check if an image is specified and a filter action is requested
if (isset($_GET['image'])) {
    $imagePath = 'images/' . $_GET['image'];
    $originalImagePath = $imagePath . '.original'; // We'll use this to store the original image

    // If it's the first time, copy the original image for backup
    if (!file_exists($originalImagePath)) {
        copy($imagePath, $originalImagePath);
    }

    if (isset($_POST['filter'])) {
        applyFilter($imagePath, $_POST['filter']);
    }

    if (isset($_POST['finish'])) {
        // If the user finished editing and didn't choose 'original', keep the edited image
        if ($_POST['filter'] !== 'original' && file_exists($originalImagePath)) {
            unlink($originalImagePath); // Delete the backup of the original image
        } else {
            // If the user finished editing and chose 'original', delete the edited image and keep the original
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the edited image
            }
            // Restore the original image
            if (file_exists($originalImagePath)) {
                rename($originalImagePath, $imagePath);
            }
        }
        header('Location: index.php'); // Redirect to the index page
        exit;
    }

    // If the user wants to discard the photo
    if (isset($_POST['discard'])) {
        unlink($originalImagePath); // Delete the original image
        unlink($imagePath); // Delete the edited image
        header('Location: index.php'); // Redirect to the index page
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
            <input type="hidden" name="filter"
                value="<?php echo isset($_POST['filter']) ? $_POST['filter'] : 'original'; ?>" />
            <input type="submit" name="filter" value="border" />
            <input type="submit" name="filter" value="bw" />
            <input type="submit" name="filter" value="original" />
            <input type="submit" name="discard" value="Discard" />
            <input type="submit" name="finish" value="Finish" /> <!-- Button to finish editing -->
        </form>
    <?php else: ?>
        <p>No image specified.</p>
    <?php endif; ?>

</body>

</html>