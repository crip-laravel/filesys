<?php namespace Crip\Filesys\Services;

use Crip\Core\Helpers\Str;
use Crip\Core\Support\PackageBase;

/**
 * Class UrlService
 * @package Crip\Filesys\Services
 */
class UrlService
{
    /**
     * @var PackageBase
     */
    private $package;

    /**
     * UrlService constructor.
     * @param PackageBase $package
     */
    public function __construct(PackageBase $package)
    {
        $this->package = $package;
    }

    /**
     * Generate URL for a file.
     * @param $path
     * @return string
     */
    public function file($path)
    {
        return $this->make('file', $path);
    }

    /**
     * Generate URL for a folder.
     * @param $path
     * @return string
     */
    public function folder($path)
    {
        return $this->make('folder', $path);
    }

    private function make($for, $path)
    {
        $path = trim($path, '\\/');
        $ctrl = '\\' . $this->package->config('actions.' . $for) . '@show';

        if ($this->package->config('absolute_url', false)) {
            return action($ctrl, $path);
        }

        $relative = action($ctrl, '', false);
        $url = '/' . Str::normalizePath($relative . '/' . $path);

        return $url;
    }
}