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
     * Parse path to file/folder.
     * @param string $path
     * @param array $params
     * @return Blob
     */
    public function parsePath($path = '', array $params = [])
    {
        $this->params = $params;

        return new Blob($this->package, $path);
    }

    /**
     * Determine if a file or directory exists.
     * @param Blob $blob
     * @return bool
     */
    public function exists(Blob $blob)
    {
        return $this->fs->exists($blob->systemPath());
    }

    /**
     * Determines is presented path for image.
     * @param Blob $blob
     * @return bool
     */
    public function isImage(Blob $blob)
    {
        return substr($blob->file->getMimeType(), 0, 5) === 'image';
    }

    /**
     * Upload file in to package configured folder.
     * @param Blob $blob
     * @param UploadedFile $upload Uploading file
     * @return string Location where file were uploaded
     */
    public function upload(Blob $blob, UploadedFile $upload)
    {
        $blob->folder->mk();

        $ext = $upload->getClientOriginalExtension();
        $name = pathinfo($upload->getClientOriginalName(), PATHINFO_FILENAME);
        // To get full path, join dir and its name

        $dir = $blob->systemPath();
        $targetFileName = $this->getUniqueFileName($dir, $name, $ext);

        $upload->move($dir, $targetFileName . '.' . $ext);

        // Update file info details after it is successfully uploaded to server
        $blob->setFile($targetFileName, $ext);

        return join('/', [$dir, $targetFileName . '.' . $ext]);
    }

    public function resizeImage(Blob $blob)
    {
        // TODO: resize image to fit all configurations
    }

    /**
     * Get the contents of a file.
     * @param Blob $blob
     * @return string
     */
    public function fileContent(Blob $blob)
    {
        // TODO: Path should be to the thumb if required key in $this->params array exists
        return $this->fs->get($blob->systemPath());
    }

    /**
     * List folder content.
     * @param Blob $blob
     * @return array
     */
    public function folderContent(Blob $blob)
    {
        $result = [];
        $list = $this->fs->glob($blob->systemPath() . '/*');
        foreach ($list as $glob) {
            $blobInfo = new Blob($this->package, $glob);
            $result[] = $blobInfo->file->isDefined() ?
                new File($blobInfo) :
                new Folder($blobInfo);
        }

        return $result;
    }

    /**
     * Rename current file or folder
     * @param Blob $blob
     * @param $newName string
     * @return Blob
     *
     * @throws \Exception
     */
    public function rename(Blob $blob, $newName)
    {
        $curr = $blob->systemPath();
        if ($blob->file->isDefined()) {
            list($name, $ext) = $blob->file->setName($newName);
            $targetName = $this->getUniqueFileName($blob->folder->getDir(), $name, $ext);
            if ($name !== $targetName) {
                $blob->file->setName($targetName);
            }
        } else {
            $newName = $this->getUniqueFileName($blob->folder->getParentDir(), $newName);
            $blob->folder->setName($newName);
        }

        if (!$this->fs->move($curr, $blob->systemPath())) {
            throw new \Exception('Could not rename.');
        }

        return $blob;
    }

    /**
     * Delete file/folder from the system
     * @param Blob $blob
     * @return bool
     */
    public function delete(Blob $blob)
    {
        return $this->fs->delete($blob->systemPath());
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
            $fullPath = $sysPath . '/' . $name . ($ext ? '.' . $ext : '');
        } while ($this->fs->exists($fullPath) && $name = $originalName . '-' . ++$i);

        return $name;
    }
}