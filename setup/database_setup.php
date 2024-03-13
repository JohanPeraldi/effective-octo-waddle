<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$dbname = $_ENV['DB_NAME'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];
$socket = $_ENV['DB_SOCKET'];

try {
  // Create a new PDO instance for initial setup without specifying the dbname
  $pdo = new PDO("mysql:host=$host;unix_socket=$socket", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Drop the existing database and create a new one
  $pdo->exec("DROP DATABASE IF EXISTS `$dbname`");
  $pdo->exec("CREATE DATABASE `$dbname`");
  $pdo->exec("USE `$dbname`");

  // SQL statements to create tables
  $tableCreationQueries = [
    "CREATE TABLE product (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            sku VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY sku (sku)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    "CREATE TABLE dvd (
            product_id INT UNSIGNED NOT NULL,
            size INT UNSIGNED NOT NULL,
            PRIMARY KEY (product_id),
            CONSTRAINT dvd_ibfk_1 FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    "CREATE TABLE book (
            product_id INT UNSIGNED NOT NULL,
            weight DECIMAL(5,2) NOT NULL,
            PRIMARY KEY (product_id),
            CONSTRAINT book_ibfk_1 FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

    "CREATE TABLE furniture (
            product_id INT UNSIGNED NOT NULL,
            height INT UNSIGNED NOT NULL,
            width INT UNSIGNED NOT NULL,
            length INT UNSIGNED NOT NULL,
            PRIMARY KEY (product_id),
            CONSTRAINT furniture_ibfk_1 FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
  ];

  // Execute table creation queries
  foreach ($tableCreationQueries as $query) {
    $pdo->exec($query);
  }

  // Insert demo products
  // Begin transaction
  $pdo->beginTransaction();

  // Generic product details
  $products = [
    ['DVD001', 'Monty Python and the Holy Grail', 8.99],
    ['DVD002', 'Brazil', 14.99],
    ['BOOK001', '1984', 9.99],
    ['BOOK002', 'To Kill a Mockingbird', 12.99],
    ['FURN001', 'Table', 129.99],
    ['FURN002', 'Chair', 54.99],
  ];

  // Prepare the SQL statement for inserting products
  $productStmt = $pdo->prepare("INSERT INTO product (sku, name, price, created_at) VALUES (?, ?, ?, NOW())");

  // Specific attributes for each product type
  $dvdSizes = [700, 850];
  $bookWeights = [0.19, 0.28];
  $furnitureDimensions = [
    [74, 90, 120],
    [100, 45, 45],
  ];

  foreach ($products as $index => $product) {
    // Insert product and get its ID
    $productStmt->execute($product);
    $productId = $pdo->lastInsertId();

    // Insert specific attributes based on product type
    if (strpos($product[0], 'DVD') !== false) {
      $pdo->prepare("INSERT INTO dvd (product_id, size) VALUES (?, ?)")->execute([$productId, array_shift($dvdSizes)]);
    } elseif (strpos($product[0], 'BOOK') !== false) {
      $pdo->prepare("INSERT INTO book (product_id, weight) VALUES (?, ?)")->execute([$productId, array_shift($bookWeights)]);
    } elseif (strpos($product[0], 'FURN') !== false) {
      $dimensions = array_shift($furnitureDimensions);
      $pdo->prepare("INSERT INTO furniture (product_id, height, width, length) VALUES (?, ?, ?, ?)")->execute(array_merge([$productId], $dimensions));
    }
  }

  // Commit transaction
  $pdo->commit();

  echo "Database and demo products setup completed successfully.";
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
