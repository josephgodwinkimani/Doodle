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

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doodle\Environment;
use Doodle\Database;
use Doctrine\Common\EventManager;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Doctrine\ORM\Mapping\Driver\AttributeReader;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\PsrCachedReader;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testConstruct(): void
    {
        $this->assertFileExists(__DIR__ . "/../../../src/Doodle/Database.php", "database init file exists");

        $this->assertDirectoryExists(__DIR__ . "/../../../src/Entities", "entities folder exists");

        // Create a simple "default" Doctrine ORM configuration for Annotations
        $isDevMode = true;
        $proxyDir = null;
        $cache = null;
        $useSimpleAnnotationReader = false;
        $ORMconfig = Setup::createAnnotationMetadataConfiguration(
            [__DIR__ . "/../../../src/Entities"],
            $isDevMode,
            $proxyDir,
            $cache,
            $useSimpleAnnotationReader
        );

        $ORMconfig->addFilter("soft-deleteable", "Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter");

        $this->assertTrue(class_exists(\Gedmo\Loggable\LoggableListener::class));
        $this->assertTrue(class_exists(\Gedmo\Blameable\BlameableListener::class));
        $this->assertTrue(class_exists(\Gedmo\Timestampable\TimestampableListener::class));

        // Create an instance of the Environment class
        $env = new Environment();

        // database configuration parameters
        $conn = [
            "host" => $env->get("DATABASE_HOST"),
            "port" => $env->get("DATABASE_PORT"),
            "driver" => $env->get("DATABASE_DRIVER"),
            "user" => $env->get("DATABASE_USERNAME"),
            "password" => $env->get("DATABASE_PASSWORD"),
            "dbname" => $env->get("DATABASE_NAME"),
            "charset" => $env->get("DATABASE_CHARSET"),
        ];

        // Create a mock for EntityManager
        $entityManager = $this->createMock(EntityManager::class);

        // central point of Doctrine"s event listener system
        $eventManager = new EventManager();

        // Define our global cache backend for the application.
        // For larger applications, you may use multiple cache pools to store cacheable data in different locations.
        $cache = new ArrayAdapter();

        $annotationReader = null;
        $extensionReader = null;

        // Build the annotation reader for the application when the `doctrine/annotations` package is installed.
        // By default, we will use a decorated reader supporting a backend cache.
        if (class_exists(AnnotationReader::class)) {
            $extensionReader = $annotationReader = new PsrCachedReader(
                new AnnotationReader(),
                $cache
            );
        }

        // Loggable extension
        // Loggable behavior tracks your record changes and is able to manage versions
        $loggableListener = new \Gedmo\Loggable\LoggableListener();
        $loggableListener->setAnnotationReader($extensionReader);
        $loggableListener->setCacheItemPool($cache);
        $loggableListener->setUsername($env->get("LOG_USERNAME"));
        $eventManager->addEventSubscriber($loggableListener);

        // Blameable extension
        // Blameable behavior will automate the update of username or user reference fields on your Entities or Documents.
        // It works through annotations and can update fields on creation, update, property subset update, or even on specific property value change.
        $blameableListener = new \Gedmo\Blameable\BlameableListener();
        $blameableListener->setAnnotationReader($extensionReader);
        $blameableListener->setCacheItemPool($cache);
        $blameableListener->setUserValue($env->get("BLAMEABLE_USERVALUE"));
        $eventManager->addEventSubscriber($blameableListener);

        // Timestampable extension
        $timestampableListener = new \Gedmo\Timestampable\TimestampableListener();
        $timestampableListener->setAnnotationReader($extensionReader);
        $timestampableListener->setCacheItemPool($cache);
        $eventManager->addEventSubscriber($timestampableListener);

        // obtaining the entity manager
        $entityManager = EntityManager::create($conn, $ORMconfig, $eventManager);
    }
}
