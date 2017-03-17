<?php namespace Crip\Filesys\App;

use Crip\Core\Contracts\ICripObject;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class FileSystemObject
 * @package Crip\Filesys\App
 */
class FileSystemObject implements ICripObject, Arrayable
{
    public $name = '';
    public $full_name = '';
    public $type = 'dir';
    public $mediatype = 'dir';
    public $bytes = 0;
    public $thumb = '';
    public $updated_at = '';
    public $dir = '';
    public $url = '';
    /**
     * Default permission set for filesystem object
     *
     * @var array
     */
    public $perms = [
        'auth_can_read' => true,
        'auth_can_write' => true,
        'auth_can_delete' => true,
        'any_can_read' => true,
        'any_can_write' => false,
        'any_can_delete' => false,
    ];

    public function readPermsFromPath($path)
    {
        $this->perms = Perms::getFilePerms($path);
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