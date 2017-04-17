<?php namespace Crip\Filesys;

use Crip\Core\Support\PackageBase;
use Crip\Filesys\Services\Blob;
use Crip\Filesys\Services\BlobMetadata;

/**
 * Class Mocks
 * @package Crip\Filesys
 */
class Mocks
{
    /**
     * @return Blob
     */
    public static function blob()
    {
        $blobMock = \Mockery::mock(Blob::class, [static::package()], [
            'getMediaType' => 'media-type',
            'getThumbUrl' => 'thumb-url',
            'getXsThumbUrl' => 'xs-thumb-url',
            'getType' => 'type',
            'getUrl' => 'url',
            'getMime' => 'mime',
            'getThumbsDetails' => ['thumb' => []]
        ]);

        $blobMock->metadata = static::meta();

        return $blobMock;
    }

    /**
     * @return BlobMetadata
     */
    public static function meta()
    {
        $metaMock = \Mockery::mock(BlobMetadata::class, [
            'getSize' => 100,
            'getDir' => 'dir',
            'getFullName' => 'full-name',
            'getName' => 'name',
            'getLastModified' => 'last-modified',
            'getPath' => 'path',
            'getExtension' => 'ext',
            'getMimeType' => 'mime-type',
        ]);

        return $metaMock;
    }

    /**
     * @return PackageBase
     */
    public static function package()
    {
        $mock = \Mockery::mock(PackageBase::class);

        return $mock;
    }
}