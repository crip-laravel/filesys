<?php namespace Crip\Filesys\App;

use Crip\Core\Contracts\ICripObject;
use Crip\Filesys\Services\FileInfo;
use Crip\Filesys\Services\FilesystemManager;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Filesystem\Filesystem;

/**
 * Class File
 * @package Crip\Filesys\App
 */
class File extends FileSystemObject implements ICripObject, Arrayable
{
    public $name = '';
    public $extension = '';
    public $mime = '';
    public $type = '';
    public $mimetype = '';
    public $bytes = '';
    public $updated_at = '';
    public $thumb = '';
    public $dir = '';
    public $full_name = '';
    public $url = '';
    public $size = [];
    public $thumbs = [];

    public function __construct(FilesystemManager $manager = null, FileInfo $file = null)
    {
        /** @var Filesystem $fs */
        $fs = app(Filesystem::class);

        if ($file !== null && $manager !== null) {
            $this->name = $file->getName();
            $this->extension = $file->getExt();
            $this->type = $fs->type($file->sysPath());
            $this->mimetype = $manager->fileMimeType($file);
            // $this->mime = resolve from config.mime.type
            $this->bytes = $fs->size($file->sysPath());
            $this->updated_at = $fs->lastModified($file->sysPath());
            // $this->thumb = resolve default thumb from a type or image for images
            $this->dir = $file->getDir();
            $this->full_name = $file->getName() . '.' . $file->getExt();
            $this->url = $manager->publicUrl($file);
        }
    }
}