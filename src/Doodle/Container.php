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

namespace Doodle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Container
{

    public ContainerBuilder $containerBuilder;

    /**
     * DI container for dependency management in your applications.
     *
     * This holds all the services you want available for your applications.
     *
     * Example:
     * ```
     *  $container = new Container();
     *  $serviceOne = $container->containerBuilder->get('helloworld.service');
     *  print $serviceOne()->notifyAdmin();
     * ```
     */
    public function __construct()
    {
        // DI container that provides an API to easily describe services
        $containerBuilder = new ContainerBuilder();

        $loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . "/../"));

        // Load a resource
        $loader->load("services.yaml");

        $this->containerBuilder = $containerBuilder;
    }
}
