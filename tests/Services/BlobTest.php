<?php namespace Crip\Filesys\Services;

use Crip\Filesys\App\Folder;
use Crip\Filesys\TestCase;

/**
 * Class BlobTest
 * @package Crip\Filesys\Services
 */
class BlobTest extends TestCase
{
    /**
     * Test Blob Initialized With Correct Path
     */
    public function testBlobInitializedWithCorrectPath()
    {
        $blob = new Blob($this->package/*, '\\\\test\\path\\'*/);

        $this->assertNotNull($blob);
        //$this->assertEquals('test/path', $blob->path);
    }

    /**
     * Test Full Details Returns Folder

    public function testFullDetailsReturnsFolder()
    {
        $mock = $this->createMock(Blob::class);
        $blob = new Blob($this->package, '/test/path');

        $result = $blob->fullDetails($this->blobMetadataMock(false));

        $this->assertNotNull($result);
        $this->assertInstanceOf(Folder::class, $result);
        $this->assertEquals('test/path', $result->path);
    }*/

    /**
     * Generates mock object of the BlobMetadata::class
     * @param bool $isFile
     * @return \Mockery\MockInterface
     */
    private function blobMetadataMock($isFile = false)
    {
        return \Mockery::mock(BlobMetadata::class, [
            'exists' => true,
            'isFile' => $isFile,
            'getSize' => 1000,
            'getDir' => 'root/dir',
            'getFullName' => 'full.name',
            'getName' => 'name',
            'getLastModified' => 'getLastModified',
            'getVisibility' => 'getVisibility',
            'getPath' => 'getPath',
        ]);
    }
}