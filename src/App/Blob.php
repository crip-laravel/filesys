<?php namespace Crip\Filesys\App;

use Crip\Core\Contracts\ICripObject;
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
    public $mediatype = 'dir';
    public $name = '';
    public $thumb = '';
    public $type = 'dir';
    public $updated_at = '';
    public $url = '';
    public $visibility = 'public';
    public $xs = '';

    public function __construct(\Crip\Filesys\Services\Blob $blob)
    {
        $this->bytes = $blob->getSize();
        $this->dir = $blob->getDir();
        $this->full_name = $blob->getFullName();
        $this->mediatype = $blob->getMediatype();
        $this->name = $blob->getName();
        $this->thumb = $blob->getThumbUrl();
        $this->type = $blob->getType();
        $this->updated_at = $blob->getLastModified();
        $this->url = $blob->getUrl();
        $this->visibility = $blob->getVisibility();
        $this->xs = $blob->getXsThumbUrl();
    }

    /**
     * Get the instance as an array.
     * @return array
     */
    public function toArray()
    {
        return (array)$this;
    }
}