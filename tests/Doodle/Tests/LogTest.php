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
use Doodle\Log;

class LogTest extends TestCase
{
    public function testErrorLogsErrorMessage()
    {
        $log = new Log();
        $log->error("Test error message");

        $logFile = __DIR__ . "/../../../logs/app.log";
        $this->assertFileExists($logFile);
        $this->assertStringContainsString("Test error message", file_get_contents($logFile));
    }
}
