<!DOCTYPE html>
<html>

<head>
  <title>Photo Album</title>
  <?php
  // If there's an error parameter in the URL, show an alert
  if (isset($_GET['error'])) {
    echo "<script>alert('Invalid username or password. Please try again.');</script>";
  }
  ?>
  <style>
    .gallery {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      /* Display the images in 4 columns */
      grid-gap: 10px;
    }

    .gallery img {
      width: 100%;
      height: auto;
    }

    .pagination {
      text-align: center;
      margin-top: 20px;
    }

    .pagination a {
      margin: 0 5px;
      text-decoration: none;
    }

    .pagination a.active {
      font-weight: bold;
    }
  </style>
</head>

<body>
  <?php
  include('db_connect.php');

  echo '<h1>Photo Album</h1>';

  $username = isset($_COOKIE['user']) ? htmlspecialchars($_COOKIE['user']) : null;

  if ($username) {
    echo '<p>Welcome, ' . $username . '!</p>';
    echo '<a href="logout.php">Logout</a>';
  } else {
    echo '<p><a href="login.php">Login</a></p>';
  }

  // Define the path to the photos directory
  $photoDir = 'images/';

  // Fetch all jpg, gif, and png photos from the directory
  $allPhotos = glob($photoDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

  // Filter photos to display based on user login status
  $photos = [];
  foreach ($allPhotos as $photo) {
    $photoName = basename($photo);
    // Pattern to check if the photo is public or belongs to the logged-in user
    if (preg_match('/^\d+_public\./', $photoName) || ($username && preg_match('/^\d+_' . preg_quote($username, '/') . '\./', $photoName))) {
      $photos[] = $photo;
    }
  }

  // Sort the photos by the number prefix, descending
  usort($photos, function ($a, $b) {
    $aNum = (int) filter_var(basename($a), FILTER_SANITIZE_NUMBER_INT);
    $bNum = (int) filter_var(basename($b), FILTER_SANITIZE_NUMBER_INT);
    return $bNum - $aNum;
  });

  // Set up pagination
  $perPage = 8; // Number of photos per page
  $totalPhotos = count($photos);
  $totalPages = ceil($totalPhotos / $perPage);
  $currentPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
  $offset = ($currentPage - 1) * $perPage;
  $photosToShow = array_slice($photos, $offset, $perPage);

  // Display the photos
  echo '<div class="gallery">';
  foreach ($photosToShow as $photo) {
    echo '<img src="' . htmlspecialchars(str_replace($_SERVER['DOCUMENT_ROOT'], '', $photo)) . '" alt="Photo">';
  }
  echo '</div>';

  // Display pagination
  echo '<div class="pagination">';
  if ($currentPage > 1) {
    echo '<a href="?page=' . ($currentPage - 1) . '">Previous Page</a>';
  }

  for ($i = 1; $i <= $totalPages; $i++) {
    $class = ($currentPage == $i) ? 'active' : '';
    echo '<a class="' . $class . '" href="?page=' . $i . '">' . $i . '</a>';
  }

  if ($currentPage < $totalPages) {
    echo '<a href="?page=' . ($currentPage + 1) . '">Next Page</a>';
  }
  echo '</div>';
  ?>

  <?php
  // Add a section for uploading photos
  if ($username) {
    // Display file upload form only for logged-in users
    ?>

    <h2>Upload a Photo</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <input type="file" name="photo" required>
      <label>
        <input type="radio" name="mode" value="public" checked> Public
      </label>
      <label>
        <input type="radio" name="mode" value="private"> Private
      </label>
      <button type="submit">Upload</button>
    </form>

    <?php
  } 
  else {
    echo "<h2>You must be logged in to upload photos.</h2>";
  }
  ?>

</body>

</html>