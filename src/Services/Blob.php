<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Helpers\Str;
use Crip\Core\Support\PackageBase;

/**
 * Class BlobInfo
 * @package Crip\Filesys\Services
 */
class Blob implements ICripObject
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var PackageBase
     */
    private $package;

    /**
     * @var FolderInfo
     */
    public $folder;

    /**
     * @var FileInfo
     */
    public $file;

    /**
     * BlobInfo constructor.
     * @param PackageBase $package
     * @param string $path
     */
    public function __construct(PackageBase $package, $path = '')
    {
        $this->package = $package;
        $this->setPath($path);
    }

    public function setPath($path)
    {
        $this->path = Str::normalizePath($path);

        $folder = $this->path;
        $file = null;

        if (pathinfo($this->path, PATHINFO_EXTENSION)) {
            $folderArr = explode('/', $this->path);
            $file = array_pop($folderArr);
            $folder = join('/', $folderArr);
        }

        $this->setFolder($folder);
        $this->setFile($file);
    }

    public function setFolder($folder)
    {
        $folder = Str::normalizePath($folder);
        $confDir = base_path(trim($this->package->config('target_dir'), '/\\'));
        if (str_contains($folder, Str::normalizePath($confDir))) {
            $folder = str_replace(Str::normalizePath($confDir), '', $folder);
        }

        $this->folder = new FolderInfo($this->package, $folder);
    }

    public function setFile($name, $extension = null)
    {
        if ($extension != null) {
            $name .= '.' . $extension;
        }

        $this->file = new FileInfo($this->package, $this->folder, $name);
    }

    public function systemPath()
    {
        if ($this->file->isDefined()) {
            return $this->file->getSystemPath();
        }

        return $this->folder->getDir();
    }

    public function getMime()
    {
        if (!$this->file->isDefined()) {
            return 'dir';
        }

        foreach ($this->package->config('mime.types') as $mime => $mimeValues) {
            $key = collect($mimeValues)->search(function ($mimeValue) {
                return preg_match($mimeValue, $this->file->getMimeType());
            });

            if ($key !== false) {
                return $mime;
            }
        }

        return 'file';
    }

    public function getThumb()
    {
        $url = $this->package->config('icons.url');
        $icons = $this->package->config('icons.files');

        if (!$this->file->isDefined()) {
            return $url . $icons['dir'];
        }

        if ($this->file->isImage()) {
            $thumbs = $this->file->getThumbs();

            if (!array_key_exists('thumb', $thumbs)) {
                return $url . $icons['img'];
            }

            return $thumbs['thumb']['url'];
        }

        $mime = $this->getMime();
        if (!array_key_exists($mime, $icons)) {
            $message = sprintf('Configuration file is missing for `%s` file type in `icons.files` array', $mime);
            throw new \Exception($message);
        }

        return $url . $icons[$mime];
    }

    /**
     * Get media type of current mime
     * @return string
     */
    public function getMediatype()
    {
        $mime = $this->getMime();
        foreach ($this->package->config('mime.media') as $mediatype => $mimes) {
            if (in_array($mime, $mimes)) {
                return $mediatype;
            }
        }

        return 'dir';
    }
}