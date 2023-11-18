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
use Doodle\Versioning;

class VersioningTest extends TestCase
{
    public function testGetVersion()
    {
        $versioning = new Versioning();

        // Test if the version file exists
        $this->assertFileExists(__DIR__ . "/../../../src/Doodle/version.txt");

        // Replace "expected_version" with the expected version from the file
        $this->assertEquals("1.0.1", $versioning->getVersion());
    }
}
