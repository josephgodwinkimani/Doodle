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

class LogListener
{
    protected Log $appLog;

    public function __construct()
    {
        $this->appLog = new Log();
    }

    /**
     * Listen to HTTP responses and log them
     *
     * @param ResponseEvent $event Base class for classes containing event data
     *
     *
     * @return void
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function onResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $statusCode = $response->getStatusCode();

        if (200 === $statusCode) {
            $this->appLog->error($response->getContent());
        }
    }
}
