<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Helpers\FileSystem;

/**
 * Class BlobMetadata
 * @package Crip\Filesys\Services
 */
class BlobMetadata implements ICripObject
{
    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
    private $storage;

    private $isExistExecuted = false;
    private $exists = false;
    private $path = null;
    private $lastModified;
    private $name;
    private $dir;
    private $mimeType = 'dir';
    private $extension = null;
    private $size;
    private $type;

    /**
     * BlobMetadata initializer.
     * @param $path
     * @return BlobMetadata $this
     */
    public function init($path)
    {
        $this->storage = app()->make('filesystem');
        $this->path = $path;

        if ($this->exists()) {
            list($this->dir, $this->name) = FileSystem::splitNameFromPath($path);
            $this->lastModified = $this->storage->lastModified($path);

            $metadata = $this->storage->getMetaData($path);
            $this->path = $metadata['path'];

            $this->size = array_key_exists('size', $metadata) ?
                $metadata['size'] : 0;

            $this->type = $metadata['type'];

            if ($this->isFile()) {
                list($this->name, $this->extension) = $this->splitNameAndExtension($this->name);

                try {
                    $this->mimeType = $this->storage->mimeType($this->path);
                } catch (\Exception $ex) {
                    $this->mimeType = $this->guessMimeType();
                }
            }
        }

        return $this;
    }

    /**
     * Get debug info of current metadata file.
     * @return array
     */
    public function debugInfo()
    {
        return [
            'exists' => $this->exists(),
            'isFile' => $this->isFile(),
            'isImage' => $this->isImage(),
            'path' => $this->path,
            'lastModified' => $this->lastModified,
            'name' => $this->name,
            'dir' => $this->dir,
            'mimeType' => $this->mimeType,
            'extension' => $this->extension,
            'size' => $this->size,
            'type' => $this->type,
            'getFullName' => $this->getFullName()
        ];
    }

    /**
     * @return bool
     */
    public function exists()
    {
        if (!$this->isExistExecuted && $this->path !== null) {
            $this->exists = $this->storage->exists($this->path);
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
        $info = pathinfo($fullName);

        return [$info['filename'], $info['extension']];
    }

    /**
     * Try guess mime type from path
     * @return string
     */
    private function guessMimeType()
    {
        if ($this->isFile()) {
            $map = config('cripfilesys.mime.map');
            return isset($map[$this->extension]) ?
                $map[$this->extension] : 'text/plain';
        }

        return 'dir';
    }
}