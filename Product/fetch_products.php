<?php

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Content-Type: application/json');

require_once __DIR__ . '/../Database/db_connect.php';

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
