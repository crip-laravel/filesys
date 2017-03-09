<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Support\PackageBase;

/**
 * Class FileManager
 * @package Crip\Filesys\Services
 */
class FileManager implements ICripObject
{
    /**
     * @var string
     */
    public $path = '';

    /**
     * @var string
     */
    public $dir = '';

    /**
     * @var PackageBase
     */
    private $package;

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
        // make sure we are using DIRECTORY_SEPARATOR in path variable
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

        // make sure that user cant go up in directory '../..'
        while (str_contains($path, '..')) {
            $path = str_replace('..', '.', $path);
        }

        $this->path = $path;
        $this->package = $package;
        $this->pathinfo = pathinfo($path);

        // in case of empty folder avoid dirname property read
        if ($this->pathinfo['basename'] !== '') {
            $this->dir = $this->pathinfo['dirname'];
            if ($this->isFile()) {
                $this->name = $this->pathinfo['filename'];
                $this->ext = $this->pathinfo['extension'];
            }
        }

        if (!$this->isFile()) {
            $explodedDir = explode(DIRECTORY_SEPARATOR, $this->path);
            $this->name = array_pop($explodedDir);
            $this->dir = join(DIRECTORY_SEPARATOR, $explodedDir);
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
     * @return string
     */
    public function sysDir()
    {
        return base_path($this->package->config('target_dir') . '/' . trim($this->dir, '/\/'));
    }

    /**
     * Get system path with file name
     * @return string
     */
    public function sysPath()
    {
        if ($this->isFile()) {
            return $this->sysDir() . DIRECTORY_SEPARATOR . $this->name . '.' . $this->ext;
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
            $explodedDir = explode(DIRECTORY_SEPARATOR, $this->dir);
            array_pop($explodedDir);
            $explodedDir[] = $newName;

            $this->dir = join(DIRECTORY_SEPARATOR, $explodedDir);
        }

        return $this;
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
}