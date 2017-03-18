<?php namespace Crip\Filesys\App;

use Crip\Core\Contracts\ICripObject;
use Crip\Filesys\Services\Blob;
use Crip\Filesys\Services\FileInfo;
use Crip\Filesys\Services\FilesystemManager;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Filesystem\Filesystem;

/**
 * Class Folder
 * @package Crip\Filesys\App
 */
class Folder extends FileSystemObject implements ICripObject, Arrayable
{
    public function __construct(Blob $blob)
    {
        /** @var Filesystem $fs */
        $fs = app(Filesystem::class);

        if ($blob !== null) {
            $this->name = $blob->folder->getCurrPath();
            $this->dir = $blob->folder->getParentPath();
            $this->full_name = $blob->folder->getPath();
            $this->updated_at = $fs->lastModified($blob->systemPath());
            $this->url = $blob->folder->getUrl();
            $this->thumb = $blob->getThumb();
            $this->bytes = $blob->getBytes();
        }
    }
}