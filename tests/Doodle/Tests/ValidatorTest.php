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
use Doodle\Validator;

class ValidatorTest extends TestCase
{
    public function testIsDataType()
    {
        $validator = new Validator();

        // Test with a valid value
        $result = $validator->isDataType(234, "int");
        $this->assertEmpty($result);

        // Test with an invalid value
        $result = $validator->isDataType("abc", "int");
        $this->assertNotEmpty($result);
    }

    public function testIsAlpha()
    {
        $validator = new Validator();

        // Test with a valid value
        $result = $validator->isAlpha("abc");
        $this->assertEmpty($result);

        // Test with an invalid value
        $result = $validator->isAlpha("$#2");
        $this->assertNotEmpty($result);
    }

    public function testIsSpecial()
    {
        $validator = new Validator();

        // Test with a valid value
        $result = $validator->isSpecial("$#!");
        $this->assertEmpty($result);

        // Test with an invalid value
        $result = $validator->isSpecial("abc");
        $this->assertNotEmpty($result);
    }

    public function testIsNumber()
    {
        $validator = new Validator();

        // Test with a valid value
        $result = $validator->isNumber(231);
        $this->assertEmpty($result);

        // Test with an invalid value
        $result = $validator->isNumber("abc");
        $this->assertNotEmpty($result);
    }

    public function testIsFloat()
    {
        $validator = new Validator();

        // Test with a valid value
        $result = $validator->isFloat(231.45);
        $this->assertEmpty($result);

        // Test with an invalid value
        $result = $validator->isFloat("231.2!");
        $this->assertNotEmpty($result);
    }

    public function testIsEmail()
    {
        $validator = new Validator();

        // Test with a valid value
        $result = $validator->isEmail("mail@gmail.net");
        $this->assertEmpty($result);

        // Test with an invalid value
        $result = $validator->isEmail("gmail.net");
        $this->assertNotEmpty($result);
    }

    public function testIsDate()
    {
        $validator = new Validator();

        // Test with a valid value
        $result = $validator->isDate("2021-11-08");
        $this->assertEmpty($result);

        // Test with an invalid value
        $result = $validator->isDate("2020-05-08-09");
        $this->assertNotEmpty($result);
    }

    public function testIsDateTime()
    {
        $validator = new Validator();

        // Test with a valid value
        $result = $validator->isDateTime("2021-11-08 12:53:28", "Y-m-d H:i:s");
        $this->assertEmpty($result);

        // Test with an invalid value
        $result = $validator->isDateTime("2023-05-08-09 12:53:28", "Y-m-d H:i:s");
        $this->assertNotEmpty($result);
    }

    public function testIsJson()
    {
        $validator = new Validator();

        // Test with a valid value
        $result = $validator->isJson('{"id":1,"name":"teddy"}');
        $this->assertEmpty($result);

        // Test with an invalid value
        $result = $validator->isJson("teaddy: 1");
        $this->assertNotEmpty($result);
    }

    public function testIsUrl()
    {
        $validator = new Validator();

        // Test with a valid value
        $result = $validator->isUrl("https://gty.com");
        $this->assertEmpty($result);

        // Test with an invalid value
        $result = $validator->isUrl("https://gty.com", ["ftps"]);
        $this->assertNotEmpty($result);
    }

    public function testIsLength()
    {
        $validator = new Validator();

        // Test with a valid value
        $result = $validator->isLength("234", 2, 3, 'UTF-8');
        $this->assertEmpty($result);

        // Test with an invalid value
        $result = $validator->isLength("234");
        $this->assertNotEmpty($result);
    }

    public function testIsEither()
    {
        $validator = new Validator();

        // Test with a valid value
        $result = $validator->isEither("B", ["A", "B"]);
        $this->assertEmpty($result);

        // Test with an invalid value
        $result = $validator->isEither("c", ["A", "B"]);
        $this->assertNotEmpty($result);
    }
}
