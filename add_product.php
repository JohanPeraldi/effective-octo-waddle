<?php

require_once __DIR__ . '/vendor/autoload.php';

use backend\Product\Book;
use backend\Product\Dvd;
use backend\Product\Furniture;
use backend\Database\DatabaseHandler;

// Add a DVD
$dvd = new Dvd($_POST['sku'], $_POST['name'], $_POST['price']);
$dvd->setSize($_POST['size']);

$dbHandler = new DatabaseHandler();
$dbHandler->saveProduct($dvd);

// Add a book
$book = new Book($_POST['sku'], $_POST['name'], $_POST['price']);
$book->setWeight($_POST['weight']);

$dbHandler->saveProduct($book);

// Add furniture
$furniture = new Furniture($_POST['sku'], $_POST['name'], $_POST['price']);
$furniture->setHeight($_POST['height']);
$furniture->setWidth($_POST['width']);
$furniture->setLength($_POST['length']);

$dbHandler->saveProduct($furniture);

// Output or redirect as needed
header('Location: /');
