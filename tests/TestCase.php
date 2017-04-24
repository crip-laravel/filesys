<?php namespace Crip\Filesys;

use Crip\Core\Support\PackageBase;
use Illuminate\Http\UploadedFile;
use League\Flysystem\Filesystem;
use League\Flysystem\Vfs\VfsAdapter;
use VirtualFileSystem\FileSystem as Vfs;

/**
 * Class TestCase
 * @package Crip\Filesys
 */
abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var PackageBase
     */
    protected $package;

    /**
     * Setup the test environment.
     * @return void
     */
    public function setUp()
    {
        $vfs = new Vfs();

        $adapter = new VfsAdapter($vfs);

        $this->filesystem = new Filesystem($adapter);
        $this->package = \Mockery::mock(PackageBase::class, [
            'config' => ['dir' => 'dir.icon.png']
        ]);

        parent::setUp();
    }

    /**
     * Define environment setup.
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('filesystems.default', 'vfs');
        $app['config']->set('filesystems.disks.vfs', [
            'driver' => 'vfs'
        ]);

        $app['filesystem']->extend('vfs', function () {
            return $this->filesystem;
        });
    }

    /**
     * Get package providers.
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [CripFilesysServiceProvider::class];
    }

    /**
     * @param string $dir
     * @param string $relativePath
     * @return \Illuminate\Http\Request
     */
    protected function createUploadRequest($dir, $relativePath)
    {
        $stub = __DIR__ . '/' . $relativePath;
        $name = str_random(8) . '.' . pathinfo($relativePath, PATHINFO_EXTENSION);
        $path = sys_get_temp_dir() . '/' . $name;

        copy($stub, $path);

        $request = new \Illuminate\Http\Request([
            'path' => $dir
        ], [], [], [], [
            'file' => new UploadedFile($path, $relativePath, null, null, null, true)
        ]);

        return $request;
    }
}
