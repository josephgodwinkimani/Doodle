<?php

declare(strict_types=1);

namespace Calendar\Model;

class LeapYear
{

    /**
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     *
     * @param string $year
     *
     *
     * @return bool
     */
    public function isLeapYear(?string $year = null): bool
    {
        if (null === $year) {
            $year = \date('Y');
        }

        return 0 === $year % 400 || (0 === $year % 4 && 0 !== $year % 100);
    }
}
