<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Support\PackageBase;
use Crip\Filesys\App\File;
use Crip\Filesys\App\Folder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

/**
 * Class FilesystemManager
 * @package Crip\Filesys\Services
 */
class FilesystemManager implements ICripObject
{
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
     * @return FileInfo
     */
    public function parsePath($path = '', array $params = [])
    {
        $this->params = $params;

        return new FileInfo($this->package, $path);
    }

    /**
     * Determine if a file or directory exists
     * @param FileInfo $file
     * @return bool
     */
    public function exists(FileInfo $file)
    {
        return $this->fs->exists($file->sysPath());
    }

    /**
     * Determines is presented path for image
     * @param FileInfo $file
     * @return bool
     */
    public function isImage(FileInfo $file)
    {
        return substr($this->fs->mimeType($file->sysPath()), 0, 5) === 'image';
    }

    /**
     * Get public url for a file
     * @param $file FileInfo
     * @return string File public url
     */
    public function publicUrl(FileInfo $file)
    {
        $filePathParts = explode('/', $file->sysPath());
        $filePublicPath = '/' . $file->getDir() . '/' . array_pop($filePathParts);
        $filePublicPath = str_replace('\\', '/', $filePublicPath);

        return action('\\' . $this->package->config('actions.file') . '@show', '', false) . $filePublicPath;
    }

    /**
     * Upload file in to package configured folder
     * @param FileInfo $file File info holder
     * @param UploadedFile $upload Uploading file
     * @return string Location where file were uploaded
     */
    public function upload(FileInfo $file, UploadedFile $upload)
    {
        $this->mkdirIfNotExists($file);
        $ext = $upload->getClientOriginalExtension();
        $clientOriginalName = $upload->getClientOriginalName();
        $name = mb_substr($clientOriginalName, 0, mb_strlen($clientOriginalName) - mb_strlen($ext));
        $name = rtrim($name, '.');
        // To get full path, join dir and its name
        $file->appendNameToDir();
        $dir = $file->sysDir();
        $targetFileName = $this->getUniqueFileName($dir, $name, $ext);

        $upload->move($dir, $targetFileName . '.' . $ext);

        // Updating file info details after it is successfully uploaded to server
        $file->setExt($ext);
        $file->setName($targetFileName);

        return join('/', [$dir, $targetFileName . '.' . $ext]);
    }

    public function resizeImage(FileInfo $file)
    {
        // TODO: resize image to fit all configurations
    }

    /**
     * Get the contents of a file
     * @param FileInfo $file
     * @return string
     */
    public function fileContent(FileInfo $file)
    {
        // TODO: Path should be to the thumb if required key in $this->params array exists
        return $this->fs->get($file->sysPath());
    }

    public function folderContent(FileInfo $info)
    {
        $result = [];
        $list = $this->fs->glob($info->sysDir(true) . '/' . '*');
        foreach ($list as $glob) {
            $globInfo = new FileInfo($this->package, $glob);
            $result[] = $globInfo->isFile() ?
                new File($this, $globInfo) :
                new Folder($this, $globInfo);
        }

        return $result;
    }

    /**
     * Get file mime type
     * @param FileInfo $file
     * @return false|string
     */
    public function fileMimeType(FileInfo $file)
    {
        return $this->fs->mimeType($file->sysPath());
    }

    /**
     * Rename current file or folder
     * @param FileInfo $file
     * @param $newName string
     * @return bool
     */
    public function rename(FileInfo $file, $newName)
    {
        return $this->fs->move($file->sysPath(), $file->rename($newName)->sysPath());
    }

    /**
     * Delete file/folder from the system
     * @param FileInfo $fileInfo
     * @return bool
     */
    public function delete(FileInfo $fileInfo)
    {
        return $this->fs->delete($fileInfo->sysPath());
    }

    /**
     * Make dir in system if it does not exists
     * @param FileInfo $file
     */
    private function mkdirIfNotExists(FileInfo $file)
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