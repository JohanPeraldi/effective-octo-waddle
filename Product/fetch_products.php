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

$sql = "SELECT p.id, p.sku, p.name, p.price, p.created_at, 
               d.size AS dvd_size, 
               b.weight AS book_weight, 
               f.height AS furniture_height, f.width AS furniture_width, f.length AS furniture_length
        FROM product p
        LEFT JOIN dvd d ON p.id = d.product_id
        LEFT JOIN book b ON p.id = b.product_id
        LEFT JOIN furniture f ON p.id = f.product_id";

try {
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($products);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(["error" => "Failed to fetch products"]);
  exit;
}
