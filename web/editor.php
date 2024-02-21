<?php
include('db_connect.php'); 

function applyFilter($imagePath, $filterType)
{
    $imagick = new Imagick($imagePath);

    switch ($filterType) {
        case 'border':
            $imagick->borderImage(new ImagickPixel('black'), 5, 5);
            break;
        case 'bw':
            $imagick->setImageColorspace(Imagick::COLORSPACE_GRAY);
            break;
        case 'original':
            // If 'original' is selected, restore the original image
            if (file_exists($imagePath . '.original')) {
                copy($imagePath . '.original', $imagePath);
            }
            break;
    }

    if ($filterType !== 'original') {
        $imagick->writeImage($imagePath); // Save the changes
    }

    $imagick->destroy();
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
        if ($_POST['last_filter'] !== 'original' && file_exists($originalImagePath)) {
            unlink($originalImagePath);
        }
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
        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Uploaded Image" />

        <form action="editor.php?image=<?php echo urlencode($_GET['image']); ?>" method="post">
            <input type="submit" name="filter" value="border" />
            <input type="submit" name="filter" value="bw" />
            <input type="submit" name="filter" value="original" />
            <input type="submit" name="discard" value="Discard" />
            <input type="hidden" name="last_filter"
                value="<?php echo isset($_POST['filter']) ? $_POST['filter'] : 'original'; ?>" />
            <input type="submit" name="finish" value="Finish" />
        </form>
    <?php else: ?>
        <p>No image specified.</p>
    <?php endif; ?>

</body>

</html>