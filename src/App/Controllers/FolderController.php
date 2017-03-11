<?php namespace Crip\Filesys\App\Controllers;

use Crip\Filesys\App\Folder;
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
        $blob = $this->manager->parsePath($request->folder);

        if (!$this->manager->exists($blob)) {
            return $this->json('Folder not found.', 401);
        }

        if (empty($request->name)) {
            return $this->json('Name property is required.', 422);
        }

        $folder = $this->manager->mkdir($blob, $request->name);

        return $this->json($folder);
    }

    /**
     * List folder content
     * @param string $folder
     * @return JsonResponse
     */
    public function show($folder)
    {
        $blob = $this->manager->parsePath($folder);

        if (!$this->manager->exists($blob)) {
            return $this->json('File not found.', 401);
        }

        $list = $this->manager->folderContent($blob);

        return $this->json($list);
    }

    /**
     * Rename folder name
     * @param Request $request
     * @param string $folder
     * @return JsonResponse
     */
    public function update(Request $request, $folder)
    {
        if (empty($request->name)) {
            return $this->json('Name property is required.', 422);
        }

        $blob = $this->manager->parsePath($folder);

        if (!$this->manager->exists($blob)) {
            return $this->json('Folder not found.', 401);
        }

        $this->manager->rename($blob, $request->name);

        return $this->json(new Folder($blob));
    }

    /**
     * Delete folder
     * @param string $folder
     * @return JsonResponse
     */
    public function destroy($folder)
    {
        $blob = $this->manager->parsePath($folder);

        if (!$this->manager->exists($blob)) {
            return $this->json('Folder not found.', 401);
        }

        $isRemoved = $this->manager->delete($blob);

        return $this->json($isRemoved, $isRemoved ? 200 : 500);
    }
}