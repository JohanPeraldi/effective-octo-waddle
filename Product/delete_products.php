<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db_connect.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Construct the filename based on the environment
$envFileName = $environment === 'development' ? '.env.development' : '.env.production';

// Specify the path to the directory containing the .env file
$dotenvDirectoryPath = __DIR__ . '/..';

// Load the environment file
$dotenv = Dotenv\Dotenv::createImmutable($dotenvDirectoryPath, $envFileName);
$dotenv->load();

// Set the response headers
header('Access-Control-Allow-Origin: ' . $_ENV['FRONTEND_URL']);
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: DELETE");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);
if (!$data) {
  echo json_encode(["error" => "No data received or JSON decode failed"]);
  exit;
}

if (!empty($data['ids'])) {
  $placeholders = implode(',', array_fill(0, count($data['ids']), '?'));
  $sql = "DELETE FROM product WHERE id IN ($placeholders)";
  $stmt = $conn->prepare($sql);
  if ($stmt->execute($data['ids'])) {
    echo json_encode(array("message" => "Products deleted successfully."));
  } else {
    echo json_encode(array("message" => "An error occurred while deleting products."));
  }
} else {
  echo json_encode(array("message" => "No IDs provided for deletion."));
}
