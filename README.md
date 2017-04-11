# CRIP Filesystem manager (v.1.1.0)

This package easily integrates filesystem manager in to your website. You can 
use it with TinyMCE editor or just stand alone popup for your input fields. CRIP
Filesys Manager is based on Vue.js framework and is stand alone single page 
application for your filesystem control on server side. 

Manager is using [Laravel Filesystem](https://laravel.com/api/5.4/Illuminate/Contracts/Filesystem/Filesystem.html)
to read and write files on the server side. This means that you can configure 
your [Laravel driver](https://laravel.com/docs/5.4/filesystem#configuration) 
and manager will fit to it. Amazon S3, FTP or local storage - your choice where keep 
files.


![Screenshoot](https://raw.githubusercontent.com/crip-laravel/filesys/master/src/public/images/screenshoot.png)



## Installation
Require package with composer:

```cmd
composer require crip-laravel/filesys
```

After package is downloaded, add `ServiceProvider` to the providers array in 
configuration file of your Laravel application `config/app.php`:

```php
'providers' = [
    ...
    Crip\Filesys\CripFilesysServiceProvider::class,
],
```

Copy the package resources and views to your local folders with the publish 
command:

```cmd
php artisan vendor:publish --provider="Crip\Filesys\CripFilesysServiceProvider"
```

Additionally you can override package configuration file publishing it to your 
application config folder:

```cmd
php artisan vendor:publish --provider="Crip\Filesys\CripFilesysServiceProvider" --tag=config
```

Filesystem manager is not configured to any of routes and you should do it 
manually. This allows to ad any middleware and will not conflict with any 
application routes, as it can be anything you choose.

Add new methods in your `app\Providers\RouteServiceProvider.php`
```php
...

/**
 * Define your route model bindings, pattern filters, etc.
 *
 * @return void
 */
public function boot()
{
    Route::pattern('crip_file', '[a-zA-Z0-9.\-\/\(\)\_\% ]+');
    Route::pattern('crip_folder', '[a-zA-Z0-9.\-\/\(\)\_\% ]+');

    parent::boot();
}

/**
 * Define the routes for the application.
 *
 * @return void
 */
public function map()
{
    $this->mapApiRoutes();
    $this->mapWebRoutes();
    $this->mapPackageRoutes();
}

/**
 * Define the "package" routes for the application.
 */
protected function mapPackageRoutes() {
    Route::prefix('packages')
        ->group(base_path('routes/package.php'));
}

...
```

Now you can add new routes file to map package controllers tou your application
routes. Create new file `routes/package.php` and add content:
```php
<?php

Route::group(['prefix' => 'filemanager'], function () {
    Route::resource('api/crip-folders', Crip\Filesys\App\Controllers\FolderController::class);
    Route::resource('api/crip-files', Crip\Filesys\App\Controllers\FileController::class);
    Route::get('api/crip-tree', Crip\Filesys\App\Controllers\TreeController::class);
    Route::get('/', Crip\Filesys\App\Controllers\ManagerController::class);
});
```

Remember - route names for `FolderController` and `FileController` are important
and should be registered in `RouteServiceProvider` `boot` method with pattern to 
correctly allocate file location in server filesystem.



## Configuration

`public_url` - Public url to assets folder. By default assets are published to 
               `/vendor/crip/filesys` and this configuration default value is 
               set to this folder.

`target_dir` - Filesystem folder, relative to application root, where files will
               be located. By default value is set to `storage/uploads` folder.

`thumbs` - Uploaded images will be sized to this configured Array. First 
           argument is `width` and second is `height`. Third argument describes
           crop type:
- `resize`  - crop image to width and height;
- `width` - resize the image to a width and constrain aspect ratio (auto height);
- `height` - resize the image to a height and constrain aspect ratio (auto width);

`icons.path` - Public url to images folder. By default images are published to 
               `/vendor/crip/filesys/images/` and this configuration default 
               value is set to this folder.

`icons.files` - Mapping array between file mime type name and icon image 
                ([type].png).

`mime.types` - mapping from file full mime type to type name (array).

`mime.media` - mapping between mime type name and media type (array).

`actions` - controller actions to allocate file and directory actions.



## Usage


### TinyMCE

Download and set up TinyMCE editor. Copy `plugins` folder from published 
resources `\public\vendor\crip\cripfilesys\tinymce\plugins` to installed TinyMCE 
editor `plugins` directory. Configure TinyMCE to enable `filesys` plugin in it:
```javascript
if (tinymce) {
  tinymce.init({
    plugins: [
      'advlist autolink link image lists charmap print preview hr anchor',
      'pagebreak searchreplace wordcount visualblocks visualchars',
      'insertdatetime media nonbreaking table contextmenu directionality',
      'emoticons paste textcolor',
      /* Creates 'Insert file' button under 'Insert' menu. */
      'cripfilesys'
    ],
    
    /* Add 'cripfilesys-btn' to editor toolbar. */
    toolbar: 'undo redo | insert | styleselect | bold italic | ' +
    'alignleft aligncenter alignright alignjustify | ' +
    'bullist numlist outdent indent | link image cripfilesys-btn',
    
    relative_urls: false,
    language: 'en',
    selector: '.tinymce',
    external_filemanager_path: '/packages/filemanager',
    
    /* Enables select buttons for 'media' and 'image' plugins. */
    external_plugins: {filemanager: '/vendor/crip/filesys/tinymce/plugin.js'}
  })
}
```

### Stand-alone filesystem manager

You can use `iframe`, `FancyBox` or `Lightbox` iframe to open the Fylesystem
manager. To handle selected file, add GET parameter to the end of the path:
`/packages/filemanager?target=callback`. You can filter visible files by they 
type: `/packages/filemanager?target=callback&type=[type]`. Supported types are:
- `document` - excel, word, pwp, html, txt, js;
- `image` - any file with mime type starting from `image/*`;
- `media` - audio, video;
- `file` - all files. This type is set by default;

Types could be configured in package configuration file `mime.media` section.

To handle filesystem manager selected file url, window object should contain 
`window.cripFilesystemManager` function witch will be called on file select with
selected file url and all GET parameters of opened window.

## Sample

```html
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" crossorigin="anonymous"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.css"
          rel="stylesheet" type="text/css">
    <script src="/tinymce/tinymce.min.js"></script>
    <script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.0.47/jquery.fancybox.js"></script>
    <script>
      tinymce.init({
        plugins: [
          'advlist autolink link image lists charmap print preview hr anchor',
          'pagebreak searchreplace wordcount visualblocks visualchars',
          'insertdatetime media nonbreaking table contextmenu directionality',
          'emoticons paste textcolor cripfilesys',
        ],
        toolbar: 'undo redo | insert | styleselect | bold italic | ' +
        'alignleft aligncenter alignright alignjustify | ' +
        'bullist numlist outdent indent | link image cripfilesys-btn',
        relative_urls: false,
        language: 'en',
        selector: '.tinymce',
        external_filemanager_path: '/packages/filemanager',
        external_plugins: {filemanager: '/vendor/crip/filesys/tinymce/plugin.js'}
      })
       
      // Callback method for input group btn
      window.cripFilesystemManager = function(fileUrl, params) {
        // will recive params.flag and params.one parameter as they are 
        // presented in href below
        console.log(fileUrl, params)
        
        if (params.flag == 'link' && params.one == 1) {
          $('#input-id').val(fileUrl)
          $.fancybox.close()
        }
      }
    
      $(document).ready(function () {
        $('.fancybox').fancybox({
          iframe: {
            preload : false,
            scrolling : 'yes',
            css: {
              maxWidth: '1200px'
            }
          }
        })
      })
    </script>
</head>
<body>
  <form>
 
    <div class="form-group">
      <textarea class="tinymce">Hello, From CRIP Filesystem!</textarea>
    </div>
    <div class="form-group">
      <div class="input-group">
        <input type="text" id="input-id" class="form-control" placeholder="File...">
        <span class="input-group-btn">
          <a class="btn btn-default fancybox" type="button" 
             href="/packages/filemanager?target=callback&flag=link&one=1&type=image">
            Select image
          </a>
        </span>
      </div>
    </div>
  </form>
</body>
</html>
```
