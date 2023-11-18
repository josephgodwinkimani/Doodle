<?php

declare(strict_types=1);

namespace Doodle\Tests;

use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;
use Doodle\FileSystemAdapter;

class FileSystemAdapterTest extends TestCase
{
    public function testFrameworkMethodReturnsFilesystemInstance(): void
    {
        $adapter = new FileSystemAdapter();
        $filesystem = $adapter->framework();
        $this->assertInstanceOf(Filesystem::class, $filesystem);
    }

    public function testLocalPathReturnsFilesystemInstance(): void
    {
        $adapter = new FileSystemAdapter();
        $filesystem = $adapter->local("/");
        $this->assertInstanceOf(Filesystem::class, $filesystem);
    }

    public function testFtpPathReturnsFilesystemInstance(): void
    {
        $adapter = new FileSystemAdapter();
        $filesystem = $adapter->ftp();
        $this->assertInstanceOf(Filesystem::class, $filesystem);
    }

    public function testMemoryPathReturnsFilesystemInstance(): void
    {
        $adapter = new FileSystemAdapter();
        $filesystem = $adapter->memory();
        $this->assertInstanceOf(Filesystem::class, $filesystem);
    }

    public function testSFtpPathReturnsFilesystemInstance(): void
    {
        $adapter = new FileSystemAdapter();
        $filesystem = $adapter->sftp();
        $this->assertInstanceOf(Filesystem::class, $filesystem);
    }

    public function testZipPathReturnsFilesystemInstance(): void
    {
        $adapter = new FileSystemAdapter();
        $filesystem = $adapter->zip("https://wordpress.org/latest.zip");
        $this->assertInstanceOf(Filesystem::class, $filesystem);
    }
}
