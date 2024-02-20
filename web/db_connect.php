<?php
$host = 'dpg-cnabbf6n7f5s73el8v3g-a';
$dbname = 'csci4140assignment1';
$username = 'csci4140assignment1_user';
$password = 'XU7wmFLzWij8XPeYQaJXfGCfAKmV05Md';

try {
    $conn = new PDO("pgsql:host=$host;port=5432;dbname=$dbname;user=$username;password=$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query('SELECT version()');
    $version = $stmt->fetchColumn();
    echo "<p>Successfully connected to the Database. Version: " . $version . "</p>";

} catch(PDOException $e) {
    echo "<p>Unable to connect to the database: " . $e->getMessage() . "</p>";
}
?>
