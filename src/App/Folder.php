<?php namespace Crip\Filesys\App;

use Crip\Core\Contracts\ICripObject;
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
    public $name = '';
    public $dir = '';
    public $type = 'dir';
    public $bytes = '';
    public $full_name = '';
    public $updated_at = '';
    public $url = '';
    public $thumb = '';

    public function __construct(FilesystemManager $manager = null, FileInfo $file = null)
    {
        /** @var Filesystem $fs */
        $fs = app(Filesystem::class);

        if ($file !== null && $manager !== null) {
            $this->name = $file->getName();
            $this->dir = $file->getDir();
            $this->full_name = $file->getDir() . '/' . $file->getName();
            $this->updated_at = $fs->lastModified($file->sysPath());
            $this->url = $manager->publicUrl($file);
            // $this->thumb = resolve from config url icon path
        }
    }
}