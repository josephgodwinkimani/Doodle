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
use Doodle\CreateProject;

class CreateProjectTest extends TestCase
{
    public function testConstructorCreatesDirectories()
    {
        $createProject = new CreateProject("TestProject");

        $this->assertDirectoryExists(__DIR__ . "/../../../src/TestProject");
    }

    public function testTwigMethodGeneratesFile()
    {
        $createProject = new CreateProject("TestProject");

        // Call the twig method
        $createProject->twig();

        $this->assertFileExists(__DIR__ . "/../../../src/TestProject/Template/Twig.php");
    }

    public function testPugMethodGeneratesFile()
    {
        $createProject = new CreateProject("TestProject2");

        // Call the twig method
        $createProject->pug();

        $this->assertFileExists(__DIR__ . "/../../../src/TestProject2/Template/Pug.php");
    }
}
