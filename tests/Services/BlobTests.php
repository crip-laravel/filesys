<?php namespace Crip\Filesys\Services;

use Crip\Filesys\TestCase;

/**
 * Class BlobTests
 * @package Crip\Filesys\Services
 */
class BlobTests extends TestCase
{
    public function testBlobInitializedWithCorrectPath()
    {
        $blob = new Blob($this->package, '\\\\test\\path\\');

        $this->assertEquals('test/path', $blob->path);

        echo 'hello';
    }
}