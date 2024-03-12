<?php

namespace backend\Product;

interface ProductFactory
{
  public static function createProduct($attributes, $conn): ProductBase;
}
