<?php namespace Crip\Filesys\Services;

use Crip\Core\Support\PackageBase;
use Illuminate\Filesystem\Filesystem;

/**
 * Class FolderInfo
 * @package Crip\Filesys\Services
 */
class FolderInfo
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $currPath;

    /**
     * @var string
     */
    private $parentPath;

    /**
     * @var string
     */
    private $dir;

    /**
     * @var string
     */
    private $currDir;

    /**
     * @var string
     */
    private $parentDir;
    /**
     * @var PackageBase
     */
    private $package;

    /**
     * @var string
     */
    private $url;

    /**
     * FolderInfo constructor.
     * @param PackageBase $package
     * @param $path
     */
    public function __construct(PackageBase $package, $path)
    {
        $this->package = $package;
        $this->setPath($path);
    }

    /**
     * Create current dir in file system.
     */
    public function mk()
    {
        app(Filesystem::class)->makeDirectory($this->dir, 0777, true, true);
    }

    /**
     * @param $newName
     * @return string
     */
    public function setName($newName)
    {
        $pathParts = explode('/', $this->path);
        array_pop($pathParts);
        $pathParts[] = $newName;
        $newPath = join('/', $pathParts);
        $this->setPath($newPath);

        return $newPath;
    }

    /**
     * @param $name
     */
    public function setSubfolder($name)
    {
        $this->setPath($this->path . '/' . $name);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getCurrPath()
    {
        return $this->currPath;
    }

    /**
     * @return string
     */
    public function getParentPath()
    {
        return $this->parentPath;
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @return string
     */
    public function getCurrDir()
    {
        return $this->currDir;
    }

    /**
     * @return string
     */
    public function getParentDir()
    {
        return $this->parentDir;
    }

    public function getUrl()
    {
        return $this->url;
    }

    private function setPath($path)
    {
        $path = trim($path, '/\\');
        $this->path = $path;
        $pathParts = explode('/', $path);
        $this->currPath = array_pop($pathParts);
        $this->parentPath = join('/', $pathParts);

        $fullDir = trim($this->package->config('target_dir'), '/\\') . '/' . $path;
        $this->dir = trim(str_replace('\\', '/', base_path($fullDir)), '/');
        $dirParts = explode('/', $this->dir);
        $this->currDir = array_pop($dirParts);
        $this->parentDir = join('/', $dirParts);

        $this->url = (new UrlService($this->package))->folder($this->getPath());
    }
}