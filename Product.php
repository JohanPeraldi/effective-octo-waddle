<?php

namespace backend\Product;

abstract class ProductBase
{
  protected $sku;
  protected $name;
  protected $price;
  protected $conn; // Database connection

  // Constructor
  public function __construct($sku, $name, $price)
  {
    $this->setSku($sku);
    $this->setName($name);
    $this->setPrice($price);
  }

  // Setters
  public function setSku($sku)
  {
    $this->sku = $sku;
  }
  public function setName($name)
  {
    $this->name = $name;
  }
  public function setPrice($price)
  {
    $this->price = $price;
  }
  public function setConnection($conn)
  {
    $this->conn = $conn;
  }

  // Getters
  public function getSku()
  {
    return $this->sku;
  }
  public function getName()
  {
    return $this->name;
  }
  public function getPrice()
  {
    return $this->price;
  }

  // Abstract method declaration for saving product specifics
  abstract public function saveProductSpecifics($productId);
}

class Dvd extends ProductBase
{
  private $size;

  public function setSize($size)
  {
    $this->size = $size;
  }
  public function getSize()
  {
    return $this->size;
  }

  public function saveProductSpecifics($productId)
  {
    // Implement saving logic specific to DVD products
    $size = $this->getSize();

    $stmt = $this->conn->prepare("INSERT INTO dvd (product_id, size) VALUES (?, ?)");
    $stmt->execute([$productId, $size]);
  }
}

class Book extends ProductBase
{
  private $weight;

  public function setWeight($weight)
  {
    $this->weight = $weight;
  }
  public function getWeight()
  {
    return $this->weight;
  }

  public function saveProductSpecifics($productId)
  {
    // Implement saving logic specific to book products
    $weight = $this->getWeight();

    $stmt = $this->conn->prepare("INSERT INTO book (product_id, weight) VALUES (?, ?)");
    $stmt->execute([$productId, $weight]);
  }
}

class Furniture extends ProductBase
{
  private $height;
  private $width;
  private $length;

  public function setHeight($height)
  {
    $this->height = $height;
  }
  public function setWidth($width)
  {
    $this->width = $width;
  }
  public function setLength($length)
  {
    $this->length = $length;
  }
  public function getHeight()
  {
    return $this->height;
  }
  public function getWidth()
  {
    return $this->width;
  }
  public function getLength()
  {
    return $this->length;
  }

  public function saveProductSpecifics($productId)
  {
    $height = $this->getHeight();
    $width = $this->getWidth();
    $length = $this->getLength();

    $stmt = $this->conn->prepare("INSERT INTO furniture (product_id, height, width, length) VALUES (?, ?, ?, ?)");
    $stmt->execute([$productId, $height, $width, $length]);
  }
}
