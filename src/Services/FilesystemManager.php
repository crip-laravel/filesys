<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;

/**
 * Class FilesystemManager
 * @package Crip\Filesys\Services
 */
class FilesystemManager implements ICripObject
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $pathinfo;

    /**
     * @var string
     */
    private $dir;

    /**
     * @param string $path
     */
    public function parsePath($path = '')
    {
        $this->path = $path;
        $this->pathinfo = pathinfo($path);
        $this->dir = $this->pathinfo['dirname'];

        if (!$this->isFile()) {
            $this->dir = $path;
        }
    }

    public function isFile()
    {
        return array_key_exists('extension', $this->pathinfo);
    }
}