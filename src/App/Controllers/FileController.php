<?php namespace Crip\Filesys\App\Controllers;

use Crip\Filesys\App\File;
use Crip\Filesys\Services\FilesysManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class FileController
 * @package Crip\Filesys\App\Controllers
 */
class FileController extends BaseController
{
    /**
     * Upload file to server
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $manager = new FilesysManager($this->package, $request->path);

            if (!$manager->isSafe($file->getClientOriginalExtension(), $file->getMimeType())) {
                return $this->json(['Uploading file is not safe and could not be uploaded.'], 422);
            }

            $blob = $manager->upload($file);

            return $this->json($blob);
        }

        return $this->json(['File not presented for upload.'], 422);
    }

    /**
     * Get file content.
     * @param string $file Path to the requested file
     * @return JsonResponse|Response
     */
    public function show($file)
    {
        $manager = new FilesysManager($this->package, $file);
        if ($manager->isFile()) {
            return new Response($manager->fileContent(), 200, [
                'Content-Type' => $manager->fileMimeType(),
                'Cache-Control' => 'private, max-age=31536000'
            ]);
        }

        return $this->json('File not found.', 404);
    }

    /**
     * Rename file
     * @param Request $request
     * @param string $file
     * @return JsonResponse
     */
    public function update(Request $request, $file)
    {
        if (empty($request->name)) {
            return $this->json('Name property is required.', 422);
        }

        $manager = new FilesysManager($this->package, $file);

        if (!$manager->getMetaData()->isFile()) {
            return $this->json('File not found.', 404);
        }

        $blob = $manager->rename($request->name);

        return $this->json($blob);
    }

    /**
     * Delete file
     * @param string $file
     * @return JsonResponse
     */
    public function destroy($file)
    {
        $manager = new FilesysManager($this->package, $file);

        if (!$manager->blobExists()) {
            return $this->json('File not found.', 404);
        }

        $isRemoved = $manager->delete();

        return $this->json($isRemoved, $isRemoved ? 200 : 500);
    }
}