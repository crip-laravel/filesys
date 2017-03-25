<?php

use Crip\Filesys\App\Controllers\FileController;
use Crip\Filesys\App\Controllers\FolderController;
use Crip\Filesys\App\Controllers\TreeController;

return [
    'public_url' => '/vendor/crip/filesys',
    'target_dir' => 'storage/uploads',
    'thumbs' => [
        'thumb' => [205, 100, 'resize',],
        'xs' => [24, 24, 'resize',],
        'sm' => [200, 200, 'resize',],
        'md' => [512, 1000, 'width',],
        'lg' => [1024, 2000, 'width',],
    ],
    'icons' => [
        'url' => '/vendor/crip/filesys/images/',
        'files' => [
            'any' => 'file.png',
            'audio' => 'audio.png',
            'css' => 'css.png',
            'dir' => 'dir.png',
            'excel' => 'excel.png',
            'file' => 'file.png',
            'html' => 'html.png',
            'img' => 'image.png',
            'js' => 'js.png',
            'pwp' => 'powerpoint.png',
            'txt' => 'txt.png',
            'video' => 'video.png',
            'word' => 'word.png',
            'zip' => 'archive.png',
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
        ],
    ],
    'block' => [
        'extensions' => ['php'],
        'mimetypes' => ["/^text\/php/"]
    ],
    'actions' => [
        'folder' => FolderController::class,
        'file' => FileController::class,
        'tree' => TreeController::class
    ]
];