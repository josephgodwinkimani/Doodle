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

class DateTime
{
    /**
     * Generate a formatted date string
     *
     * @param string $format e.g. `F d, Y H:i`
     *
     *
     * @return string
     *
     *  @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function getDatetime($format)
    {
        $appEnv = new Environment();
        $timezone = $appEnv->get("TZ");

        \date_default_timezone_set($timezone);

        return \date($format);
    }
}
