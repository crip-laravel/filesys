<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Support\PackageBase;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

/**
 * Class FilesystemManager
 * @package Crip\Filesys\Services
 */
class FilesystemManager implements ICripObject
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $pathinfo;

    /**
     * @var string
     */
    private $dir = '';

    /**
     * @var array
     */
    private $params;
    /**
     * @var PackageBase
     */
    private $package;

    /**
     * FilesystemManager constructor.
     * @param PackageBase $package
     */
    public function __construct(PackageBase $package)
    {
        $this->package = $package;
    }

    /**
     * Parse path to file/folder
     * @param string $path
     * @param array $params
     */
    public function parsePath($path = '', array $params = [])
    {
        $this->path = $path;
        $this->params = $params;

        $this->pathinfo = pathinfo($this->path);

        // in case of empty folder avoid dirname property read
        if ($this->pathinfo['basename'] !== '') {
            $this->dir = $this->pathinfo['dirname'];
        }

        if (!$this->isFile()) {
            $this->dir = $this->path ?: '';
        }
    }

    /**
     * Determines is parsed path for the file
     * @return bool
     */
    public function isFile()
    {
        return array_key_exists('extension', $this->pathinfo);
    }

    /**
     * Determines is parsed path for the image with custom size
     * @return bool
     */
    public function isSizedImageRequest()
    {
        // TODO: add check on file mime type
        return array_key_exists('s', $this->params);
    }

    /**
     * Upload file in to package configured folder
     * @param UploadedFile $file
     * @return UploadedFile
     */
    public function upload(UploadedFile $file)
    {
        $sysPath = $this->createPathIfNotExists($this->dir);
        $ext = $file->getClientOriginalExtension();
        $nameLen = mb_strlen($file->getClientOriginalName()) - mb_strlen($ext);
        $name = trim(substr($file->getClientOriginalName(), 0, $nameLen), '.');
        $targetFileName = $this->getUniqueFileName($sysPath, $name, $ext);

        // dd(compact('sysPath', 'ext', 'name', 'targetFileName'));

        $file->move($sysPath, $targetFileName . '.' . $ext);

        return $file;
    }

    private function createPathIfNotExists($dir)
    {
        $sysPath = base_path($this->package->config('target_dir') . '/' . trim($dir, '/\/'));
        app('files')->makeDirectory($sysPath, 0777, true, true);

        return $sysPath;
    }

    private function getUniqueFileName($sysPath, $name, $ext = null)
    {
        $filesystem = app(Filesystem::class);
        $originalName = $name;
        $i = 0;

        do {
            $fullPath = $this->fullPath($sysPath, $name, $ext);
        } while ($filesystem->exists($fullPath) && $name = $originalName . '-' . ++$i);

        return $name;
    }

    private function fullPath($sysPath, $name, $ext = null)
    {
        return $sysPath . '/' . $name . ($ext ? '.' . $ext : '');
    }
}