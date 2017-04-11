<?php namespace Crip\Filesys\Services;

use Crip\Core\Support\PackageBase;
use Illuminate\Contracts\Filesystem\Filesystem;
use Intervention\Image\ImageManager;

/**
 * Class ThumbService
 * @package Crip\Filesys\Services
 */
class ThumbService
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private $sizes = [
        'thumb' => [205, 100, 'resize'],
        'xs' => [50, 25, 'resize']
    ];

    /**
     * @var PackageBase
     */
    private $package;

    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
    private $storage;

    /**
     * @var UrlService
     */
    private $url;

    /**
     * ThumbService constructor.
     * @param PackageBase $package
     */
    public function __construct(PackageBase $package)
    {
        $this->package = $package;
        $this->url = new UrlService($package);
        $this->storage = app()->make('filesystem');

        // Merge configuration file sizes in to this class
        // sizes property
        $package->mergeWithConfig($this->sizes, 'thumbs');

        $this->sizes = collect($this->sizes);
    }

    /**
     * Get all thumb sizes.
     * @return \Illuminate\Support\Collection
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * Resize image to all configured sizes
     * @param $pathToImage
     */
    public function resize($pathToImage)
    {
        $file = $this->storage->get($pathToImage);

        $this->sizes->each(function ($size, $key) use ($pathToImage, $file) {
            $img = app(ImageManager::class)->make($file);
            $path = $this->createThumbPath($pathToImage, $key);

            switch ($size[2]) {
                case 'width':
                    // resize the image to a width of $sizes[ 0 ] and constrain aspect ratio (auto height)
                    $img->resize($size[0], null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    break;
                case 'height':
                    // resize the image to a height of $sizes[ 1 ] and constrain aspect ratio (auto width)
                    $img->resize(null, $size[1], function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    break;
                // 'resize' or any other fullbacks to this
                default:
                    $img->fit($size[0], $size[1]);
                    break;
            }

            $this->storage->put($path, $img->stream()->__toString());
        });
    }

    /**
     * Rename thumbs for a image.
     * @param string $pathToImage
     * @param string $newName
     */
    public function rename($pathToImage, $newName)
    {
        $this->sizes->keys()->each(function ($size) use ($pathToImage, $newName) {
            $existing = $this->createThumbPath($pathToImage, $size);
            list($path, $oldName) = $this->getThumbPath($pathToImage, $size);

            if ($this->storage->exists($existing)) {
                $this->storage->move($existing, $path . '/' . $newName);
            }
        });
    }

    /**
     * Delete all thumbs of an image.
     * @param string $pathToImage
     * @param bool $isDir
     */
    public function delete($pathToImage, $isDir = false)
    {
        $this->sizes->keys()->each(function ($size) use ($pathToImage, $isDir) {
            list($path, $name) = $this->getThumbPath($pathToImage, $size);
            $file = $path . '/' . $name;
            if ($this->storage->exists($file)) {
                if (!$isDir) {
                    $this->storage->delete($file);
                } else {
                    $this->storage->deleteDirectory($file);
                }
            }
        });
    }

    /**
     * Create dir for thumb and return file path with this thumb prefix
     * @param $originalFilePath
     * @param $thumbSizeIdentifier
     * @return string
     */
    public function createThumbPath($originalFilePath, $thumbSizeIdentifier)
    {
        list($path, $file) = $this->getThumbPath($originalFilePath, $thumbSizeIdentifier);

        // Make sure dir exists for thumb
        $this->storage->makeDirectory($path, 0777, true, true);

        return $path . '/' . $file;
    }

    /**
     * @param $originalFilePath
     * @param $thumbSizeIdentifier
     * @return array ['path', 'filename']
     */
    public function getThumbPath($originalFilePath, $thumbSizeIdentifier)
    {
        $parts = explode('/', $originalFilePath);
        $fileName = array_pop($parts);
        array_unshift($parts, $thumbSizeIdentifier);

        return [join('/', $parts), $fileName];
    }
}