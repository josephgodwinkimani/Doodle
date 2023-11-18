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

use Doodle\PsyShellCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use PHPUnit\Util\Reflection;

class PsyShellCommandTest extends TestCase
{

    public function testConfigure()
    {
        $reflection = new \ReflectionClass(PsyShellCommand::class);
        $method = $reflection->getMethod("configure");
        $method->setAccessible(true);

        //$this->assertTrue(method_exists("Doodle\PsyShellCommand", "configure"));

        $this->assertContains("Starts a Psy Shell", [(new PsyShellCommand())->getDescription()]);
    }

    public function testExecute()
    {
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);

        $command = new PsyShellCommand();
        $this->assertTrue(method_exists("Doodle\PsyShellCommand", "execute"));

        //$command->run($input, $output);
    }
}
