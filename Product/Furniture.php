<?php

namespace backend\Product;

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
