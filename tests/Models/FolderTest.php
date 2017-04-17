<?php namespace Crip\Filesys\Models;

use Crip\Filesys\App\Folder;
use Crip\Filesys\Mocks;
use Crip\Filesys\TestCase;

/**
 * Class FolderTest
 * @package Crip\Filesys\Models
 */
class FolderTest extends TestCase
{
    /**
     * Can Create Instance
     */
    public function testCanCreateInstance()
    {
        $folder = new Folder();

        $this->assertNotNull($folder);
    }

    /**
     * Can Init Instance Details
     */
    public function testCanInitInstanceDetails()
    {
        $folder = new Folder();
        $folder->init(Mocks::blob());

        $this->assertNotNull($folder);
        $this->assertEquals(100, $folder->bytes);
        $this->assertEquals('dir', $folder->dir);
        $this->assertEquals('name', $folder->name);
        $this->assertEquals('thumb-url', $folder->thumb);
        $this->assertEquals('type', $folder->type);
        $this->assertEquals('last-modified', $folder->updated_at);
        $this->assertEquals('url', $folder->url);
        $this->assertEquals('xs-thumb-url', $folder->xs);
        $this->assertEquals('path', $folder->path);
    }
}