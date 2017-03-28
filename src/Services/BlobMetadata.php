<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Helpers\FileSystem;

/**
 * Class BlobMetadata
 * @package Crip\Filesys\Services
 */
class BlobMetadata implements ICripObject
{
    private $isExistExecuted = false;
    private $exists = false;
    private $path = null;
    private $lastModified;
    private $name;
    private $dir;
    private $mimeType = 'dir';
    private $extension = null;
    private $visibility;
    private $size;
    private $type;

    /**
     * @param string $path
     * @return $this
     */
    public function init($path = '')
    {
        $this->path = $path;
        if ($this->exists()) {
            list($this->dir, $this->name) = FileSystem::splitNameFromPath($path);
            $this->lastModified = \Storage::lastModified($path);

            $metadata = \Storage::getMetaData($path);
            $this->path = $metadata['path'];
            $this->visibility = $metadata['visibility'];
            $this->size = $metadata['size'];
            $this->type = $metadata['type'];

            if ($this->isFile()) {
                list($this->name, $this->extension) = $this->splitNameAndExtension($this->name);

                $this->mimeType = \Storage::mimeType($this->path);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        if (!$this->isExistExecuted && $this->path !== null) {
            $this->exists = \Storage::exists($this->path);
            $this->isExistExecuted = true;
        }

        return $this->exists;
    }

    /**
     * @return bool
     */
    public function isFile()
    {
        return $this->type === 'file';
    }

    /**
     * @return bool
     */
    public function isImage()
    {
        if ($this->exists()) {
            return substr($this->mimeType, 0, 5) === 'image';
        }

        return false;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getLastModified()
    {
        return $this->lastModified;
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
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return null|string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return string
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        if ($this->isFile()) {
            return $this->name . '.' . $this->extension;
        }

        return $this->name;
    }

    /**
     * Split name and extension.
     * @param $fullName
     * @return array [name, extension]
     */
    private function splitNameAndExtension($fullName)
    {
        $parts = explode('.', $fullName);
        $ext = array_pop($parts);
        $name = join('.', $parts);

        return [$name, $ext];
    }
}