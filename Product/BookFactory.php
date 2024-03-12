<?php

namespace backend\Product;

class BookFactory implements ProductFactory
{
  public static function createProduct($attributes, $conn): ProductBase
  {
    return new Book($attributes, $conn);
  }
}
