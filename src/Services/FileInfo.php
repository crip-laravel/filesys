<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Support\PackageBase;

/**
 * Class FileManager
 * @package Crip\Filesys\Services
 */
class FileInfo implements ICripObject
{
    /**
     * @var string
     */
    public $path = '';

    /**
     * @var PackageBase
     */
    private $package;

    /**
     * @var string
     */
    private $dir = '';

    /**
     * @var string
     */
    private $name = 'NULL';

    /**
     * @var string
     */
    private $ext = 'NULL';

    /**
     * @var array
     */
    private $pathinfo = [];

    /**
     * FileManager constructor.
     * @param PackageBase $package
     * @param string $path
     */
    public function __construct(PackageBase $package, $path = '')
    {
        $path = $this->normalizePath($path);
        $this->path = $path;
        $this->package = $package;
        $this->pathinfo = pathinfo($path);

        // in case of empty folder avoid dirname property read
        if ($this->pathinfo['basename'] !== '') {
            $this->setDir($this->pathinfo['dirname']);
            if ($this->isFile()) {
                $this->name = $this->pathinfo['filename'];
                $this->ext = $this->pathinfo['extension'];
            }
        }

        if (!$this->isFile()) {
            $explodedDir = explode('/', $this->path);
            $this->name = array_pop($explodedDir);
            $this->setDir(join('/', $explodedDir));
        }
    }

    /**
     * Determines is parsed path for the file
     * @return bool
     */
    public function isFile()
    {
        return array_key_exists('extension', $this->pathinfo) || $this->ext !== 'NULL';
    }

    /**
     * Get system dir
     * @param bool $includeName
     * @return string
     */
    public function sysDir($includeName = false)
    {
        $result = base_path(trim($this->package->config('target_dir'), '/\\') . '/' . $this->getDir());

        if ($includeName && $this->name !== 'NULL') {
            $result .= '/' . $this->name;
        }

        return $this->normalizePath($result);
    }

    /**
     * Get system path with file name
     * @return string
     */
    public function sysPath()
    {
        if ($this->isFile()) {
            return $this->sysDir() . '/' . $this->name . '.' . $this->ext;
        }

        return $this->sysDir();
    }

    /**
     * Set new name for current file/folder info details
     * @param $newName string New file name
     * @return $this
     */
    public function rename($newName)
    {
        if ($this->isFile()) {
            $this->name = $newName;
        } else {
            // replace last item in dir with new name
            $explodedDir = explode('/', $this->dir);
            array_pop($explodedDir);
            $explodedDir[] = $newName;

            $this->dir = join('/', $explodedDir);
        }

        return $this;
    }

    public function appendNameToDir()
    {
        $this->dir = $this->getDir() . '/' . $this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @param string $ext
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * @param $dir string
     */
    private function setDir($dir)
    {
        $dir = $this->normalizePath($dir);
        $this->dir = $dir;
        $configDir = $this->normalizePath(base_path($this->package->config('target_dir')));

        if (str_contains($dir, $configDir)) {
            $this->dir = str_replace($configDir, '', $dir);
        }

        $this->dir = trim($this->dir, '/\\');
    }

    /**
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }


    private function normalizePath($path)
    {
        // make sure we are using '/' in path variable
        $path = str_replace('\\', '/', $path);

        // make sure that user cant go up in directory '../..'
        while (str_contains($path, '..')) {
            $path = str_replace('..', '.', $path);
        }

        return $path;
    }
}