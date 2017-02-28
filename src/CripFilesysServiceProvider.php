<?php namespace Crip\Filesys;

use Crip\Core\Support\CripServiceProvider;
use Crip\Core\Support\PackageBase;
use Illuminate\Foundation\AliasLoader;

/**
 * Class CripFilesysServiceProvider
 * @package Crip\Filesys
 */
class CripFilesysServiceProvider extends CripServiceProvider
{
    /**
     * @var PackageBase
     */
    private static $package;

    /**
     * php artisan vendor:publish --provider="Crip\Filesys\CripFilesysServiceProvider"
     * @return PackageBase
     */
    private static function package()
    {
        if (!self::$package) {
            self::$package = new PackageBase('cripfilesys', __DIR__);
            self::$package->publish_database = false;
            self::$package->enable_routes = false;
        }
        return self::$package;
    }

    /**
     * Bootstrap the application events.
     * @return void
     */
    public function boot()
    {
        $this->cripBoot(self::package());
    }

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->cripRegister(self::package());
    }

    /**
     * @param AliasLoader $loader
     */
    function aliasLoader(AliasLoader $loader)
    {
        // TODO: Implement aliasLoader() method.
    }
}