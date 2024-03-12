<?php

// For debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5173'); // Allow only your frontend's origin
header('Access-Control-Allow-Methods: POST, OPTIONS'); // Allow only necessary methods
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With'); // Allow only headers needed for your requests

// Respond to preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  exit; // No further action is needed for preflight requests
}

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Database/db_connect.php';

// Debugging
/* echo "Including db_connect.php";
require_once __DIR__ . '/../Database/db_connect.php';
exit; */

use backend\Database\DatabaseHandler;
use backend\Product\BookFactory;
use backend\Product\DvdFactory;
use backend\Product\FurnitureFactory;
use backend\Product\ProductFactoryRegistry;

// Register the product factories
ProductFactoryRegistry::registerFactory('Book', new BookFactory());
ProductFactoryRegistry::registerFactory('DVD', new DvdFactory());
ProductFactoryRegistry::registerFactory('Furniture', new FurnitureFactory());

// Create a DatabaseHandler instance
$dbHandler = new DatabaseHandler($conn);

// Determine the product type
/* if (isset($_POST['productType'])) {
  $productType = $_POST['productType'];
} else {
  echo "Product type not set";
  exit;
} */

// $productType = $data['productType'];

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
  echo json_encode(["error" => "Invalid or missing data"]);
  exit;
}

$productType = $data['productType'] ?? null;
$attributes['sku'] = $data['sku'] ?? null;
$attributes['name'] = $data['name'] ?? null;
$attributes['price'] = $data['price'] ?? null;

if (is_null($productType) || is_null($attributes['sku']) || is_null($attributes['name']) || is_null($attributes['price'])) {
  echo json_encode(["error" => "Missing required fields"]);
  exit;
}

// Collect common data from the form
/* $attributes = [
  'sku' => $_POST['sku'],
  'name' => $_POST['name'],
  'price' => $_POST['price'],
]; */

// Collect specific data based on the product type
switch ($productType) {
  case 'dvd':
    $attributes['size'] = $data['size'] ?? null;
    break;
  case 'book':
    $attributes['weight'] = $data['weight'] ?? null;
    break;
  case 'furniture':
    $attributes['height'] = $data['height'] ?? null;
    $attributes['width'] = $data['width'] ?? null;
    $attributes['length'] = $data['length'] ?? null;
    break;
  default:
    echo "Invalid product type";
    exit;
}

// Rename productType to match Factory types
switch ($productType) {
  case 'dvd':
    $productType = 'DVD';
    break;
  case 'book':
    $productType = 'Book';
    break;
  case 'furniture':
    $productType = 'Furniture';
    break;
}

// Create and save the product
try {
  // $conn = require 'backend/Database/db_connect.php';
  $product = ProductFactoryRegistry::createProduct($productType, $attributes, $conn);
  $dbHandler->saveProduct($product);
  echo json_encode(["message" => "Product saved successfully!"]);
} catch (Exception $e) {
  http_response_code(500);
  echo json_encode(["error" => $e->getMessage()]);
  exit;
}
