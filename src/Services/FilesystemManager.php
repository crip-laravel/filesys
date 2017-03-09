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
     * @return string
     */
    public function parsePath($path = '', array $params = [])
    {
        // make sure we are using DIRECTORY_SEPARATOR in path variable
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        $path = str_replace('/', DIRECTORY_SEPARATOR, $path);

        // make sure that user cant go up in directory '../..'
        while (str_contains($path, '..')) {
            $path = str_replace('..', '.', $path);
        }

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

        return $this->dir;
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
     * Determines is presented path for image
     * @param $path string File system full path
     * @return bool
     */
    public function isImage($path)
    {
        return substr(app(Filesystem::class)->mimeType($path), 0, 5) === 'image';
    }

    /**
     * Get public url for a file
     * @param $path string File system full path to the file
     * @return string File public url
     */
    public function publicUrl($path)
    {
        $filePathParts = explode(DIRECTORY_SEPARATOR, $path);
        $filePublicPath = DIRECTORY_SEPARATOR . $this->dir . DIRECTORY_SEPARATOR . array_pop($filePathParts);
        $filePublicPath = str_replace(DIRECTORY_SEPARATOR, '/', $filePublicPath);

        return action('\\' . $this->package->config('actions.file') . '@show', '', false) . $filePublicPath;
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
        $sysPath = $this->mkdirIfNotExists($this->dir);
        $ext = $file->getClientOriginalExtension();
        $nameLen = mb_strlen($file->getClientOriginalName()) - mb_strlen($ext);
        $name = trim(substr($file->getClientOriginalName(), 0, $nameLen), '.');
        $targetFileName = $this->getUniqueFileName($sysPath, $name, $ext) . '.' . $ext;

        // dd(compact('sysPath', 'ext', 'name', 'targetFileName'));

        $file->move($sysPath, $targetFileName);

        return join(DIRECTORY_SEPARATOR, [$sysPath, $targetFileName]);
    }

    public function resizeImage($file)
    {
        // TODO: resize image to fit all configurations
    }

    /**
     * Make directory if it does not exists and get system path as a response
     * @param $dir string Relative path to the dir
     * @return string System full path
     */
    private function mkdirIfNotExists($dir)
    {
        $sysPath = base_path($this->package->config('target_dir') . '/' . trim($dir, '/\/'));
        app('files')->makeDirectory($sysPath, 0777, true, true);

        return $sysPath;
    }

    /**
     * Get unique name for a file/folder in system path
     * @param $sysPath string System full path
     * @param $name string File/Folder name
     * @param null $ext File extension
     * @return string Unique name
     */
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

    /**
     * @param $sysPath string System full path
     * @param $name string File/Folder name
     * @param null $ext string File extension
     * @return string Joined path
     */
    private function fullPath($sysPath, $name, $ext = null)
    {
        return $sysPath . '/' . $name . ($ext ? '.' . $ext : '');
    }
}