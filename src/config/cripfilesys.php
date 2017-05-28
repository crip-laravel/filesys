<?php

use Crip\Filesys\App\Controllers\FileController;
use Crip\Filesys\App\Controllers\FolderController;
use Crip\Filesys\App\Controllers\TreeController;

return [

    /*
    |---------------------------------------------------------------------------
    | Public URL
    |---------------------------------------------------------------------------
    |
    | This value is public URL to location of filesys assets. This value is used
    | when backend is generating initial HTML and requesting assets for manager.
    |
    */

    'public_url' => '/vendor/crip/cripfilesys',

    /*
    |---------------------------------------------------------------------------
    | Use public storage url.
    |---------------------------------------------------------------------------
    |
    | This value is indicates usage of storage public access generator. This
    | feature may increase application speed, but in this case files will have
    | public access for everyone, no matter what. If you choose enable it make
    | sure https://laravel.com/docs/5.4/filesystem#the-public-disk symbolic link
    | is created for your storage directory in case if you use local/public
    | storage configuration.
    |
    */

    'public_storage' => false,

    /*
    |---------------------------------------------------------------------------
    | Use custom subfolder for user.
    |---------------------------------------------------------------------------
    |
    | This value is indicates value of the subfolder of currently configured
    | storage. This may be useful in case if you want each user or user group to
    | have their own folder - by default single folder is shared for everyone.
    | This can be done creating middleware for routes and defining value on
    | application start-up state. For more details take a look on a sample of
    | README.md file.
    |
    */

    'user_folder' => '',

    /*
    |---------------------------------------------------------------------------
    | Authorization configuration.
    |---------------------------------------------------------------------------
    |
    | This value may be useful if your application is SPA and you do not use
    | Laravel sessions to identify users. For packages as JWT you need pass
    | token in a request or may be used Bearer authorization for API. For web
    | routes you may pass 'token' property with value and then all API calls
    | will contain Bearer authorization replacing placeholder with passed token
    | value in a first request of UI part of filesys manager.
    |
    */
    'authorization' => [
        'web' => 'token',
        'api' => [
            'key' => 'Authorization',
            'value' => 'Bearer {token}'
        ]
    ],

    /*
    |---------------------------------------------------------------------------
    | Thumb sizes
    |---------------------------------------------------------------------------
    |
    | Here are each of the thumb size setup for your application.
    | Uploaded images will be sized to this configured Array. First argument is
    | width and second is height. Third argument describes crop type:
    | - `resize` - crop image to width and height
    | - `width`  - resize the image to a width and constrain height
    | - `height` - resize the image to a height and constrain width
    |
    */

    'thumbs' => [
        'thumb' => [205, 100, 'resize',],
        'xs' => [24, 24, 'resize',],
        'sm' => [200, 200, 'resize',],
        'md' => [512, 1000, 'width',],
        'lg' => [1024, 2000, 'width',],
    ],

    /*
    |---------------------------------------------------------------------------
    | Icons
    |---------------------------------------------------------------------------
    |
    | Filesys manager UI icons are mapped to this configuration and developer
    | can configure its own icons to fit required design in system. Each
    | configured mime type should contain configuration in this section as icons
    | are taken depending on it. Make sure that your images are in png format.
    |
    */

    'icons' => [
        'url' => '/vendor/crip/cripfilesys/images/',
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

    /*
    |---------------------------------------------------------------------------
    | Mimes
    |---------------------------------------------------------------------------
    |
    | Mime types are mapping from filesystem defined mimetype to filesys mime
    | names to be able add custom icons for files and filter by media types.
    | Some editors may predefine media type of files to show in editor and this
    | is place to map mime types to some of media group. Some drivers (like FTP)
    | does not provide filesystem mimetypes, in this cases we are using mime
    | type guesser with file extension map configured in this section.
    |
    */

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
        'map' => [
            'hqx' => 'application/mac-binhex40',
            'cpt' => 'application/mac-compactpro',
            'csv' => 'text/x-comma-separated-values',
            'bin' => 'application/octet-stream',
            'dms' => 'application/octet-stream',
            'lha' => 'application/octet-stream',
            'lzh' => 'application/octet-stream',
            'exe' => 'application/octet-stream',
            'class' => 'application/octet-stream',
            'psd' => 'application/x-photoshop',
            'so' => 'application/octet-stream',
            'sea' => 'application/octet-stream',
            'dll' => 'application/octet-stream',
            'oda' => 'application/oda',
            'pdf' => 'application/pdf',
            'ai' => 'application/pdf',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',
            'smi' => 'application/smil',
            'smil' => 'application/smil',
            'mif' => 'application/vnd.mif',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'wbxml' => 'application/wbxml',
            'wmlc' => 'application/wmlc',
            'dcr' => 'application/x-director',
            'dir' => 'application/x-director',
            'dxr' => 'application/x-director',
            'dvi' => 'application/x-dvi',
            'gtar' => 'application/x-gtar',
            'gz' => 'application/x-gzip',
            'gzip' => 'application/x-gzip',
            'php' => 'application/x-httpd-php',
            'php4' => 'application/x-httpd-php',
            'php3' => 'application/x-httpd-php',
            'phtml' => 'application/x-httpd-php',
            'phps' => 'application/x-httpd-php-source',
            'js' => 'application/javascript',
            'swf' => 'application/x-shockwave-flash',
            'sit' => 'application/x-stuffit',
            'tar' => 'application/x-tar',
            'tgz' => 'application/x-tar',
            'z' => 'application/x-compress',
            'xhtml' => 'application/xhtml+xml',
            'xht' => 'application/xhtml+xml',
            'zip' => 'application/x-zip',
            'rar' => 'application/x-rar',
            'mid' => 'audio/midi',
            'midi' => 'audio/midi',
            'mpga' => 'audio/mpeg',
            'mp2' => 'audio/mpeg',
            'mp3' => 'audio/mpeg',
            'aif' => 'audio/x-aiff',
            'aiff' => 'audio/x-aiff',
            'aifc' => 'audio/x-aiff',
            'ram' => 'audio/x-pn-realaudio',
            'rm' => 'audio/x-pn-realaudio',
            'rpm' => 'audio/x-pn-realaudio-plugin',
            'ra' => 'audio/x-realaudio',
            'rv' => 'video/vnd.rn-realvideo',
            'wav' => 'audio/x-wav',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpe' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'css' => 'text/css',
            'html' => 'text/html',
            'htm' => 'text/html',
            'shtml' => 'text/html',
            'txt' => 'text/plain',
            'text' => 'text/plain',
            'log' => 'text/plain',
            'rtx' => 'text/richtext',
            'rtf' => 'text/rtf',
            'xml' => 'application/xml',
            'xsl' => 'application/xml',
            'mpeg' => 'video/mpeg',
            'mpg' => 'video/mpeg',
            'mpe' => 'video/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',
            'avi' => 'video/x-msvideo',
            'movie' => 'video/x-sgi-movie',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'dot' => 'application/msword',
            'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'word' => 'application/msword',
            'xl' => 'application/excel',
            'eml' => 'message/rfc822',
            'json' => 'application/json',
            'pem' => 'application/x-x509-user-cert',
            'p10' => 'application/x-pkcs10',
            'p12' => 'application/x-pkcs12',
            'p7a' => 'application/x-pkcs7-signature',
            'p7c' => 'application/pkcs7-mime',
            'p7m' => 'application/pkcs7-mime',
            'p7r' => 'application/x-pkcs7-certreqresp',
            'p7s' => 'application/pkcs7-signature',
            'crt' => 'application/x-x509-ca-cert',
            'crl' => 'application/pkix-crl',
            'der' => 'application/x-x509-ca-cert',
            'kdb' => 'application/octet-stream',
            'pgp' => 'application/pgp',
            'gpg' => 'application/gpg-keys',
            'sst' => 'application/octet-stream',
            'csr' => 'application/octet-stream',
            'rsa' => 'application/x-pkcs7',
            'cer' => 'application/pkix-cert',
            '3g2' => 'video/3gpp2',
            '3gp' => 'video/3gp',
            'mp4' => 'video/mp4',
            'm4a' => 'audio/x-m4a',
            'f4v' => 'video/mp4',
            'webm' => 'video/webm',
            'aac' => 'audio/x-acc',
            'm4u' => 'application/vnd.mpegurl',
            'm3u' => 'text/plain',
            'xspf' => 'application/xspf+xml',
            'vlc' => 'application/videolan',
            'wmv' => 'video/x-ms-wmv',
            'au' => 'audio/x-au',
            'ac3' => 'audio/ac3',
            'flac' => 'audio/x-flac',
            'ogg' => 'audio/ogg',
            'kmz' => 'application/vnd.google-earth.kmz',
            'kml' => 'application/vnd.google-earth.kml+xml',
            'ics' => 'text/calendar',
            'zsh' => 'text/x-scriptzsh',
            '7zip' => 'application/x-7z-compressed',
            'cdr' => 'application/cdr',
            'wma' => 'audio/x-ms-wma',
            'jar' => 'application/java-archive',
        ]
    ],

    /*
    |---------------------------------------------------------------------------
    | Blocked file extensions and mime types
    |---------------------------------------------------------------------------
    |
    | Some files may not be allowed for end-user needs. In those cases this
    | configuration may be used to avoid this type file upload to server.
    |
    */

    'block' => [
        'extensions' => ['php'],
        'mimetypes' => ["/^text\/php/"]
    ],

    /*
    |---------------------------------------------------------------------------
    | Controller action
    |---------------------------------------------------------------------------
    |
    | Developer may want to define its own methods for parts of application
    | back-end.
    |
    */

    'actions' => [
        'folder' => FolderController::class,
        'file' => FileController::class,
        'tree' => TreeController::class
    ]
];