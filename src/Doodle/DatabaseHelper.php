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

class DatabaseHelper
{
    /**
     * Create EntityManager instance
     *
     * The EntityManager is the central access point to Doctrine ORM functionality.
     * It is a facade to all different Doctrine ORM subsystems such as UnitOfWork,
     *
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function init()
    {
        include __DIR__ . "/../../config/entity-config.php";
        //  Obtain the EntityManager
        require __DIR__ . "/../Doodle/Database.php";

        return $entityManager; /** @phpstan-ignore-line */
    }

    /**
     * Create a new instance of the entity
     *
     * @param string $entity Actual name of your entity class
     *
     *
     * @return object
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function entityInit($entity)
    {
        include __DIR__ . "/../../config/entity-config.php";
        //  Obtain the EntityManager
        require __DIR__ . "/../Doodle/Database.php";

        return new $entity();
    }
}
