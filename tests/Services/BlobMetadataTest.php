<?php namespace Crip\Filesys\Services;

use Crip\Filesys\TestCase;

/**
 * Class BlobMetadataTest
 * @package Crip\Filesys\Services
 */
class BlobMetadataTest extends TestCase
{
    /**
     * Can Create Instance
     */
    public function testCanCreateInstance()
    {
        $meta = new BlobMetadata();

        $this->assertNotNull($meta);
    }

    /**
     * Initializes For Not Existing File
     */
    public function testInitializesForNotExistingFile()
    {
        $meta = new BlobMetadata();
        $meta->init('path/to/unknown.txt');

        $this->assertNotNull($meta);
        $this->assertEquals('path/to/unknown.txt', $meta->getPath());
        $this->assertFalse($meta->isFile());
    }

    /**
     * Initializes For Existing File
     */
    public function testInitializesForExistingFile()
    {
        $meta = new BlobMetadata();
        $meta->init('path/to/file.txt');

        $this->assertNotNull($meta);
        $this->assertEquals('path/to/file.txt', $meta->getPath());
        $this->assertFalse($meta->isFile());
    }
}