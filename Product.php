<?php

namespace backend\Product;

abstract class ProductBase
{
  protected $sku;
  protected $name;
  protected $price;
  protected $conn; // Database connection

  // Constructor
  public function __construct($attributes, $conn)
  {
    $this->setSku($attributes['sku']);
    $this->setName($attributes['name']);
    $this->setPrice($attributes['price']);
    $this->conn = $conn; // Set the database connection upon instantiation
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

  public function __construct($attributes, $conn)
  {
    parent::__construct($attributes, $conn);
    $this->setSize($attributes['size']);
  }
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

  public function __construct($attributes, $conn)
  {
    parent::__construct($attributes, $conn);
    $this->setWeight($attributes['weight']);
  }
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

  public function __construct($attributes, $conn)
  {
    parent::__construct($attributes, $conn);
    $this->setHeight($attributes['height']);
    $this->setWidth($attributes['width']);
    $this->setLength($attributes['length']);
  }
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

// Product Factory
interface ProductFactory
{
  public static function createProduct($attributes, $conn): ProductBase;
}

class DvdFactory implements ProductFactory
{
  public static function createProduct($attributes, $conn): ProductBase
  {
    return new Dvd($attributes, $conn);
  }
}

class BookFactory implements ProductFactory
{
  public static function createProduct($attributes, $conn): ProductBase
  {
    return new Book($attributes, $conn);
  }
}

class FurnitureFactory implements ProductFactory
{
  public static function createProduct($attributes, $conn): ProductBase
  {
    return new Furniture($attributes, $conn);
  }
}

class ProductFactoryRegistry
{
  private static $factories = [];

  public static function registerFactory($type, ProductFactory $factory)
  {
    self::$factories[$type] = $factory;
  }

  public static function createProduct($type, $attributes, $conn): ProductBase
  {
    if (!isset(self::$factories[$type])) {
      throw new \InvalidArgumentException("No factory registered for type: {$type}");
    }

    $factory = self::$factories[$type];
    $product = $factory::createProduct($attributes, $conn);

    return $product;
  }
}
