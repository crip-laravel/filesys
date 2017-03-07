<?php namespace Crip\Filesys\App\Controllers;

use Crip\Filesys\Services\FilesystemManager;
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
            $this->manager->parsePath($request->path);

            $file = $this->manager->upload($request->file('file'));

            dd($this->manager, $file, $request->file('file'));

            return $this->json($request->all());
        }

        return $this->json(['File required to upload'], 422);
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