<?php namespace Crip\Filesys\App\Controllers;

use Crip\Filesys\TestCase;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

/**
 * Class FileControllerTest
 */
class FileControllerTest extends TestCase
{
    /**
     * @var JsonResponse
     */
    private $response;

    /**
     * Setup the test environment.
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->response = $this->ctrl()->store($this->createUploadRequest('/test/dir/', 'stubs/test.colors.png'));
    }

    /**
     * Can Upload Txt File
     */
    public function testCanUploadTxtFile()
    {
        $response = $this->ctrl()->store($this->createUploadRequest('', 'stubs/test_upload.txt'));

        $info = $response->getData();

        self::assertEquals('txt', $info->extension);
        self::assertEquals('txt', $info->mime);
        self::assertEquals('text/plain', $info->mimeType);
        self::assertTrue(is_array($info->thumbs), 'Thumbs property is not an array.');
        self::assertEquals(0, count($info->thumbs));
        self::assertEquals(16, $info->bytes);
        self::assertEquals('/', $info->dir);
        self::assertEquals('test-upload.txt', $info->fullName);
        self::assertEquals('document', $info->mediaType);
        self::assertEquals('test-upload', $info->name);
        self::assertEquals('/vendor/crip/cripfilesys/images/txt.png', $info->thumb);
        self::assertEquals('file', $info->type);
        self::assertTrue(time() >= $info->updatedAt && time() - 10000 < $info->updatedAt, 'Date of update is recent.');
        self::assertEquals('/storage/test-upload.txt', $info->url);
        self::assertEquals('/vendor/crip/cripfilesys/images/txt.png', $info->xs);
        self::assertEquals('test-upload.txt', $info->path);
    }

    /**
     * Can Upload Png File And Create Thumbs
     */
    public function testCanUploadPngFileAndCreateThumbs()
    {
        $info = $this->response->getData();

        self::assertEquals('png', $info->extension);
        self::assertEquals('img', $info->mime);
        self::assertEquals('image/png', $info->mimeType);
        self::assertEquals(5, count((array)$info->thumbs));

        $this->assertThumbs((array)$info->thumbs);

        self::assertEquals(17855, $info->bytes);
        self::assertEquals('test/dir/', $info->dir);
        self::assertEquals('test-colors.png', $info->fullName);
        self::assertEquals('image', $info->mediaType);
        self::assertEquals('test-colors', $info->name);
        self::assertEquals('/storage/thumb/test/dir/test-colors.png', $info->thumb);
        self::assertEquals('file', $info->type);
        self::assertTrue(time() >= $info->updatedAt && time() - 10000 < $info->updatedAt, 'Date of update is recent.');
        self::assertEquals('/storage/test/dir/test-colors.png', $info->url);
        self::assertEquals('/storage/xs/test/dir/test-colors.png', $info->xs);
        self::assertEquals('test/dir/test-colors.png', $info->path);
    }

    /**
     * Could Not Upload Php File
     */
    public function testCouldNotUploadPhpFile()
    {
        $response = $this->ctrl()->store($this->createUploadRequest('/test/dir/', 'stubs/attack.php'));

        $info = $response->getData();

        self::assertEquals('Uploading file is not safe and could not be uploaded.', $info[0]);
    }

    /**
     * Could Request Uploaded Image And Thumb
     */
    public function testCouldRequestUploadedImageAndThumb()
    {
        $response = $this->ctrl()->show('/test/dir/test-colors.png');

        self::assertEquals(13351, mb_strlen($response->content()));
        self::assertEquals('image/png', $response->headers->get('content-type'));

        $response = $this->ctrl()->show('/thumb/test/dir/test-colors.png');

        self::assertEquals(2150, mb_strlen($response->content()));
        self::assertEquals('image/png', $response->headers->get('content-type'));
    }

    /**
     * Could Not Request Unexisting File
     */
    public function testCouldNotRequestUnexistingFile()
    {
        $response = $this->ctrl()->show('/test/file.png');

        self::assertEquals('application/json', $response->headers->get('content-type'));
        self::assertEquals('"File not found."', $response->content());
        self::assertEquals(404, $response->getStatusCode());
    }

    /**
     * Can Rename File
     */
    public function testCanRenameFile()
    {
        $response = $this->ctrl()->update(new FormRequest(['name' => 'отхер наме']), '/test/dir/test-colors.png');
        $info = $response->getData();

        self::assertEquals('png', $info->extension);
        self::assertEquals('img', $info->mime);
        self::assertEquals('image/png', $info->mimeType);
        self::assertEquals(5, count((array)$info->thumbs));

        $this->assertThumbs((array)$info->thumbs, 'other-name.png');

        self::assertEquals(17855, $info->bytes);
        self::assertEquals('test/dir/', $info->dir);
        self::assertEquals('other-name.png', $info->fullName);
        self::assertEquals('image', $info->mediaType);
        self::assertEquals('other-name', $info->name);
        self::assertEquals('/storage/thumb/test/dir/other-name.png', $info->thumb);
        self::assertEquals('file', $info->type);
        self::assertTrue(time() >= $info->updatedAt && time() - 10000 < $info->updatedAt, 'Date of update is recent.');
        self::assertEquals('/storage/test/dir/other-name.png', $info->url);
        self::assertEquals('/storage/xs/test/dir/other-name.png', $info->xs);
        self::assertEquals('test/dir/other-name.png', $info->path);
    }

    /**
     * Can Delete File
     */
    public function testCanDeleteFile()
    {
        $response = $this->ctrl()->destroy('/test/dir/test-colors.png');

        self::assertEquals('application/json', $response->headers->get('content-type'));
        self::assertEquals('true', $response->content());
        self::assertEquals(200, $response->getStatusCode());
    }

    /**
     * @return FileController
     */
    private function ctrl()
    {
        return app()->make(FileController::class);
    }

    private function assertThumbs($thumbs, $fileName = 'test-colors.png')
    {
        self::assertTrue(isset($thumbs['thumb']), 'Thumb with size "thumb" is not presented.');
        self::assertEquals('/storage/thumb/test/dir/'. $fileName, $thumbs['thumb']->url);

        self::assertTrue(isset($thumbs['xs']), 'Thumb with size "xs" is not presented.');
        self::assertEquals('/storage/xs/test/dir/'. $fileName, $thumbs['xs']->url);

        self::assertTrue(isset($thumbs['sm']), 'Thumb with size "sm" is not presented.');
        self::assertEquals('/storage/sm/test/dir/'. $fileName, $thumbs['sm']->url);

        self::assertTrue(isset($thumbs['md']), 'Thumb with size "md" is not presented.');
        self::assertEquals('/storage/md/test/dir/'. $fileName, $thumbs['md']->url);

        self::assertTrue(isset($thumbs['lg']), 'Thumb with size "lg" is not presented.');
        self::assertEquals('/storage/lg/test/dir/'. $fileName, $thumbs['lg']->url);
    }
}