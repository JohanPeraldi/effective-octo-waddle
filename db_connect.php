<?php

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

$successMessage = "Connected to database successfully!";
$errorMessage = "Connection failed!";

// Connect to database
try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "<h1>$successMessage</h1>";
} catch (PDOException $e) {
  echo "<h1>$errorMessage</h1>";
  echo "<p>" . $e->getMessage() . "</p>";
  exit; // Ensure script stops if connection fails
}

return $conn; // Return the PDO connection object;
