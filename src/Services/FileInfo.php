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
     * @var array
     */
    private $size = [];

    /**
     * @var array
     */
    private $thumbs = [];

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
            $this->fullName = $file;
            $this->ext = pathinfo($file, PATHINFO_EXTENSION);
            $this->update();
            if (substr($this->mimeType, 0, 5) === 'image') {
                $this->size = getimagesize($this->getSystemPath());
            }
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
        $this->update();

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

    /**
     * @return array
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return array
     */
    public function getThumbs()
    {
        return $this->thumbs;
    }

    /**
     * Update info of current file
     */
    public function update()
    {
        /** @var Filesystem $fs */
        $fs = app(Filesystem::class);
        $url = new UrlService($this->package);

        $this->url = $url->file($this->getPath());
        $this->thumbs = (new ThumbService($this->package))->details($this->getSystemPath());
        $this->mimeType = $fs->mimeType($this->getSystemPath());
        $this->fullName = $this->name . '.' . $this->ext;
    }
}