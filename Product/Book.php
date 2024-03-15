<?php

namespace backend\Product;

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
    $weight = $this->getWeight();

    $stmt = $this->conn->prepare("INSERT INTO book (product_id, weight) VALUES (?, ?)");
    $stmt->execute([$productId, $weight]);
  }
}
