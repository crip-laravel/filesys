<?php
use Crip\Filesys\App\Controllers\FileController;
use Crip\Filesys\App\Controllers\FolderController;

return [
    'base_url' => '/packages/filemanager',
    'public_url' => '/vendor/crip/cripfilesys',
    'target_dir' => 'storage/uploads',
    'thumbs_dir' => 'thumbs',
    'thumbs' => [
        'thumb' => [205, 100, 'resize',],
        'xs' => [24, 24, 'resize',],
        'sm' => [200, 200, 'resize',],
        'md' => [512, 1000, 'width',],
        'lg' => [1024, 2000, 'width',],
    ],
    'icons' => [
        'url' => '/vendor/crip/cripfilesys/images/',
        'files' => [
            'js' => 'js.png',
            'dir' => 'dir.png',
            'css' => 'css.png',
            'txt' => 'txt.png',
            'any' => 'file.png',
            'img' => 'image.png',
            'zip' => 'archive.png',
            'pwp' => 'powerpoint.png',
            'html' => 'html.png',
            'word' => 'word.png',
            'audio' => 'audio.png',
            'video' => 'video.png',
            'excel' => 'excel.png',
        ]
    ],
    'mime' => [
        'types' => [
            'js' => [
                "/^application\/javascript/",
                "/^application\/json/",
                "/^application\/x\-javascript/",
                "/^text\/javascript/",
                "/^text\/x\-jquery\-tmpl/",
            ],
            'css' => ["/^text\/css/",],
            'txt' => ["/^text\/plain/"],
            'img' => ["/^image\/*/"],
            'zip' => [
                "/^application\/x\-gzip/",
                "/^application\/x\-rar\-compressed/",
                "/^application\/x\-7z\-compressed/",
                "/^application\/zip/",],
            'pwp' => [
                "/^application\/vnd\.ms\-powerpoint/",
                "/^application\/vnd\.openxmlformats\-officedocument\.presentationml*/"
            ],
            'html' => [
                "/^application\/xhtml\+xml/",
                "/^text\/html/"
            ],
            'word' => [
                "/^application\/msword/",
                "/^application\/vnd\.openxmlformats\-officedocument\.wordprocessingml*/"
            ],
            'audio' => ["/^audio\/*/"],
            'video' => ["/^video\/*/"],
            'excel' => [
                "/^application\/vnd.ms-excel/",
                "/^application\/vnd\.openxmlformats\-officedocument\.spreadsheetml*/"
            ]
        ],
        'media' => [
            'dir' => ['dir'],
            'image' => ['img'],
            'media' => ['audio', 'video'],
            'document' => ['excel', 'word', 'pwp', 'html', 'txt', 'js']
        ]
    ],
    'actions' => [
        'folder' => FolderController::class,
        'file' => FileController::class
    ]
];