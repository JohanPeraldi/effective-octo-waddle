<?php
header('Access-Control-Allow-Origin: http://localhost:5173'); // Allow requests from your Vue app
header('Content-Type: application/json'); // Set the content type to JSON

require_once 'db_connect.php';

$sql = "SELECT p.id, p.sku, p.name, p.price, p.created_at, 
               d.size AS dvd_size, 
               b.weight AS book_weight, 
               f.height AS furniture_height, f.width AS furniture_width, f.length AS furniture_length
        FROM product p
        LEFT JOIN dvd d ON p.id = d.product_id
        LEFT JOIN book b ON p.id = b.product_id
        LEFT JOIN furniture f ON p.id = f.product_id";

$stmt = $conn->prepare($sql);
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($products) {
  echo json_encode($products);
} else {
  echo json_encode([]);
}
