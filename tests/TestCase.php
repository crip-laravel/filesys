<?php namespace Crip\Filesys;

use Crip\Core\Support\PackageBase;
use League\Flysystem\Vfs\VfsAdapter;
use phpDocumentor\Reflection\Types\Object_;
use VirtualFileSystem\FileSystem as Vfs;
use League\Flysystem\Filesystem;

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
}
