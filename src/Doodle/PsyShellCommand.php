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

use Psy\Shell;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PsyShellCommand extends Command
{
    protected static $defaultName = "psy:shell";

    /**
     * Configure the current command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setDescription("Starts a Psy Shell");
    }

    /**
     * Executes the current command
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arr = include __DIR__ . "\..\console.php";
        $shell = new Shell();
        $shell->setIncludes(array_merge($arr, [
            __DIR__ . "\CreateProject.php",
            __DIR__ . "\Environment.php",
            __DIR__ . "\Database.php",
            __DIR__ . "\DatabaseHelper.php",
            __DIR__ . "\DateTime.php",
            __DIR__ . "\FileSystemAdapter.php",
            __DIR__ . "\Log.php",
            __DIR__ . "\ResponseEvent.php",
            __DIR__ . "\Validator.php",

        ]));
        $shell->run();
    }
}
