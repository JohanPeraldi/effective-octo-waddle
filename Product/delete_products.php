<?php

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Content-Type: application/json');
header("Access-Control-Allow-Methods: DELETE");
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once __DIR__ . '/../Database/db_connect.php';

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
