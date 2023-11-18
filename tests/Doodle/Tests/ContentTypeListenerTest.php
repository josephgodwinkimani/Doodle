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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Doodle\ContentTypeListener;
use Doodle\ResponseEvent;
use PHPUnit\Framework\TestCase;

class ContentTypeListenerTest extends TestCase
{
    public function testOnResponseSetsXContentTypeOptionsHeader()
    {
        // Create a mock ResponseEvent
        $event = $this->createMock(ResponseEvent::class);

        // Create a mock Response object
        $response = $this->createMock(Response::class);
        $response->headers = $this->createMock(ResponseHeaderBag::class);

        // Set up the expectations
        $response->headers->expects($this->once())
            ->method("set")
            ->with("X-Content-Type-Options", "nosniff");

        // Set the response on the event
        $event->expects($this->once())
            ->method("getResponse")
            ->willReturn($response);

        // Create an instance of the ContentTypeListener
        $listener = new ContentTypeListener();

        // Call the onResponse method
        $listener->onResponse($event);
    }
}
