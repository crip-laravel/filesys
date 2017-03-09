<?php namespace Crip\Filesys\App\Controllers;

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
            // Configure manager path where file should be uploaded
            // and make sure that directory exists in file system
            $fileInfo = $this->manager->parsePath($request->path);

            // Upload file to the server
            $this->manager->upload($fileInfo, $request->file('file'));

            // If file is image, create all configured sizer for it
            if ($this->manager->isImage($fileInfo)) {
                $this->manager->resizeImage($fileInfo);
            }

            // Return file public url to the uploaded file
            return $this->json($this->manager->publicUrl($fileInfo));
        }

        return $this->json(['File not presented for upload.'], 422);
    }

    /**
     * Get file
     * @param Request $request
     * @param string $file Path to the requested file
     * @return JsonResponse|Response
     */
    public function show(Request $request, $file)
    {
        $fileInfo = $this->manager->parsePath($file, $request->all());

        if ($fileInfo->isFile() && $this->manager->exists($fileInfo)) {
            return new Response($this->manager->fileContent($fileInfo), 200, [
                'Content-Type' => $this->manager->fileMimeType($fileInfo)]);
        }

        return $this->json('File not found.', 401);
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

        $fileInfo = $this->manager->parsePath($file);

        if (!$this->manager->exists($fileInfo)) {
            return $this->json('File not found.', 401);
        }

        $isRenamed = $this->manager->rename($fileInfo, $request->name);

        return $this->json($isRenamed, $isRenamed ? 200 : 500);
    }

    /**
     * Delete file
     * @param string $file
     * @return JsonResponse
     */
    public function destroy($file)
    {
        $fileInfo = $this->manager->parsePath($file);

        if (!$this->manager->exists($fileInfo)) {
            return $this->json('File not found.', 401);
        }

        $isRemoved = $this->manager->delete($fileInfo);

        return $this->json($isRemoved, $isRemoved ? 200 : 500);
    }
}