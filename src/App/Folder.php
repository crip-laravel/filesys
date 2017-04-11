<?php namespace Crip\Filesys\App;

use Crip\Core\Contracts\ICripObject;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class Folder
 * @package Crip\Filesys\App
 */
class Folder extends Blob implements ICripObject, Arrayable
{
    public function __construct(\Crip\Filesys\Services\Blob $blob)
    {
        parent::__construct($blob);
    }
}