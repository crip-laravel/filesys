<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta id="settings"
        data-sizes="{!! $thumbs !!}"
        data-authorization="{!! $authorization !!}"
        data-params="{!! $input !!}"
        data-icon-dir="{{ $iconDir }}"
        data-dir-icon-url="{{ $dirIconUrl }}"
        data-tree-url="{{ $treeUrl }}"
        data-files-url="{{ $filesUrl }}"
        data-folders-url="{{ $foldersUrl }}"/>
  <title>CRIP Filesys</title>
  <link href="{{ config('cripfilesys.public_url') }}/styles.css" rel="stylesheet">
  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body>

<div id="app">Loading...</div>

<script src="<?php echo config('cripfilesys.public_url') ?>/app.js"></script>

</body>

</html>