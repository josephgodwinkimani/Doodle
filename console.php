#!/usr/bin/env php
<?php
// console.php

require __DIR__ . '/vendor/autoload.php';

use Doodle\PsyShellCommand;
use Symfony\Component\Console\Application;

// main entry point of a Console application
$application = new Application();

// add a command object
$application->add(new PsyShellCommand());

// run the current application
$application->run();
