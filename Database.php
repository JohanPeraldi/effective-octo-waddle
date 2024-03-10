<?php

namespace backend\Database;

use backend\Product\ProductBase;

class DatabaseHandler
{
  private $conn;

  public function __construct()
  {
    // Require the db_connect.php file and use the returned PDO object
    $this->conn = require 'db_connect.php';
  }

  public function saveProduct(ProductBase $product)
  {
    // Use $product's getters to save common properties
    $sku = $product->getSku();
    $name = $product->getName();
    $price = $product->getPrice();

    // Insert into product table
    $stmt = $this->conn->prepare("INSERT INTO product (sku, name, price) VALUES (?, ?, ?)");
    $stmt->execute([$sku, $name, $price]);

    // Get the last inserted product ID
    $lastId = $this->conn->lastInsertId();

    // Insert into the specific product type table based on the product type
    $product->saveProductSpecifics($lastId);
  }
}
