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

use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\WebProcessor;

class Log
{
    /**
     * Adds a log record at the ERROR level.
     *
     * @param string $log Error Message e.g. `$e->getMessage()`
     *
     *
     * @return void
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function error($log)
    {
        $appEnv = new Environment();
        $logFile = $appEnv->get("LOG_FILE");

        $logger = new Logger("logger");
        $logger->pushHandler(new StreamHandler(__DIR__ . "/../../logs/" . $logFile, Logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());

        $logger->pushProcessor(new WebProcessor());

        return $logger->error($log); /** @phpstan-ignore-line */
    }
}
