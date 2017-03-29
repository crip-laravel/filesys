<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Helpers\Slug;
use Crip\Core\Helpers\Str;
use Crip\Core\Support\PackageBase;
use Crip\Filesys\App\File;
use Crip\Filesys\App\Folder;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

/**
 * Class FilesystemManager
 * @package Crip\Filesys\Services
 */
class FilesystemManager implements ICripObject
{
    /**
     * Delete file/folder from the system
     * @param Blob $blob
     * @return bool
     */
    public function delete(Blob $blob)
    {
        $isFile = $blob->file->isDefined();

        $this->thumb->delete($blob->systemPath(), $isFile);

        if ($isFile) {
            return $this->fs->delete($blob->systemPath());
        }

        return $this->fs->deleteDirectory($blob->systemPath());
    }

    public function getTree($recursive = true)
    {
        /** @var Filesystem $fs */
        $fs = app(Filesystem::class);
        $dirs = [];
        $dir = Str::normalizePath($this->package->config('target_dir'));

        $this->readDirs($fs, $dir, $dirs, $dir, $dir);

        return $dirs;
    }

    private function readDirs(Filesystem $fs, $dir, &$result, $base, $root)
    {
        $dirs = $fs->directories($dir);
        collect($dirs)->each(function ($dir) use ($fs, &$result, $base, $root) {
            if (!str_contains($dir, '--thumbs--')) {
                $dir = Str::normalizePath($dir);
                $path = Str::normalizePath(str_replace($root, '', $dir));
                $name = Str::normalizePath(str_replace($base, '', $dir));
                $index = count($result);
                $result[$index] = [
                    'path' => $path,
                    'name' => $name,
                    'children' => []
                ];
                $this->readDirs($fs, $dir, $result[$index]['children'], $base . '/' . $name, $root);
            }
        });
    }
}