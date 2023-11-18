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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class ResponseEvent extends Event
{
    private $request; /** @phpstan-ignore-line */

    private $response; /** @phpstan-ignore-line */
    public function __construct(Response $response, Request $request)
    {
        $this->response = $response;
        $this->request = $request;
    }

    /**
     * @return Response
     */
    public function getResponse() /** @phpstan-ignore-line */
    {
        return $this->response;
    }

    /**
     * @return Request
     */
    public function getRequest() /** @phpstan-ignore-line */
    {
        return $this->request;
    }
}
