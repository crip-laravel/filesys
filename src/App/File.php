<?php namespace Crip\Filesys\App;

use Crip\Core\Contracts\ICripObject;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class File
 * @package Crip\Filesys\App
 */
class File extends Blob implements ICripObject, Arrayable
{
    public $extension = '';
    public $mime = '';
    public $mimetype = '';
    public $thumbs = [];

    public function __construct(\Crip\Filesys\Services\Blob $blob)
    {
        parent::__construct($blob);

        $this->extension = $blob->metadata->getExtension();
        $this->mime = $blob->getMime();
        $this->mimetype = $blob->metadata->getMimeType();
        $this->thumbs = $blob->getThumbsDetails();
    }
}