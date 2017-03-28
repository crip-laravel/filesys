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
     * @var Filesystem
     */
    private $fs;

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
        $this->fs = app(Filesystem::class);

        // Merge configuration file sizes in to this class
        // sizes property
        $package->mergeWithConfig($this->sizes, 'thumbs');

        $this->sizes = collect($this->sizes);
    }

    /**
     * Get all thumb sizes.
     * @return array
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
        $file = \Storage::get($pathToImage);

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

            \Storage::put($path, $img->stream()->__toString());
        });
    }

    /**
     * Rename thumbs for a image.
     * @param string $pathToImage
     * @param string $newName
     * @param string $ext
     */
    public function rename($pathToImage, $newName, $ext)
    {
        $this->sizes->each(function ($D, $size) use ($pathToImage, $newName, $ext) {
            $existing = $this->createThumbPath($pathToImage, $size);
            list($path, $oldName) = $this->getThumbPath($pathToImage, $size);

            if ($this->fs->exists($existing)) {
                \Storage::move($existing, $path . '/' . $newName . '.' . $ext);
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
        $this->sizes->each(function ($D, $size) use ($pathToImage, $isDir) {
            list($path, $name) = $this->getThumbPath($pathToImage, $size);
            $file = $path . '/' . $name;
            if ($this->fs->exists($file)) {
                if (!$isDir) {
                    $this->fs->delete($file);
                } else {
                    $this->fs->deleteDirectory($file);
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
        $this->fs->makeDirectory($path, 0777, true, true);

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