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
    public $path;

    /**
     * @var BlobMetadata
     */
    public $metadata;

    /**
     * @var PackageBase
     */
    private $package;

    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
    private $storage;

    private $thumbsDetails = null;

    /**
     * Blob constructor.
     * @param PackageBase $package
     * @param string $path
     */
    public function __construct(PackageBase $package, $path = '')
    {
        $this->package = $package;
        $this->path = Str::normalizePath($path);
        $this->storage = app()->make('filesystem');
    }

    /**
     * @param BlobMetadata $metadata
     * @return File|Folder
     * @throws \Exception
     */
    public function fullDetails($metadata = null)
    {
        $this->metadata = $metadata ?: new BlobMetadata($this->path);
        if (!$this->metadata->exists()) {
            throw new \Exception('File not found');
        }

        if ($this->metadata->isFile()) {
            $result = new File($this);
        } else {
            $result = new Folder($this);
        }

        return $result;
    }

    /**
     * Get blob type.
     * @return string
     */
    public function getType()
    {
        return $this->metadata->isFile() ? 'file' : 'dir';
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

        foreach ($this->package->config('mime.media') as $mediaType => $mimes) {
            if (in_array($mime, $mimes)) {
                return $mediaType;
            }
        }

        return 'dir';
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

        if (!$this->metadata->isFile()) {
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
     * Generates url to a file.
     * @param null $path
     * @return string
     */
    public function getUrl($path = null)
    {
        $path = $path ?: $this->path;
        // If file has public access enabled, we simply can return storage url
        // to file
        if ($this->metadata->getVisibility() === 'public') {
            try {
                return '/' . $this->storage->url($path);
            } catch (\Exception $ex) {
                // Some drivers does not support url method (like ftp), so we
                // simply continue and generate crip url to our controller
            }
        }

        $service = new UrlService($this->package);
        if ($this->metadata->isFile()) {
            return $service->file($path);
        }

        return $service->folder($path);
    }

    /**
     * Get blob mime.
     * @return int|string
     */
    public function getMime()
    {
        if ($this->metadata->isFile()) {
            $mimes = $this->package->config('mime.types');
            foreach ($mimes as $mime => $mimeValues) {
                $key = collect($mimeValues)->search(function ($mimeValue) {
                    return preg_match($mimeValue, $this->metadata->getMimeType());
                });

                if ($key !== false) {
                    return $mime;
                }
            }

            return 'file';
        }

        return 'dir';
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
     * Determines is the current blob an image.
     * @return bool
     */
    private function isImage()
    {
        if ($this->metadata->isFile() &&
            mb_strpos($this->metadata->getMimeType(), 'image/') === 0
        ) {
            return true;
        }

        return false;
    }

    /**
     * Set thumb sizes details for current file.
     */
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