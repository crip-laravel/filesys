<?php namespace Crip\Filesys\App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class FolderController
 * @package Crip\Filesys\App\Controllers
 */
class FolderController extends BaseController
{
    /**
     * List root folder content
     * @return JsonResponse
     */
    public function index()
    {
        return $this->show('');
    }

    /**
     * Create new sub folder
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $this->manager->resetPath($request->folder);

        if (empty($request->name)) {
            return $this->json('Name property is required.', 422);
        }

        if (!$this->manager->blobExists()) {
            return $this->json('Folder not found.', 404);
        }

        $result = $this->manager->makeDirectory($request->name)->fullDetails();

        return $this->json($result);
    }

    /**
     * List folder content
     * @param string $folder
     * @return JsonResponse
     */
    public function show($folder)
    {
        $this->manager->resetPath($folder);

        if (!$this->manager->blobExists()) {
            return $this->json('File not found.', 404);
        }

        $list = $this->manager->folderContent();

        return $this->json($list);
    }

    /**
     * Rename folder name
     * @param Request $request
     * @param string $folder
     * @return JsonResponse
     */
    public function update(Request $request, string $folder)
    {
        if (empty($request->name)) {
            return $this->json('Name property is required.', 422);
        }

        $this->manager->resetPath($folder);

        if (!$this->manager->blobExists()) {
            return $this->json('Folder not found.', 404);
        }

        $details = $this->manager->rename($request->name);

        return $this->json($details);
    }

    /**
     * Delete folder
     * @param string $folder
     * @return JsonResponse
     */
    public function destroy($folder)
    {
        $this->manager->resetPath($folder);

        if (!$this->manager->blobExists()) {
            return $this->json('Folder not found.', 404);
        }

        $isRemoved = $this->manager->delete();

        return $this->json($isRemoved, $isRemoved ? 200 : 500);
    }
}