<?php namespace Crip\Filesys\Services;

use Crip\Core\Helpers\Str;
use Crip\Core\Support\PackageBase;

/**
 * Class TreeService
 * @package Services
 */
class TreeService
{
    /**
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
    private $storage;

    /**
     * @var PackageBase
     */
    private $package;

    /**
     * @var array
     */
    private $exclude;

    /**
     * @var string
     */
    private $root;

    /**
     * TreeService constructor.
     * @param PackageBase $package
     */
    public function __construct(PackageBase $package)
    {
        $thumbService = new ThumbService($package);
        $userFolder = $package->config('user_folder');

        $this->package = $package;
        $this->storage = app()->make('filesystem');
        $this->root = Str::normalizePath($userFolder);
        $this->exclude = $thumbService->getSizes()->keys()->all();
    }

    /**
     * Gets root folder tree content.
     * @return array
     */
    public function content(): array
    {
        $dirs = collect($this->storage->allDirectories($this->root));
        $output = [];

        $dirs->filter(function ($dir) {
            $parts = $this->split($dir);
            return !in_array($parts[0], $this->exclude);
        })->each(function ($dir) use (&$output) {
            $curr = &$output;
            foreach ($this->split($dir) as $name) {
                if (!collect($curr)->contains('name', $name)) {
                    $curr[] = [
                        'path' => $this->dirToPath($dir),
                        'name' => $name,
                        'children' => []
                    ];
                } else {
                    foreach ($curr as $key => $item) {
                        if ($item['name'] === $name)
                            $curr = &$curr[$key]['children'];
                    }
                }
            }
        });

        return $output;
    }

    /**
     * Convert dir to path.
     * @param  string $dir
     * @return string
     */
    private function dirToPath(string $dir): string
    {
        if ($this->root !== '') {
            return str_replace_first($this->root, '', $dir);
        }

        return $dir;
    }

    /**
     * Normalize and split dir path.
     * @param  string $dir
     * @return array
     */
    private function split(string $dir): array
    {
        $path = $this->dirToPath($dir);

        return explode('/', Str::normalizePath($path));
    }
}