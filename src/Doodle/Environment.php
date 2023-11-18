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

use Symfony\Component\Dotenv\Dotenv;

class Environment
{

    /**
     * Get an environment variable from dotenv files
     * e.g. .env.local, .env.$env and .env.$env.local files if they exist
     *
     * @param string $env        variable e.g. `APP_NAME`
     *
     * @param string $defaultEnv variable that only accepts either 'dev', 'test'
     *
     * @return string
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function get($env, $defaultEnv = "dev")
    {
        $dotenv = new Dotenv();
        $defaultEnv === "test" ? $dotenv->loadEnv(__DIR__ . "/../../.env.test") : $dotenv->loadEnv(__DIR__ . "/../../.env.dist");

        return $_ENV[$env];
    }
}
