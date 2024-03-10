<?php

header('Access-Control-Allow-Origin: http://localhost:5173'); // Allow only your frontend's origin
header('Access-Control-Allow-Methods: POST, OPTIONS'); // Allow only necessary methods
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With'); // Allow only headers needed for your requests

// Respond to preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  exit; // No further action is needed for preflight requests
}

require_once __DIR__ . '/vendor/autoload.php';

use backend\Database\DatabaseHandler;
use backend\Product\ProductFactoryRegistry;
use backend\Product\DvdFactory;
use backend\Product\BookFactory;
use backend\Product\FurnitureFactory;

// Register the product factories
ProductFactoryRegistry::registerFactory('DVD', new DvdFactory());
ProductFactoryRegistry::registerFactory('Book', new BookFactory());
ProductFactoryRegistry::registerFactory('Furniture', new FurnitureFactory());

$conn = require 'backend/Database/db_connect.php';

// Create a DatabaseHandler instance
$dbHandler = new DatabaseHandler($conn);

// Determine the product type
$productType = $_POST['productType'];

// Collect common data from the form
$attributes = [
  'sku' => $_POST['sku'],
  'name' => $_POST['name'],
  'price' => $_POST['price'],
];

// Collect specific data based on the product type
switch ($productType) {
  case 'DVD':
    $attributes['size'] = $_POST['size'];
    break;
  case 'Book':
    $attributes['weight'] = $_POST['weight'];
    break;
  case 'Furniture':
    $attributes['height'] = $_POST['height'];
    $attributes['width'] = $_POST['width'];
    $attributes['length'] = $_POST['length'];
    break;
  default:
    echo "Invalid product type";
    exit;
}

// Create and save the product
try {
  $product = ProductFactoryRegistry::createProduct($productType, $attributes, $conn);
  $dbHandler->saveProduct($product);
  echo "Product saved successfully!";
} catch (Exception $e) {
  echo "Error saving product: " . $e->getMessage();
}
