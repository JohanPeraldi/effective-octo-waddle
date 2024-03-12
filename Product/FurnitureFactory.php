<?php

namespace backend\Product;

class FurnitureFactory implements ProductFactory
{
  public static function createProduct($attributes, $conn): ProductBase
  {
    return new Furniture($attributes, $conn);
  }
}
