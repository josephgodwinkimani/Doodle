<?php

// products.php

/*
 * This file is part of the Doodle package.
 *
 * (c) Godwin Kimani <josephgodwinke@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//  Test get all products from database
require_once __DIR__ . '/src/Entities/Product.php';
//  Obtain the EntityManager
require __DIR__ . '/src/Doodle/bootstrap.php';


$productRepository = $entityManager->getRepository('Product');
$array = $productRepository->findAll();


json_encode($array, JSON_FORCE_OBJECT);
