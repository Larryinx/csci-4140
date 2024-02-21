<?php
// Unset the cookie by setting its expiration to an hour ago
setcookie('user', '', time() - 3600);
header('Location: index.php');
exit();
?>
