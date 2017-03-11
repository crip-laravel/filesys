<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Support\PackageBase;

/**
 * Class BlobInfo
 * @package Crip\Filesys\Services
 */
class Blob implements ICripObject
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var PackageBase
     */
    private $package;

    /**
     * @var FolderInfo
     */
    public $folder;

    /**
     * @var FileInfo
     */
    public $file;

    /**
     * BlobInfo constructor.
     * @param PackageBase $package
     * @param string $path
     */
    public function __construct(PackageBase $package, $path = '')
    {
        $this->package = $package;
        $this->setPath($path);
    }

    public function setPath($path)
    {
        $this->path = $this->normalizePath($path);

        $folder = $this->path;
        $file = null;

        if (pathinfo($this->path, PATHINFO_EXTENSION)) {
            $folderArr = explode('/', $this->path);
            $file = array_pop($folderArr);
            $folder = join('/', $folderArr);
        }

        $this->setFolder($folder);
        $this->setFile($file);
    }

    public function setFolder($folder)
    {
        $confDir = base_path(trim($this->package->config('target_dir'), '/\\'));
        if (str_contains($folder, $this->normalizePath($confDir))) {
            $folder = str_replace($this->normalizePath($confDir), '', $folder);
        }

        $this->folder = new FolderInfo($this->package, $folder);
    }

    public function setFile($name, $extension = null)
    {
        if ($extension != null) {
            $name .= '.' . $extension;
        }

        $this->file = new FileInfo($this->package, $this->folder, $name);
    }

    public function systemPath()
    {
        if ($this->file->isDefined()) {
            return $this->file->getSystemPath();
        }

        return $this->folder->getDir();
    }

    public function getMime()
    {
        if (!$this->file->isDefined()) {
            return 'dir';
        }

        foreach ($this->package->config('mime.types') as $mime => $mimeValues) {
            $key = collect($mimeValues)->search(function ($mimeValue) {
                return preg_match($mimeValue, $this->file->getMimeType());
            });

            if ($key !== false) {
                return $mime;
            }
        }

        return 'file';
    }

    private function normalizePath($path)
    {
        $path = str_replace('\\', '/', $path);

        // make sure that user cant go up in directory '../..'
        while (str_contains($path, '..')) {
            $path = str_replace('..', '.', $path);
        }

        return $path;
    }
}