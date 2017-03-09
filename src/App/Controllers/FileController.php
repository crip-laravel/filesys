<?php namespace Crip\Filesys\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            $this->manager->parsePath($request->path);

            // Upload file to the server
            $file = $this->manager->upload($request->file('file'));

            // If file is image, create all configured sizer for it
            if ($this->manager->isImage($file)) {
                $this->manager->resizeImage($file);
            }

            // Return file public url to the uploaded file
            return $this->json($this->manager->publicUrl($file));
        }

        return $this->json(['File not presented for upload.'], 422);
    }

    /**
     * Get file
     * @param Request $request
     * @param string $file Path to the requested file
     * @return JsonResponse
     */
    public function show(Request $request, $file)
    {
        $this->manager->parsePath($file, $request->all());

        return $this->json($file);
    }

    /**
     * Rename file
     * @param Request $request
     * @param string $file
     */
    public function update(Request $request, $file)
    {

    }

    /**
     * Delete file
     * @param string $file
     */
    public function destroy($file)
    {

    }
}