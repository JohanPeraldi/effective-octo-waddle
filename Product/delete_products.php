<?php
// For debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: http://localhost:5173'); // Allow requests from your Vue app
header('Content-Type: application/json'); // Set the content type to JSON
header("Access-Control-Allow-Methods: DELETE");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once __DIR__ . '/../Database/db_connect.php';

// $data = json_decode(file_get_contents("php://input"));

// Debugging
$rawData = file_get_contents("php://input");
var_dump($rawData); // Check the raw JSON string
$data = json_decode($rawData, true);
// $data = json_decode(file_get_contents("php://input"), true); // Adding true to get an associative array
if (!$data) {
  echo json_encode(["error" => "No data received or JSON decode failed"]);
  exit;
}
var_dump($data); // See exactly what data structure you're getting

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
