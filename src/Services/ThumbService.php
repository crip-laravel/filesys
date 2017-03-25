<?php namespace Crip\Filesys\Services;

use Crip\Core\Helpers\Str;
use Crip\Core\Support\PackageBase;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageManager;

/**
 * Class ThumbService
 * @package Crip\Filesys\Services
 */
class ThumbService
{
    /**
     * @var array
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
    }

    /**
     * Resize image to all configured sizes
     * @param $pathToImage
     */
    public function resize($pathToImage)
    {
        foreach ($this->sizes as $key => $size) {
            $img = app(ImageManager::class)->make($pathToImage);
            $newPath = $this->createThumbPath($pathToImage, $key);

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

            $img->save($newPath);
        }
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
     * Get details of all available thumbs
     * @param $pathToImage
     * @return array
     */
    public function details($pathToImage)
    {
        $thumbs = collect([]);

        collect(array_keys($this->sizes))
            ->each(function ($size) use ($thumbs, $pathToImage) {
                list($path, $file) = $this->getThumbPath($pathToImage, $size);
                if ($this->fs->exists($path . '/' . $file)) {
                    list($width, $height) = getimagesize($path . '/' . $file);

                    $thumbs->put($size, [
                        'url' => $this->url->file($path . '/' . $file, true),
                        'size' => [$width, $height]
                    ]);
                }
            });

        return $thumbs->all();
    }

    /**
     * Rename thumbs for a image.
     * @param string $pathToImage
     * @param string $newName
     */
    public function rename($pathToImage, $newName)
    {
        collect(array_keys($this->sizes))
            ->each(function ($size) use ($pathToImage, $newName) {
                $existing = $this->createThumbPath($pathToImage, $size);
                list($path, $oldName) = $this->getThumbPath($pathToImage, $size);

                if ($this->fs->exists($existing)) {
                    rename($existing, $path . '/' . $newName);
                }
            });
    }

    /**
     * Delete all thumbs of an image.
     * @param string $pathToImage
     * @param bool $isFile
     */
    public function delete($pathToImage, $isFile)
    {
        collect(array_keys($this->sizes))
            ->each(function ($size) use ($pathToImage, $isFile) {
                list($path, $name) = $this->getThumbPath($pathToImage, $size);
                $file = $path . '/' . $name;
                if ($this->fs->exists($file)) {
                    if ($isFile) {
                        $this->fs->delete($file);
                    } else {
                        $this->fs->deleteDirectory($file);
                    }
                }
            });
    }

    /**
     * @param $originalFilePath
     * @param $thumbSizeIdentifier
     * @return array ['path', 'filename']
     */
    public function getThumbPath($originalFilePath, $thumbSizeIdentifier)
    {
        $baseDir = Str::normalizePath(base_path($this->package->config('target_dir')));
        $relativePath = str_replace($baseDir, '', $originalFilePath);
        $parts = explode('/', $relativePath);
        $fileName = array_pop($parts);
        $result = Str::normalizePath($baseDir . '/--thumbs--/' . $thumbSizeIdentifier . '/' . join('/', $parts));

        return [$result, $fileName];
    }
}