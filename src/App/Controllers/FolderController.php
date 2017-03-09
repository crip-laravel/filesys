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
     * @param string $folder
     */
    public function store(Request $request, $folder)
    {

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
     */
    public function update(Request $request, $folder)
    {

    }

    /**
     * Delete folder
     * @param string $folder
     */
    public function destroy($folder)
    {

    }
}