<?php

declare(strict_types=1);

namespace Cat\Service;

class HelloWorldService
{
    public function __invoke()
    {
        return "Hello World!";
    }
}
