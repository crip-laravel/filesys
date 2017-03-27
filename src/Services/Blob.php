<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Helpers\FileSystem;
use Crip\Core\Helpers\Str;
use Crip\Core\Support\PackageBase;
use Crip\Filesys\App\File;
use Crip\Filesys\App\Folder;

/**
 * Class Blob
 * @package Crip\Filesys\Services
 */
class Blob implements ICripObject
{
    private $isFile = false;
    private $mime = 'dir';
    private $thumbsDetails = null;

    /**
     * @var string
     */

    public $path;
    /**
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */

    /**
     * @var PackageBase
     */
    private $package;

    private $fs;
    private $visibility;
    private $size;
    private $mimeType;
    private $type;
    private $lastModified;
    private $dir;
    private $name;
    private $extension;

    /**
     * Blob constructor.
     * @param PackageBase $package
     * @param string $path
     */
    public function __construct(PackageBase $package, $path = '')
    {
        $this->fs = app(\Illuminate\Contracts\Filesystem\Filesystem::class);
        $this->package = $package;
        $this->path = Str::normalizePath($path);
    }

    /**
     * @return File|Folder
     * @throws \Exception
     */
    public function fullDetails()
    {
        if (!\Storage::exists($this->path)) {
            throw new \Exception('File not found');
        }

        $metadata = \Storage::getMetaData($this->path);

        $this->path = $metadata['path'];
        $this->visibility = $metadata['visibility'];
        $this->size = $metadata['size'];
        $this->mimeType = 'folder';
        $this->lastModified = \Storage::lastModified($this->path);

        list($this->dir, $this->name) = FileSystem::splitNameFromPath($this->path);

        if ($metadata['type'] === 'file') {
            $this->isFile = true;
            $this->type = 'file';
            $this->mimeType = \Storage::mimeType($this->path);

            $parts = explode('.', $this->name);
            $this->extension = array_pop($parts);
            $this->name = join('.', $parts);

            $result = new File($this);
        } else {
            $this->type = 'dir';
            $result = new Folder($this);
        }

        return $result;
    }

    /**
     * get blob name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get blob full name.
     * @return string
     */
    public function getFullName()
    {
        if ($this->isFile) {
            return $this->name . '.' . $this->extension;
        }

        return $this->name;
    }

    /**
     * Get blob type.
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get blob media type.
     * @return string
     */
    public function getMediatype()
    {
        $mime = $this->getMime();

        if ($mime == 'file') {
            return $mime;
        }

        foreach ($this->package->config('mime.media') as $mediatype => $mimes) {
            if (in_array($mime, $mimes)) {
                return $mediatype;
            }
        }

        return 'dir';
    }

    /**
     * Get blob size in bytes.
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get 'thumb' size thumbnail url.
     * @param string $size
     * @return string
     * @throws \Exception
     */
    public function getThumbUrl($size = 'thumb')
    {
        $url = $this->package->config('icons.url');
        $icons = $this->package->config('icons.files');

        if (!$this->isFile) {
            return $url . $icons['dir'];
        }

        if ($this->isImage()) {
            $thumbs = $this->getThumbsDetails();

            if (!array_key_exists($size, $thumbs)) {
                return $url . $icons['img'];
            }

            return $thumbs[$size]['url'];
        }

        $mime = $this->getMime();
        if (!array_key_exists($mime, $icons)) {
            $message = sprintf('Configuration file is missing for `%s` file type in `icons.files` array', $mime);
            throw new \Exception($message);
        }

        return $url . $icons[$mime];
    }

    /**
     * Get 'xs' size thumbnail url.
     * @return string
     */
    public function getXsThumbUrl()
    {
        return $this->getThumbUrl('xs');
    }

    /**
     * Get last modified timestamp.
     * @return mixed
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * Get blob dir.
     * @return string
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * Generates url to a file.
     * @param null $path
     * @return string
     */
    public function getUrl($path = null)
    {
        $path = $path ?: $this->path;
        // If file has public access enabled, we simply can return storage url
        // to file
        if ($this->visibility === 'public') {
            try {
                return \Storage::url($path);
            } catch (\Exception $ex) {
                // Some drivers does not support url method (like ftp), so we
                // simply continue and generate crip url to our controller
            }
        }

        $service = new UrlService($this->package);
        if ($this->isFile) {
            return $service->file($path);
        }

        return $service->folder($path);
    }

    /**
     * Get file extension.
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Get blob mime.
     * @return int|string
     */
    public function getMime()
    {
        if ($this->isFile) {
            $mimes = $this->package->config('mime.types');
            foreach ($mimes as $mime => $mimeValues) {
                $key = collect($mimeValues)->search(function ($mimeValue) {
                    return preg_match($mimeValue, $this->mimeType);
                });

                if ($key !== false) {
                    return $mime;
                }
            }

            return 'file';
        }

        return $this->mime;
    }

    /**
     * Get blob mime type.
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Get thumbs details.
     * @return array
     */
    public function getThumbsDetails()
    {
        if ($this->thumbsDetails === null) {
            $this->setThumbsDetails();
        }

        return $this->thumbsDetails;
    }

    /**
     * Get blob visibility.
     * @return string Gets 'public' or 'private'
     */
    public function getVisibility()
    {
        return $this->visibility;
    }

    /**
     * Determines is the current blob an image.
     * @return bool
     */
    private function isImage()
    {
        if (mb_strpos($this->getMimeType(), 'image/') === 0) {
            return true;
        }

        return false;
    }

    private function setThumbsDetails()
    {
        $this->thumbsDetails = [];
        if ($this->isImage()) {
            $service = new ThumbService($this->package);
            collect($service->getSizes())->each(function ($size, $key) {
                $this->thumbsDetails[$key] = [
                    'size' => $key,
                    'width' => $size[0],
                    'height' => $size[1],
                    'url' => $this->getUrl($key . '/' . $this->path)
                ];
            });
        }
    }
}