<!DOCTYPE html>
<html>

<head>
  <title>Example PHP page for CSCI4140 24SP</title>
</head>

<body>
  <?php
  echo '<h1>Hello world!</h1>';
  echo '<p>This page uses PHP version '
    . phpversion()
    . '.</p>';
  include('db_connect.php');
  ?>
  <!-- <?php
  $image = new Imagick();
  $image->newImage(100, 100, new ImagickPixel('red'));
  $image->setImageFormat('png');

  header('Content-Type: image/png');
  echo $image;
  ?>
</body> -->

</html>