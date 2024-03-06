<?php

require_once __DIR__ . '/vendor/autoload.php';

use backend\Product\Dvd;
use backend\Database\DatabaseHandler;

// Add a DVD
$dvd = new Dvd($_POST['sku'], $_POST['name'], $_POST['price']);
$dvd->setSize($_POST['size']);

$dbHandler = new DatabaseHandler();
$dbHandler->saveProduct($dvd);

// Add a book

// Add furniture

// Output or redirect as needed
