<?php

namespace backend\Product;

class DvdFactory implements ProductFactory
{
  public static function createProduct($attributes, $conn): ProductBase
  {
    return new Dvd($attributes, $conn);
  }
}
