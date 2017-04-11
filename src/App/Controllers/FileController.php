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
            $this->manager->resetPath($request->path);

            if (!$this->manager->isSafe($file->getClientOriginalExtension(), $file->getMimeType())) {
                return $this->json(['Uploading file is not safe and could not be uploaded.'], 422);
            }

            $blob = $this->manager->upload($file);

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
        $this->manager->resetPath($file);
        if ($this->manager->isFile()) {
            return new Response($this->manager->fileContent(), 200, [
                'Content-Type' => $this->manager->fileMimeType(),
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

        $this->manager->resetPath($file);

        if (!$this->manager->getMetaData()->isFile()) {
            return $this->json('File not found.', 404);
        }

        $blob = $this->manager->rename($request->name);

        return $this->json($blob);
    }

    /**
     * Delete file
     * @param string $file
     * @return JsonResponse
     */
    public function destroy($file)
    {
        $this->manager->resetPath($file);

        if (!$this->manager->blobExists()) {
            return $this->json('File not found.', 404);
        }

        $isRemoved = $this->manager->delete();

        return $this->json($isRemoved, $isRemoved ? 200 : 500);
    }
}