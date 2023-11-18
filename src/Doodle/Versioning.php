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

use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToReadFile;
use Version\Version;

/**
 * A Class to create and provide a SemVer-compliant version number for the framework library
 * version number MAJOR.MINOR.PATCH
 */

class Versioning
{
    /**
     * Get version of the framework
     *
     * @return string $version
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function getVersion()
    {
        $filesystemAdapter = new FileSystemAdapter();
        $filesystem = $filesystemAdapter->framework();

        try {
            $version = $filesystem->read("version.txt");
        } catch (FilesystemException | UnableToReadFile | \Exception $exception) {
            // handle the error
            $version = "no version detected";
        }

        return $version;
    }

    /**
     * Update the version of framework
     *
     * (WIP)
     * @return string $latest
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function updateVersion()
    {
        $filesystemAdapter = new FileSystemAdapter();
        $filesystem = $filesystemAdapter->framework();

        try {
            $version = $filesystem->read("version.txt");

            $old = Version::fromString($version);
            $new = $old->incrementPatch();
            $latest = $new->toString();

            $filesystem->write("version.txt", $latest);
        } catch (FilesystemException | UnableToReadFile | \Exception $exception) {
            // handle the error
            $latest = "version could not be updated";
        }

        return $latest;
    }
}
