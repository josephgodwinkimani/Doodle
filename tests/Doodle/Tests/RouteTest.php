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

namespace Doodle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing;

class RouteTest extends TestCase
{
    public function testLeapYearRoute()
    {
        include __DIR__ . "/../../../src/route.php";

        //$routes = new Routing\RouteCollection();
        $route = $routes->get('leap_year');

        $this->assertInstanceOf(Routing\Route::class, $route);
        $this->assertEquals('/is_leap_year/{year}', $route->getPath());
        $this->assertEquals('Home', $route->getDefault('title'));
        $this->assertEquals(null, $route->getDefault('year'));
        $this->assertEquals('Calendar\Controller\LeapYearController::index', $route->getDefault('_controller'));
        $this->assertEquals(['GET'], $route->getMethods());
    }
}
