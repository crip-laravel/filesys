<?php namespace Crip\Filesys\App;

use Crip\Core\Contracts\ICripObject;
use Crip\Filesys\Services\Blob;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Filesystem\Filesystem;

/**
 * Class File
 * @package Crip\Filesys\App
 */
class File extends FileSystemObject implements ICripObject, Arrayable
{
    public $extension = '';
    public $mime = '';
    public $mimetype = '';
    public $size = [];
    public $thumbs = [];

    public function __construct(Blob $blob = null)
    {
        /** @var Filesystem $fs */
        $fs = app(Filesystem::class);

        if ($blob !== null) {
            $this->name = $blob->file->getName();
            $this->extension = $blob->file->getExt();
            $this->type = $fs->getMetaData($blob->systemPath())['type'];
            $this->mimetype = $blob->file->getMimeType();
            $this->mime = $blob->getMime();
            $this->mediatype = $blob->getMediatype();
            $this->bytes = $fs->size($blob->systemPath());
            $this->updated_at = $fs->lastModified($blob->systemPath());
            $this->thumb = $blob->getThumb();
            $this->dir = $blob->folder->getPath();
            $this->full_name = $blob->file->getFullName();
            $this->url = $blob->file->getUrl();
            $this->size = $blob->file->getSize();
            $this->thumbs = $blob->file->getThumbs();
        }
    }
}