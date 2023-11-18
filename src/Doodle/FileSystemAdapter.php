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

use League\Flysystem\Filesystem;
use League\Flysystem\Ftp\FtpAdapter;
use League\Flysystem\Ftp\FtpConnectionOptions;
use League\Flysystem\Ftp\FtpConnectionProvider;
use League\Flysystem\Ftp\NoopCommandConnectivityChecker;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\PhpseclibV2\SftpAdapter;
use League\Flysystem\PhpseclibV2\SftpConnectionProvider;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;
use League\Flysystem\ZipArchive\FilesystemZipArchiveProvider;

/**
 * one interface to interact with many different types of filesystems
 */

class FileSystemAdapter
{
    /**
     * Interact with framework filesystem
     *
     *
     * @return \League\Flysystem\Filesystem
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function framework(): Filesystem
    {
        // The internal adapter
        $adapter = new LocalFilesystemAdapter(
            // Determine the root directory
            __DIR__ . "/",
            // Customize how visibility is converted to unix permissions
            PortableVisibilityConverter::fromArray([
                "file" => [
                    "public" => 0640,
                    "private" => 0604,
                ],
                "dir" => [
                    "public" => 0740,
                    "private" => 7604,
                ],
            ]),
            // Write flags
            \LOCK_EX,
            // How to deal with links, either DISALLOW_LINKS or SKIP_LINKS
                // Disallowing them causes exceptions when encountered
            LocalFilesystemAdapter::DISALLOW_LINKS
        );

        // The FilesystemOperator
        return new Filesystem($adapter);
    }

    /**
     * Interact with any local filesystem
     *
     * @param $path Path
     *
     * @return \League\Flysystem\Filesystem
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function local($path): Filesystem
    {
        // The internal adapter
        $adapter = new LocalFilesystemAdapter(
            // Determine the root directory
            $path,
            // Customize how visibility is converted to unix permissions
            PortableVisibilityConverter::fromArray([
                "file" => [
                    "public" => 0640,
                    "private" => 0604,
                ],
                "dir" => [
                    "public" => 0740,
                    "private" => 7604,
                ],
            ]),
            // Write flags
            \LOCK_EX,
            // How to deal with links, either DISALLOW_LINKS or SKIP_LINKS
                // Disallowing them causes exceptions when encountered
            LocalFilesystemAdapter::DISALLOW_LINKS
        );

        // The FilesystemOperator
        return new Filesystem($adapter);
    }

    /**
     * Interact with ftp filesystem
     *
     * @return \League\Flysystem\Filesystem
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function ftp(): Filesystem
    {
        $appEnv = new Environment();
        $hostname = $appEnv->get("FTP_HOSTNAME");
        $path = $appEnv->get("FTP_ROOT_PATH");
        $username = $appEnv->get("FTP_USERNAME");
        $password = $appEnv->get("FTP_PASSWORD");
        $port = (int) $appEnv->get("FTP_PORT");
        $ssl = $appEnv->get("FTP_SSL");
        $charset = $appEnv->get("FTP_CHARSET");
        $passiveConnectionMode = $appEnv->get("FTP_CONNECTION_MODE");
        $systemType = $appEnv->get("FTP_SYSTEMTYPE");
        $ignorePassiveAddress = $appEnv->get("FTP_IGNOREPASSIVE_ADDRESS");
        $timestampsOnUnixListingsEnabled = $appEnv->get("FTP_TIMESTAMPLISTING_ENABLED");
        $recurseManually = $appEnv->get("FTP_RECURSE_MANUALLY");

        // The internal adapter
        $adapter = new FtpAdapter(
            // Connection options
            FtpConnectionOptions::fromArray([
                "host" => $hostname, // required
                "root" => $path, // required
                "username" => $username, // required
                "password" => $password, // required
                "port" => $port,
                "ssl" => (bool) $ssl,
                "timeout" => 90,
                "utf8" => (bool) $charset,
                "passive" => (bool) $passiveConnectionMode,
                "transferMode" => \FTP_BINARY,
                "systemType" => $systemType, // 'windows' or 'unix'
                "ignorePassiveAddress" => (bool) $ignorePassiveAddress, // true or false
                "timestampsOnUnixListingsEnabled" => (bool) $timestampsOnUnixListingsEnabled, // true or false
                "recurseManually" => (bool) $recurseManually, // true
            ]),
            new FtpConnectionProvider(),
            new NoopCommandConnectivityChecker(),
            new PortableVisibilityConverter()
        );

        // The FilesystemOperator
        return new Filesystem($adapter);
    }

    /**
     * Interact with in memory filesystem that is not persisted
     *
     * @return \League\Flysystem\Filesystem
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function memory(): Filesystem
    {
        // The internal adapter
        $adapter = new InMemoryFilesystemAdapter();

        // The FilesystemOperator
        return new Filesystem($adapter);
    }

    /**
     * Interact with sftp v2 filesystem
     *
     * This implementation uses version 2 of phpseclib
     *
     * @return \League\Flysystem\Filesystem
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function sftp(): Filesystem
    {
        $appEnv = new Environment();
        $hostname = $appEnv->get("SFTP_HOSTNAME");
        $username = $appEnv->get("SFTP_USERNAME");
        $password = $appEnv->get("SFTP_PASSWORD");
        $port = $appEnv->get("SFTP_PORT");
        $port = (int) $port;
        $fingerprint = $appEnv->get("SFTP_FINGERPRINT");

        $filesystem = new Filesystem(
            new SftpAdapter(
                new SftpConnectionProvider(
                    $hostname, // host (required)
                    $username, // username (required)
                    $password, // password (optional, default: null) set to null if privateKey is used
                    null, // private key (optional, default: null) can be used instead of password, set to null if password is set
                    null, // passphrase (optional, default: null), set to null if privateKey is not used or has no passphrase
                    $port, // port (optional, default: 22)
                    true, // use agent (optional, default: false)
                    30, // timeout (optional, default: 10)
                    10, // max tries (optional, default: 4)
                    $fingerprint, //'fingerprint-string' - host fingerprint (optional, default: null),
                    null, // connectivity checker (must be an implementation of 'League\Flysystem\PhpseclibV2\ConnectivityChecker' to check if a connection can be established (optional, omit if you don't need some special handling for setting reliable connections)
                ),
                "/upload",
                // root path (required)
                PortableVisibilityConverter::fromArray([
                    "file" => [
                        "public" => 0640,
                        "private" => 0604,
                    ],
                    "dir" => [
                        "public" => 0740,
                        "private" => 7604,
                    ],
                ])
            )
        );

        return $filesystem;
    }

    /**
     * Interact with zip files in local filesystem
     *
     * This implementation uses php ZipArchive class
     *
     * @param $pathToZip Path to zip archive
     *
     * @return \League\Flysystem\Filesystem
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function zip($pathToZip): Filesystem
    {
        $adapter = new ZipArchiveAdapter(
            new FilesystemZipArchiveProvider($pathToZip)
        );

        return new Filesystem($adapter);
    }
}
