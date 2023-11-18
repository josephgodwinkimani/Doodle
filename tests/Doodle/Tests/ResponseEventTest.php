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

use Doodle\ResponseEvent;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseEventTest extends TestCase
{
    public function testGetResponse(): void
    {
        $response = new Response();
        $request = new Request();
        $event = new ResponseEvent($response, $request);

        $this->assertSame($response, $event->getResponse());
    }

    public function testGetRequest(): void
    {
        $response = new Response();
        $request = new Request();
        $event = new ResponseEvent($response, $request);

        $this->assertSame($request, $event->getRequest());
    }
}
