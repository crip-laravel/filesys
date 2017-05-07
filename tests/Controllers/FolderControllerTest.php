<?php namespace Crip\Filesys\App\Controllers;

use Crip\Filesys\TestCase;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class FolderControllerTest
 * @property \Illuminate\Http\JsonResponse response
 * @package Controllers
 */
class FolderControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->response = $this->ctrl()->store(new FormRequest([
            'folder' => '',
            'name' => 'dir'
        ]));
    }

    /**
     * Can Create Duplicate Dir
     */
    public function testCanCreateDuplicateDir()
    {
        $response = $this->ctrl()->store(new FormRequest([
            'folder' => '',
            'name' => 'dir'
        ]));

        $info = $response->getData(true);

        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals(0, $info['bytes']);
        self::assertEquals('', $info['dir']);
        self::assertEquals('dir-1', $info['fullName']);
        self::assertEquals('dir', $info['mediaType']);
        self::assertEquals('dir-1', $info['name']);
        self::assertEquals('/vendor/crip/cripfilesys/images/dir.png', $info['thumb']);
        self::assertEquals('dir', $info['type']);
        self::assertTrue(isset($info['updatedAt']), 'updatedAt property dose not exists.');
        self::assertEquals('/storage/dir-1', $info['url']);
        self::assertEquals('/vendor/crip/cripfilesys/images/dir.png', $info['xs']);
        self::assertEquals('dir-1', $info['path']);
    }

    /**
     * Can Read Folders And Files In Dir
     */
    public function testCanReadFoldersAndFilesInDir()
    {
        $this->ctrl()->store(new FormRequest(['folder' => 'dir', 'name' => 'dir']));
        app()->make(FileController::class)->store($this->createUploadRequest('dir', 'stubs/test_upload.txt'));

        $response = $this->ctrl()->show('dir');
        $content = $response->getData();

        self::assertEquals(2, count($content));

        $dir = collect($content)->where('type', 'dir')->first();
        $file = collect($content)->where('type', 'file')->first();

        self::assertEquals('/storage/dir/dir', $dir->url);
        self::assertEquals('/storage/dir/test-upload.txt', $file->url);
    }

    /**
     * Can Read Folders And Files In Dir
     */
    public function testCanRenameFoldersAndFilesInDir()
    {
        $this->ctrl()->store(new FormRequest(['folder' => 'dir', 'name' => 'dir']));
        app()->make(FileController::class)->store($this->createUploadRequest('dir', 'stubs/test.colors.png'));

        $response = $this->ctrl()->update(new FormRequest(['name' => 'new']), 'dir');
        $info = $response->getData(true);

        self::assertEquals(0, $info['bytes']);
        self::assertEquals('', $info['dir']);
        self::assertEquals('new', $info['fullName']);
        self::assertEquals('dir', $info['mediaType']);
        self::assertEquals('new', $info['name']);
        self::assertEquals('/vendor/crip/cripfilesys/images/dir.png', $info['thumb']);
        self::assertEquals('dir', $info['type']);
        self::assertTrue(isset($info['updatedAt']), 'updatedAt property dose not exists.');
        self::assertEquals('/storage/new', $info['url']);
        self::assertEquals('/vendor/crip/cripfilesys/images/dir.png', $info['xs']);
        self::assertEquals('new', $info['path']);

        // ensure that files in dir are mowed too

        $response = $this->ctrl()->show('new');
        $content = $response->getData();

        self::assertEquals(2, count($content));

        $dir = collect($content)->where('type', 'dir')->first();
        $file = collect($content)->where('type', 'file')->first();

        self::assertEquals('/storage/new/dir', $dir->url);
        self::assertEquals('/storage/new/test-colors.png', $file->url);
        self::assertEquals('/storage/thumb/new/test-colors.png', $file->thumbs->thumb->url);
    }

    /**
     * Cant Rename Unexisting Dir
     */
    public function testCantRenameUnexistingDir()
    {
        $response = $this->ctrl()->update(new FormRequest(['name' => 'new']), 'unexisting');

        self::assertEquals('application/json', $response->headers->get('content-type'));
        self::assertEquals('"Folder not found."', $response->content());
        self::assertEquals(404, $response->getStatusCode());
    }

    /**
     * Can Delete Dir
     */
    public function testCanDeleteDir()
    {
        $response = $this->ctrl()->destroy('dir');

        self::assertEquals('application/json', $response->headers->get('content-type'));
        self::assertEquals('true', $response->content());
        self::assertEquals(200, $response->getStatusCode());

        $response = $this->ctrl()->show('');
        self::assertEquals(0, count($response->getData()));
    }

    /**
     * @return FolderController
     */
    private function ctrl()
    {
        return app()->make(FolderController::class);
    }
}