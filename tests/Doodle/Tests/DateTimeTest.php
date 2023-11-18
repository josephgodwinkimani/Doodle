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
use Doodle\DateTime;

class DateTimeTest extends TestCase
{
    public function testGetDatetime()
    {
        $dateTime = new DateTime();
        $formattedDate = $dateTime->getDatetime("F d, Y H:i");

        // Add your assertions here to test the formatted date string
        $this->assertNotEmpty($formattedDate);
        $this->assertIsString($formattedDate);
        // Add more assertions as needed

        // You can also test for specific date formats, time zones, etc.
    }
}
