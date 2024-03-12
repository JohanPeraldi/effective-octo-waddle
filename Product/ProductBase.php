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
