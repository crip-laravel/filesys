<?php namespace Crip\Filesys\App\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * Class TreeController
 * @package Crip\Filesys\App\Controllers
 */
class TreeController extends BaseController
{
    /**
     * Get dir tree.
     * @return JsonResponse
     */
    public function __invoke()
    {
        return $this->json($this->manager->folderTree());
    }
}