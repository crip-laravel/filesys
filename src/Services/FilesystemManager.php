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
     * Get the contents of a file.
     * @param Blob $blob
     * @return string
     */
    public function fileContent(Blob $blob)
    {
        $path = $blob->systemPath();

        if (array_key_exists('s', $this->params)) {
            $path = join('/', $this->thumb->getThumbPath($path, $this->params['s']));
        }

        return $this->fs->get($path);
    }

    /**
     * List folder content.
     * @param Blob $blob
     * @return array
     */
    public function folderContent(Blob $blob)
    {
        $result = [];
        $list = collect($this->fs->files($blob->systemPath()));
        $list = $list->union($this->fs->directories($blob->systemPath()));

        $list->each(function ($glob) use (&$result) {
            if (str_contains($glob, '--thumbs--')) {
                // skip thumbs dir and do not show it for users
                return;
            }

            $blobInfo = new Blob($this->package, $glob);
            $result[] = $blobInfo->file->isDefined() ?
                new File($blobInfo) :
                new Folder($blobInfo);
        });

        return $result;
    }

    /**
     * Rename current file or folder
     * @param Blob $blob
     * @param $newName string
     * @return Blob
     *
     * @throws \Exception
     */
    public function rename(Blob $blob, $newName)
    {
        $newName = Slug::make($newName);
        $curr = $blob->systemPath();

        if ($blob->file->isDefined()) {
            list($name, $ext, $oldName) = $blob->file->setName($newName);
            $targetName = $this->getUniqueFileName($blob->folder->getDir(), $name, $ext);

            if ($name !== $targetName) {
                $blob->file->setName($targetName);
            }

            if ($blob->file->isImage()) {
                $this->thumb->rename($curr, $targetName . '.' . $ext);
            }
        } else {
            $newName = $this->getUniqueFileName($blob->folder->getParentDir(), $newName);
            $blob->folder->setName($newName);
            $this->thumb->rename($curr, $newName);
        }

        if (!$this->fs->move($curr, $blob->systemPath())) {
            throw new \Exception('Could not rename.');
        }

        if ($blob->file->isDefined()) {
            $blob->file->update();
        }

        return $blob;
    }

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

    /**
     * Determines is the file safe for upload.
     * @param string $ext
     * @param string $mime
     * @return bool
     */
    public function isSafe($ext, $mime)
    {
        $unsafeExtensions = $this->package->config('block.extensions');
        $unsafeMimes = $this->package->config('block.mimetypes');
        $mimeSearch = function ($mimeValue) use ($mime) {
            return preg_match($mimeValue, $mime);
        };

        if (in_array($ext, $unsafeExtensions)) {
            return false;
        }

        if (collect($unsafeMimes)->search($mimeSearch)) {
            return false;
        }

        return true;
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