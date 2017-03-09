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
     * @var Filesystem
     */
    private $fs;

    /**
     * FilesystemManager constructor.
     * @param PackageBase $package
     */
    public function __construct(PackageBase $package)
    {
        $this->package = $package;
        $this->fs = app(Filesystem::class);
    }

    /**
     * Parse path to file/folder
     * @param string $path
     * @param array $params
     * @return FileManager
     */
    public function parsePath($path = '', array $params = [])
    {
        $this->params = $params;

        return new FileManager($this->package, $path);
    }

    /**
     * Determine if a file or directory exists
     * @param FileManager $file
     * @return bool
     */
    public function exists(FileManager $file)
    {
        return $this->fs->exists($file->sysPath());
    }

    /**
     * Determines is presented path for image
     * @param FileManager $file
     * @return bool
     */
    public function isImage(FileManager $file)
    {
        return substr($this->fs->mimeType($file->sysPath()), 0, 5) === 'image';
    }

    /**
     * Get public url for a file
     * @param $file FileManager
     * @return string File public url
     */
    public function publicUrl(FileManager $file)
    {
        $filePathParts = explode(DIRECTORY_SEPARATOR, $file->sysPath());
        $filePublicPath = DIRECTORY_SEPARATOR . $file->dir . DIRECTORY_SEPARATOR . array_pop($filePathParts);
        $filePublicPath = str_replace(DIRECTORY_SEPARATOR, '/', $filePublicPath);

        return action('\\' . $this->package->config('actions.file') . '@show', '', false) . $filePublicPath;
    }

    /**
     * Upload file in to package configured folder
     * @param FileManager $file File info holder
     * @param UploadedFile $upload Uploading file
     * @return string Location where file were uploaded
     */
    public function upload(FileManager $file, UploadedFile $upload)
    {
        $this->mkdirIfNotExists($file);
        $ext = $upload->getClientOriginalExtension();
        $clientOriginalName = $upload->getClientOriginalName();
        $name = mb_substr($clientOriginalName, 0, mb_strlen($clientOriginalName) - mb_strlen($ext));
        $name = rtrim($name, '.');
        // To get full path, join dir and its name
        $file->dir .= DIRECTORY_SEPARATOR . $file->getName();
        $dir = $file->sysDir();
        $targetFileName = $this->getUniqueFileName($dir, $name, $ext);

        $upload->move($dir, $targetFileName . '.' . $ext);

        // Updating file info details after it is successfully uploaded to server
        $file->setExt($ext);
        $file->setName($targetFileName);

        return join(DIRECTORY_SEPARATOR, [$dir, $targetFileName . '.' . $ext]);
    }

    public function resizeImage(FileManager $file)
    {
        // TODO: resize image to fit all configurations
    }

    /**
     * Get the contents of a file
     * @param FileManager $file
     * @return string
     */
    public function fileContent(FileManager $file)
    {
        // TODO: Path should be to the thumb if required key in $this->params array exists
        return $this->fs->get($file->sysPath());
    }

    /**
     * Get file mime type
     * @param FileManager $file
     * @return false|string
     */
    public function fileMimeType(FileManager $file)
    {
        return $this->fs->mimeType($file->sysPath());
    }

    /**
     * Rename current file or folder
     * @param FileManager $file
     * @param $newName string
     * @return bool
     */
    public function rename(FileManager $file, $newName)
    {
        return $this->fs->move($file->sysPath(), $file->rename($newName)->sysPath());
    }

    /**
     * Delete file/folder from the system
     * @param FileManager $fileInfo
     * @return bool
     */
    public function delete(FileManager $fileInfo)
    {
        return $this->fs->delete($fileInfo->sysPath());
    }

    /**
     * Make dir in system if it does not exists
     * @param FileManager $file
     */
    private function mkdirIfNotExists(FileManager $file)
    {
        $this->fs->makeDirectory($file->sysDir(), 0777, true, true);
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
        $originalName = $name;
        $i = 0;

        do {
            $fullPath = $this->fullPath($sysPath, $name, $ext);
        } while ($this->fs->exists($fullPath) && $name = $originalName . '-' . ++$i);

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