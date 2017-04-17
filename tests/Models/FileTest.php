<?php namespace Crip\Filesys\Models;

use Crip\Filesys\App\File;
use Crip\Filesys\Mocks;
use Crip\Filesys\TestCase;

/**
 * Class FileTest
 * @package Crip\Filesys\Models
 */
class FileTest extends TestCase
{
    /**
     * Can Create Instance
     */
    public function testCanCreateInstance()
    {
        $file = new File();

        $this->assertNotNull($file);
    }

    /**
     * Can Init Instance Details
     */
    public function testCanInitInstanceDetails()
    {
        $file = new File();
        $file->init(Mocks::blob());

        $this->assertNotNull($file);
        $this->assertEquals('ext', $file->extension);
        $this->assertEquals('mime', $file->mime);
        $this->assertEquals('mime-type', $file->mimeType);
        $this->assertEquals(100, $file->bytes);
        $this->assertEquals('dir', $file->dir);
        $this->assertEquals('name', $file->name);
        $this->assertEquals('thumb-url', $file->thumb);
        $this->assertEquals('type', $file->type);
        $this->assertEquals('last-modified', $file->updated_at);
        $this->assertEquals('url', $file->url);
        $this->assertEquals('xs-thumb-url', $file->xs);
        $this->assertEquals('path', $file->path);

        $this->assertArrayHasKey('thumb', $file->thumbs);
    }
}