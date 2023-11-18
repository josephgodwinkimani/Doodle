<?php

declare(strict_types=1);

/*
 * This file is part of the Doodle package.
 *
 * (c) Godwin Kimani <josephgodwinke@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

/*
 * Add your routes here
 */

// demo route
$routes->add(
    "leap_year",
    new Routing\Route("/is_leap_year/{year}", [
        "title" => "Home",
        "year" => null,
        "_controller" => "Calendar\Controller\LeapYearController::index",
    ], [], [], "", [], ["GET"])
);

// demo route
$routes->add(
    "github_api",
    new Routing\Route("/github_api", [
        "_controller" => "Calendar\Controller\LeapYearController::subrequest",
    ], [], [], "", [], ["GET"])
);

// demo route
$routes->add(
    "dog",
    new Routing\Route("/dogs", [
        "title" => "Dogs",
        "_controller" => "Dog\Controller\DogController::index",
    ], [], [], "", [], ["GET"])
);

// demo route
$routes->add(
    "cat",
    new Routing\Route("/cat/form", [
        "title" => "New Cat Form",
        "_controller" => "Cat\Controller\CatController::index",
    ], [], [], "", [], ["GET"])
);

// demo route
$routes->add(
    "market",
    new Routing\Route("/market/products", [
        "title" => "Market",
        "_controller" => "Market\Controller\ProductsController::index",
    ], [], [], "", [], ["GET"])
);

// demo route
$routes->add(
    "market_product_create",
    new Routing\Route("/market/products/create", [
        "title" => "Create Product",
        "_controller" => "Market\Controller\ProductsController::create",
    ], [], [], "", [], ["GET"])
);

return $routes;
