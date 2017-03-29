<?php namespace Crip\Filesys\App\Controllers;

use Crip\Filesys\Services\FilesysManager;
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
        $manager = new FilesysManager($this->package);

        return $this->json($manager->folderTree());
    }
}