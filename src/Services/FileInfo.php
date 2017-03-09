<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Support\PackageBase;
use Illuminate\Filesystem\Filesystem;

/**
 * Class FileManager
 * @package Crip\Filesys\Services
 */
class FileInfo implements ICripObject
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $ext;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var FolderInfo
     */
    private $folder;

    /**
     * @var false|string
     */
    private $mimeType;

    /**
     * @var string
     */
    private $url;

    /**
     * @var PackageBase
     */
    private $package;

    /**
     * FileInfo constructor.
     * @param PackageBase $package
     * @param FolderInfo $folder
     * @param $file
     */
    public function __construct(PackageBase $package, FolderInfo $folder, $file)
    {
        $this->package = $package;
        $this->name = pathinfo($file, PATHINFO_FILENAME);
        $this->folder = $folder;

        if ($file) {
            /** @var Filesystem $fs */
            $fs = app(Filesystem::class);
            $this->fullName = $file;
            $this->ext = pathinfo($file, PATHINFO_EXTENSION);
            $this->mimeType = $fs->mimeType($this->getSystemPath());
            $this->updateUrl();
        }
    }

    /**
     * Determines is file info details presented.
     * @return bool
     */
    public function isDefined()
    {
        return (bool)$this->ext;
    }

    /**
     * Update current file name
     * @param $newName
     * @return array [name, extension]
     */
    public function setName($newName)
    {
        $this->name = $newName;
        $this->fullName = $newName . '.' . $this->ext;
        $this->updateUrl();

        return [$newName, $this->ext];
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->folder->getPath() . '/' . $this->fullName;
    }

    /**
     * @return string
     */
    public function getSystemPath()
    {
        return $this->folder->getDir() . '/' . $this->fullName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return false|string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    private function updateUrl()
    {
        $ctrl = '\\' . $this->package->config('actions.file') . '@show';
        $this->url = action($ctrl, '', false) . '/' . $this->getPath();
        $this->url = str_replace('//', '/', $this->url);
    }
}