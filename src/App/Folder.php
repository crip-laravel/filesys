<?php namespace Crip\Filesys\App;

use Crip\Core\Contracts\ICripObject;
use Crip\Filesys\Services\Blob as ServiceBlob;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Folder
 * @package Crip\Filesys\App
 */
class Folder extends Blob implements ICripObject, Arrayable
{
    /**
     * Folder constructor.
     * @param ServiceBlob|null $blob
     */
    public function __construct(ServiceBlob $blob = null)
    {
        parent::__construct($blob);
    }
}