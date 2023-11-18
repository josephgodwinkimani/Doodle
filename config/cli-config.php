#!/usr/bin/env php
<?php

// cli-config.php

/*
 * This file is part of the Doodle package.
 *
 * (c) Godwin Kimani <josephgodwinke@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

//  Obtain the EntityManager
require_once __DIR__ . "/../src/Doodle/Database.php";

// Runs console with the given helper set
return ConsoleRunner::run(new SingleManagerProvider($entityManager));