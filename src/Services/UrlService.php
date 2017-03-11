<?php namespace Crip\Filesys\Services;

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
     * @param bool $pathIsSystem
     * @return string
     */
    public function file($path, $pathIsSystem = false)
    {
        return $this->make('file', $path, $pathIsSystem);
    }

    /**
     * Generate URL for a folder.
     * @param $path
     * @param bool $pathIsSystem
     * @return string
     */
    public function folder($path, $pathIsSystem = false)
    {
        return $this->make('folder', $path, $pathIsSystem);
    }

    private function make($for, $path, $pathIsSystem)
    {
        if ($pathIsSystem) {
            return $this->makeFromSysPath($for, $path);
        }

        $path = trim($path, '\\/');
        $ctrl = '\\' . $this->package->config('actions.' . $for) . '@show';
        $url = action($ctrl, '', false) . '/' . $path;
        $url = str_replace('\\', '/', $url);

        return $url;
    }

    private function makeFromSysPath($for, $path)
    {
        $postfix = '';
        $baseDir = trim(str_replace('\\', '/', base_path($this->package->config('target_dir'))), '/');
        $relativePath = trim(str_replace($baseDir, '', $path), '/');
        $isThumb = substr_count($relativePath, '--thumbs--');

        if ($isThumb) {
            $clean = trim(str_replace('--thumbs--', '', $relativePath), '/');
            $parts = explode('/', $clean);
            $size = array_shift($parts);
            $relativePath = join('/', $parts);
            $postfix = '?s=' . $size;
        }

        return $this->make($for, $relativePath . $postfix, false);
    }
}