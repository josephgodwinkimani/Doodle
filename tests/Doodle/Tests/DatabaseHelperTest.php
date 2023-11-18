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
use Doctrine\ORM\Mapping\Entity;
use Doodle\DatabaseHelper;

class DatabaseHelperTest extends TestCase
{
    public function testInitReturnsEntityManagerInstance(): void
    {
        $databaseHelper = new DatabaseHelper();
        $entityManager = $databaseHelper->init();

        $this->assertInstanceOf(\Doctrine\ORM\EntityManagerInterface::class, $entityManager);
    }

    public function testEntityInitReturnsInstanceOfGivenEntity(): void
    {
        $databaseHelper = new DatabaseHelper();
        $entity = $databaseHelper->entityInit(Entity::class);

        $this->assertInstanceOf(Entity::class, $entity);
    }
}
