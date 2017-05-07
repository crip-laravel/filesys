<?php namespace Crip\Filesys\App;

use Crip\Core\Contracts\ICripObject;
use Crip\Filesys\Services\Blob as ServiceBlob;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class File
 * @package Crip\Filesys\App
 */
class File extends Blob implements ICripObject, Arrayable
{
    public $extension = '';
    public $mime = '';
    public $mimeType = '';
    public $thumbs = [];

    /**
     * File constructor.
     * @param ServiceBlob|null $blob
     */
    public function __construct(ServiceBlob $blob = null)
    {
        parent::__construct($blob);

        if ($blob == null) return;

        $this->init($blob);
    }

    /**
     * Initialize File properties from service instance.
     * @param ServiceBlob $blob
     */
    public function init(ServiceBlob $blob)
    {
        parent::init($blob);

        $this->extension = $blob->metadata->getExtension();
        $this->mime = $blob->getMime();
        $this->mimeType = $blob->metadata->getMimeType();
        $this->thumbs = $blob->getThumbsDetails();
    }
}