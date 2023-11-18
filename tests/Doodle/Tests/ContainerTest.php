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

use Doodle\Container;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function testContainerInstance(): void
    {
        $container = new Container();

        $loader = new YamlFileLoader($container->containerBuilder, new FileLocator(__DIR__ . "/../../../src"));

        // Load a resource
        $loader->load("services.yaml");

        $this->assertInstanceOf(Container::class, $container);
    }
}
