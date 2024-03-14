<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

// Error message in case of a database connection error
$errorMessage = "Failed to connect to the database. Please try again later.";

// Connect to database
try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  return $conn; // Return the PDO connection object

} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(["error" => $errorMessage]);
  exit; // Ensure script stops if connection fails
}
