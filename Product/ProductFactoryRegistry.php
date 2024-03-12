<?php

namespace backend\Product;

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
