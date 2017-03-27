<?php namespace Crip\Filesys\Services;

use Crip\Core\Contracts\ICripObject;
use Crip\Core\Helpers\Slug;
use Crip\Core\Support\PackageBase;
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
     * @return array|\Crip\Filesys\App\File|\Crip\Filesys\App\Folder
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

        return (new Blob($this->package, $path))->fullDetails();
    }

    /**
     * Create a directory.
     * @return FilesysManager
     * @throws \Exception
     */
    public function makeDirectory()
    {
        if ($this->blob === null) {
            throw new \Exception('Blob path is not set yet.');
        }

        if ($this->blob->path !== '') {
            \Storage::makeDirectory($this->blob->path);
        }

        return $this;
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
}