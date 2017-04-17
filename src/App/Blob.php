<?php namespace Crip\Filesys\App;

use Crip\Core\Contracts\ICripObject;
use Crip\Filesys\Services\Blob as ServiceBlob;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Blob
 * @package Crip\Filesys\App
 */
class Blob implements ICripObject, Arrayable
{
    public $bytes = 0;
    public $dir = '';
    public $full_name = '';
    public $mediaType = 'dir';
    public $name = '';
    public $thumb = '';
    public $type = 'dir';
    public $updated_at = '';
    public $url = '';
    public $xs = '';
    public $path = '';

    public function __construct(ServiceBlob $blob = null)
    {
        if ($blob == null) return;

        $this->init($blob);
    }

    /**
     * Get the instance as an array.
     * @return array
     */
    public function toArray()
    {
        return (array)$this;
    }

    /**
     * Initialize Blob properties from service instance.
     * @param ServiceBlob $blob
     */
    public function init(ServiceBlob $blob)
    {
        $this->bytes = $blob->metadata->getSize();
        $this->dir = $blob->metadata->getDir();
        $this->full_name = $blob->metadata->getFullName();
        $this->mediaType = $blob->getMediaType();
        $this->name = $blob->metadata->getName();
        $this->thumb = $blob->getThumbUrl();
        $this->type = $blob->getType();
        $this->updated_at = $blob->metadata->getLastModified();
        $this->url = $blob->getUrl();
        $this->xs = $blob->getXsThumbUrl();
        $this->path = $blob->metadata->getPath();
    }
}