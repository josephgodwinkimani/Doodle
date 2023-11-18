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
use Symfony\Component\HttpFoundation\Response;
use Doodle\ResponseEvent;
use Doodle\LogListener;
use Doodle\Log;

class LogListenerTest extends TestCase
{
    public function testOnResponseLogsContentFor200StatusCode(): void
    {
        // Create a mock for the ResponseEvent
        $event = $this->createMock(ResponseEvent::class);

        // Create a mock for the Response
        $response = $this->createMock(Response::class);
        $statusCode = $response->method("getStatusCode")->willReturn(200);
        $response->method("getContent")->willReturn("Log error test here");

        $event->method("getResponse")->willReturn($response);

        $this->assertTrue(method_exists("Doodle\LogListener", "onResponse"));

        if ($statusCode === 200) {
            // Create a mock for the Log class
            $log = $this->createMock(Log::class);
            $log->expects($this->once())->method("error")->with("Log error test here");
        }

        $listener = new LogListener();

        $listener->onResponse($event);
    }
}
