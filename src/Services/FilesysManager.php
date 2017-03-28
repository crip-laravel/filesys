<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Helpers\Slug;
use Crip\Core\Helpers\Str;
use Crip\Core\Support\PackageBase;
use Crip\Filesys\App\File;
use Crip\Filesys\App\Folder;
use Illuminate\Http\UploadedFile;

/**
 * Class FilesysManager
 * @package Crip\Filesys\Services
 */
class FilesysManager implements ICripObject
{
    /**
     * @var Blob
     */
    private $blob = null;

    /**
     * @var PackageBase
     */
    private $package;

    private $metadata = null;

    /**
     * FilesysManager constructor.
     * @param PackageBase $package
     * @param string $path
     */
    public function __construct(PackageBase $package, $path = '')
    {
        $this->blob = new Blob($package, $path);
        $this->package = $package;
    }

    /**
     * Write the contents of a file.
     * @param UploadedFile $uploadedFile
     * @return array|File|Folder
     * @throws \Exception
     */
    public function upload(UploadedFile $uploadedFile)
    {
        if ($this->blob === null) {
            throw new \Exception('Blob path is not set yet.');
        }

        $this->makeDirectory();

        $path = $this->blob->path;
        $ext = $uploadedFile->getClientOriginalExtension();
        $name = Slug::make(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));
        $uniqueName = $this->getUniqueFileName($path, $name, $ext);

        $fullName = $uniqueName . '.' . $ext;

        \Storage::putFileAs($path, $uploadedFile, $fullName);

        $path .= '/' . $fullName;

        $this->blob->path = $path;

        if ($this->getMetaData()->isImage()) {
            (new ThumbService($this->package))
                ->resize($this->getMetaData()->getPath());
        }

        return $this->fullDetails();
    }

    /**
     * Rename blob.
     * @param string $name
     * @return File|Folder
     */
    public function rename($name)
    {
        $name = Str::slug($name);
        if ($this->isFile()) {
            return $this->renameFile($name, $this->getMetaData()->getExtension());
        }

        return $this->renameFolder($name);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $meta = $this->getMetaData();

        if ($meta->isImage() || !$meta->isFile()) {
            $service = new ThumbService($this->package);
            $service->delete($meta->getPath(), !$meta->isFile());
        }

        if ($meta->isFile()) {
            return \Storage::delete($meta->getPath());
        }

        return \Storage::deleteDirectory($meta->getPath());
    }

    /**
     * Create a directory.
     * @param string $subDir
     * @return FilesysManager
     * @throws \Exception
     */
    public function makeDirectory($subDir = '')
    {
        if ($this->blob === null) {
            throw new \Exception('Blob path is not set yet.');
        }

        if ($subDir) {
            $this->blob->path = trim($this->blob->path . '/' . Str::slug($subDir), '/\\');
        }

        if ($this->blob->path !== '') {
            \Storage::makeDirectory($this->blob->path);
        }

        return $this;
    }

    /**
     * Get the content of a file.
     * @return string
     */
    public function fileContent()
    {
        // TODO: here should be placed validation on visibility
        return \Storage::get($this->blob->path);
    }

    /**
     * @return bool
     */
    public function blobExists()
    {
        if ($this->blob->path . '' === '') {
            return true;
        }

        return \Storage::exists($this->blob->path);
    }

    /**
     * @return bool
     */
    public function isFile()
    {
        if ($this->blobExists()) {
            $metadata = \Storage::getMetaData($this->blob->path);

            return $metadata['type'] === 'file';
        }

        return false;
    }

    /**
     * Get file mime type.
     * @return string
     */
    public function fileMimeType()
    {
        return \Storage::mimeType($this->blob->path);
    }

    /**
     * Determines is the file safe for upload.
     * @param string $ext
     * @param string $mime
     * @return bool
     */
    public function isSafe($ext, $mime)
    {
        $unsafeExtensions = $this->package->config('block.extensions');
        $unsafeMimes = $this->package->config('block.mimetypes');
        $mimeSearch = function ($mimeValue) use ($mime) {
            return preg_match($mimeValue, $mime);
        };

        if (in_array($ext, $unsafeExtensions)) {
            return false;
        }

        if (collect($unsafeMimes)->search($mimeSearch)) {
            return false;
        }

        return true;
    }

    /**
     * @return BlobMetadata
     */
    public function getMetaData()
    {
        if (!$this->metadata) {
            $this->metadata = (new BlobMetadata())->init($this->blob->path);
        }

        return $this->metadata;
    }

    /**
     * @return File|Folder
     */
    public function fullDetails()
    {
        return (new Blob($this->package, $this->blob->path))
            ->fullDetails($this->getMetaData());
    }

    /**
     * Get unique name for a file/folder in system path
     * @param $path string System full path
     * @param $name string File/Folder name
     * @param null $ext File extension
     * @return string Unique name
     */
    private function getUniqueFileName($path, $name, $ext = null)
    {
        $originalName = $name;
        $i = 0;

        do {
            $fullPath = $path . '/' . $name . ($ext ? '.' . $ext : '');
        } while (\Storage::exists($fullPath) && $name = $originalName . '-' . ++$i);

        return $name;
    }

    /**
     * @param $name
     * @param $extension
     * @return File|Folder
     */
    private function renameFile($name, $extension)
    {
        $meta = $this->getMetaData();
        $newName = $this->getUniqueFileName($meta->getDir(), $name, $extension);
        if ($meta->isImage()) {
            (new ThumbService($this->package))->rename(
                $meta->getPath(),
                $newName,
                $meta->getExtension());
        }

        $newPath = $meta->getDir() . '/' . $newName . '.' . $meta->getExtension();
        $this->blob->path = $newPath;
        \Storage::move($meta->getPath(), $newPath);

        return $this->fullDetails();
    }

    private function renameFolder($name)
    {

    }
}