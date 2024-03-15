<?php

namespace backend\Product;

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
    $size = $this->getSize();

    $stmt = $this->conn->prepare("INSERT INTO dvd (product_id, size) VALUES (?, ?)");
    $stmt->execute([$productId, $size]);
  }
}
