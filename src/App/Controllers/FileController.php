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
     * @var FilesystemManager
     */
    private $manager;

    /**
     * FileController constructor.
     * @param FilesystemManager $filesystemManager
     */
    public function __construct(FilesystemManager $filesystemManager)
    {
        parent::__construct();
        $this->manager = $filesystemManager;
    }

    /**
     * Upload file to server
     * @param Request $request
     */
    public function store(Request $request)
    {

    }

    /**
     * Get file
     * @param string $file
     * @return JsonResponse
     */
    public function show($file)
    {
        $this->manager->parsePath($file);
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